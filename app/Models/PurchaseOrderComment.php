<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PurchaseOrderComment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $timestamps = true;

    protected $fillable = [
        'purchase_order_id',
        'user_id',
        'comment',
        'operacion',
        'created_at',
        'updated_at',
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
}
