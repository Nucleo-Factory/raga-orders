<?php

namespace App\Services;

use App\Models\AuthorizationRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthorizationService
{
    /**
     * Crea una nueva solicitud de autorización
     */
    public function createRequest(Model $model, string $operationType, array $data = []): AuthorizationRequest
    {
        return $model->authorizationRequests()->create([
            'operation_id' => Str::uuid(),
            'requester_id' => Auth::id(),
            'operation_type' => $operationType,
            'status' => 'pending',
            'data' => $data,
        ]);
    }

    /**
     * Aprueba una solicitud de autorización
     */
    public function approve(AuthorizationRequest $request, ?string $notes = null): bool
    {
        return $request->update([
            'status' => 'approved',
            'authorizer_id' => Auth::id(),
            'authorized_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Rechaza una solicitud de autorización
     */
    public function reject(AuthorizationRequest $request, ?string $notes = null): bool
    {
        return $request->update([
            'status' => 'rejected',
            'authorizer_id' => Auth::id(),
            'authorized_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Obtiene todas las solicitudes pendientes
     */
    public function getPendingRequests()
    {
        return AuthorizationRequest::with(['requester', 'authorizable'])
                                  ->where('status', 'pending')
                                  ->latest()
                                  ->get();
    }

    /**
     * Verifica si existe una operación pendiente de aprobación
     */
    public function isOperationPending(string $operationType, Model $model): bool
    {
        return AuthorizationRequest::where('operation_type', $operationType)
                                  ->where('authorizable_type', get_class($model))
                                  ->where('authorizable_id', $model->id)
                                  ->where('status', 'pending')
                                  ->exists();
    }

    /**
     * Obtiene todas las solicitudes (pendientes y aprobadas) para un modelo dado
     */
    public function getAllRequestsForModel(Model $model)
    {
        return AuthorizationRequest::with(['requester', 'authorizer'])
                                  ->where('authorizable_type', get_class($model))
                                  ->where('authorizable_id', $model->id)
                                  ->whereIn('status', ['pending', 'approved'])
                                  ->latest()
                                  ->get();
    }
}
