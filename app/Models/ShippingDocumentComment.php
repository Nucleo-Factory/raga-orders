<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ShippingDocumentComment extends Model {
    protected $fillable = [
        'shipping_document_id',
        'user_id',
        'comment',
    ];

    public function shippingDocument() {
        return $this->belongsTo(ShippingDocument::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->format('d/m/Y H:i');
    }
}
