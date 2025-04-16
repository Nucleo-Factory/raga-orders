<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Traits\HasAuthorizations;

class PurchaseOrderComment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasAuthorizations;

    public $timestamps = true;

    protected $fillable = [
        'purchase_order_id',
        'user_id',
        'comment',
        'operacion',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con la orden de compra
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método helper para obtener el archivo adjunto
    public function getAttachment()
    {
        return $this->getFirstMedia('attachments');
    }

    public function getRole()
    {
        return $this->user->roles->first()->name ?? 'Sin rol';
    }

    /**
     * Define las colecciones de medios disponibles para este modelo
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    /**
     * Check if comment is pending
     */
    public function isPending(): bool
    {
        return $this->authorizations()
            ->where('operation_type', 'attach_file_to_comment')
            ->where('status', Authorization::STATUS_PENDING)
            ->exists();
    }

    /**
     * Check if comment is approved
     */
    public function isApproved(): bool
    {
        // Si no tiene autorizaciones o todas están aprobadas
        if (!$this->hasAttachment()) {
            return true; // Comentarios sin archivos se consideran aprobados
        }

        return $this->authorizations()
            ->where('operation_type', 'attach_file_to_comment')
            ->where('status', Authorization::STATUS_APPROVED)
            ->exists();
    }

    /**
     * Check if comment is rejected
     */
    public function isRejected(): bool
    {
        return $this->authorizations()
            ->where('operation_type', 'attach_file_to_comment')
            ->where('status', Authorization::STATUS_REJECTED)
            ->exists();
    }

    // Helper para verificar si tiene archivos adjuntos
    public function hasAttachment(): bool
    {
        return $this->getFirstMedia('attachments') !== null ||
               $this->authorizations()->where('operation_type', 'attach_file_to_comment')->exists();
    }
}
