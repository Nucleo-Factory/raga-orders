<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderComment extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'purchase_order_id',
        'user_id',
        'comment',
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
}
