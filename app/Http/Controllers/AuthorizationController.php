<?php

namespace App\Http\Controllers;

use App\Models\AuthorizationRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderComment;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthorizationController extends Controller
{
    protected $authorizationService;

    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * Display a listing of pending authorization requests.
     */
    public function index()
    {
        $pendingRequests = $this->authorizationService->getPendingRequests();
        return view('authorization.index', compact('pendingRequests'));
    }

    /**
     * Approve an authorization request for a PurchaseOrder.
     */
    public function approve(AuthorizationRequest $request, Request $httpRequest)
    {
        Log::info('AuthorizationController@approve siendo ejecutada', [
            'request_id' => $request->id,
            'authorizable_type' => $request->authorizable_type,
            'authorizable_id' => $request->authorizable_id,
            'operation_type' => $request->operation_type
        ]);

        $notes = $httpRequest->input('notes');

        // Guardar los datos originales antes de cambiar el estado
        $originalData = $request->data;
        $operationType = $request->operation_type;
        $purchaseOrder = null;

        if ($request->authorizable_type === PurchaseOrder::class) {
            $purchaseOrder = $request->authorizable;
        }

        // Aprobar la solicitud
        $this->authorizationService->approve($request, $notes);

        // Según el tipo de operación, realizar la acción correspondiente
        if ($request->authorizable_type === PurchaseOrder::class && $purchaseOrder) {
            if ($operationType === 'upload_file') {
                // En caso de aprobación, mostramos instrucciones para que el usuario
                // vuelva a subir el archivo ya que no se guarda temporalmente
                Session::flash('file_upload_approved', true);
                Session::flash('purchase_order_id', $purchaseOrder->id);
                // Incluir los datos originales para que se usen al subir el archivo
                Session::flash('approved_file_data', $originalData);

                return redirect()->route('purchase-orders.detail', ['id' => $purchaseOrder->id])
                    ->with('message', 'Solicitud de subida de archivo aprobada. Por favor, suba el archivo nuevamente.');
            }
            elseif ($operationType === 'attach_file_to_comment') {
                try {
                    // Verificación adicional de datos
                    Log::info('Datos de la solicitud de comentario', [
                        'request_id' => $request->id,
                        'data' => $originalData,
                        'purchase_order_id' => $purchaseOrder->id,
                        'requester_id' => $request->requester_id
                    ]);

                    if (!isset($originalData['comment'])) {
                        Log::warning('No hay contenido de comentario en los datos de la solicitud', [
                            'request_id' => $request->id
                        ]);
                        // Usar un comentario vacío o predeterminado
                        $commentText = 'Comentario sin contenido';
                    } else {
                        $commentText = $originalData['comment'];
                    }

                    // PUNTO DE DIAGNOSTICO: Verificar el modelo PurchaseOrderComment
                    Log::info('Verificando modelo PurchaseOrderComment', [
                        'class_exists' => class_exists(PurchaseOrderComment::class),
                        'fillable' => (new PurchaseOrderComment())->getFillable(),
                        'table' => (new PurchaseOrderComment())->getTable(),
                        'connection' => (new PurchaseOrderComment())->getConnectionName() ?: 'default'
                    ]);

                    // Crear el comentario manualmente con todos los datos requeridos
                    // Verificar primero si ya existe un comentario para esta solicitud
                    $existingComment = PurchaseOrderComment::where('purchase_order_id', $purchaseOrder->id)
                        ->where('user_id', $request->requester_id)
                        ->where('comment', $commentText)
                        ->where('operacion', 'Comentario con archivo (Aprobado)')
                        ->first();

                    if ($existingComment) {
                        Log::info('Ya existe un comentario para esta solicitud, reutilizando', [
                            'comment_id' => $existingComment->id
                        ]);
                        $comment = $existingComment;
                    } else {
                        // Crear el comentario directamente en la base de datos
                        DB::beginTransaction();
                        try {
                            // PUNTO DE DIAGNOSTICO: Intentar primero con create()
                            try {
                                $comment = PurchaseOrderComment::create([
                                    'purchase_order_id' => $purchaseOrder->id,
                                    'user_id' => $request->requester_id,
                                    'comment' => $commentText,
                                    'operacion' => 'Comentario con archivo (Aprobado)'
                                ]);

                                Log::info('Comentario creado con create()', [
                                    'comment_id' => $comment->id ?? 'No creado'
                                ]);
                            } catch (\Exception $innerEx) {
                                Log::error('Error al crear comentario con create()', [
                                    'error' => $innerEx->getMessage()
                                ]);

                                // Si falló create(), intentar con new + save
                                $comment = new PurchaseOrderComment();
                                $comment->purchase_order_id = $purchaseOrder->id;
                                $comment->user_id = $request->requester_id;
                                $comment->comment = $commentText;
                                $comment->operacion = 'Comentario con archivo (Aprobado)';
                                $comment->save();

                                Log::info('Comentario creado con new + save()', [
                                    'comment_id' => $comment->id ?? 'No creado'
                                ]);
                            }

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Error en la transacción de DB al crear comentario', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                            throw $e;
                        }
                    }

                    // PUNTO DE DIAGNOSTICO: Verificar si el comentario realmente se guardó
                    $savedComment = PurchaseOrderComment::find($comment->id ?? 0);

                    Log::info('Estado del comentario después de guardarlo', [
                        'comment_id' => $comment->id ?? 'No disponible',
                        'saved_comment_exists' => $savedComment ? true : false,
                        'data' => $savedComment ? $savedComment->toArray() : 'No disponible'
                    ]);

                    Log::info('Comentario creado/asignado después de aprobación', [
                        'comment_id' => $comment->id ?? 'No disponible',
                        'purchase_order_id' => $purchaseOrder->id,
                        'requester_id' => $request->requester_id,
                        'comment_text' => $commentText
                    ]);

                    // Verificar si el comentario fue realmente guardado
                    $verifyComment = PurchaseOrderComment::find($comment->id ?? 0);
                    if (!$verifyComment) {
                        Log::error('El comentario no se encontró después de guardarlo', [
                            'comment_id' => $comment->id ?? 'No disponible'
                        ]);
                        throw new \Exception('El comentario no pudo ser guardado correctamente');
                    }

                    // Pasar la información necesaria para adjuntar el archivo
                    Session::flash('comment_attachment_approved', true);
                    Session::flash('comment_id', $comment->id);
                    Session::flash('purchase_order_id', $purchaseOrder->id);

                    return redirect()->route('purchase-orders.detail', ['id' => $purchaseOrder->id])
                        ->with('message', 'Comentario aprobado. Por favor, adjunte el archivo nuevamente para completar el proceso.');
                } catch (\Exception $e) {
                    Log::error('Error al crear comentario después de aprobación', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'purchase_order_id' => $purchaseOrder->id ?? null,
                        'data' => $originalData ?? []
                    ]);

                    // Intento de último recurso - creación directa
                    try {
                        Log::info('Intentando creación de último recurso mediante SQL directo');
                        $commentId = DB::table('purchase_order_comments')->insertGetId([
                            'purchase_order_id' => $purchaseOrder->id,
                            'user_id' => $request->requester_id,
                            'comment' => $originalData['comment'] ?? 'Comentario sin contenido',
                            'operacion' => 'Comentario con archivo (Aprobado - fallback)',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        Log::info('Comentario creado con SQL directo', [
                            'comment_id' => $commentId
                        ]);

                        // Intenta continuar con este ID
                        Session::flash('comment_attachment_approved', true);
                        Session::flash('comment_id', $commentId);
                        Session::flash('purchase_order_id', $purchaseOrder->id);

                        return redirect()->route('purchase-orders.detail', ['id' => $purchaseOrder->id])
                            ->with('message', 'Comentario aprobado (con recuperación). Por favor, adjunte el archivo nuevamente para completar el proceso.');
                    } catch (\Exception $e2) {
                        Log::error('Error en creación de último recurso', [
                            'error' => $e2->getMessage()
                        ]);
                    }

                    return redirect()->route('purchase-orders.detail', ['id' => $purchaseOrder->id])
                        ->with('error', 'Error al crear el comentario: ' . $e->getMessage());
                }
            }

            return redirect()->route('purchase-orders.detail', ['id' => $purchaseOrder->id])
                ->with('message', 'Solicitud aprobada correctamente.');
        }

        return redirect()->back()
            ->with('message', 'Solicitud aprobada correctamente.');
    }

    /**
     * Reject an authorization request.
     */
    public function reject(AuthorizationRequest $request, Request $httpRequest)
    {
        $notes = $httpRequest->input('notes');

        // Rechazar la solicitud
        $this->authorizationService->reject($request, $notes);

        // Si es una solicitud de PurchaseOrder, redireccionar a la página de detalle
        if ($request->authorizable_type === PurchaseOrder::class) {
            $purchaseOrder = $request->authorizable;
            return redirect()->route('purchase-orders.detail', ['id' => $purchaseOrder->id])
                ->with('message', 'Solicitud rechazada correctamente.');
        }

        return redirect()->back()
            ->with('message', 'Solicitud rechazada correctamente.');
    }

    /**
     * Show the details of an authorization request.
     */
    public function show(AuthorizationRequest $request)
    {
        return view('authorization.show', compact('request'));
    }
}
