<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'contact_person',
        'vendor_direccion',
        'vendor_codigo_postal',
        'vendor_pais',
        'vendor_estado',
        'vendor_telefono',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the company that owns the vendor.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the purchase orders for the vendor.
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
