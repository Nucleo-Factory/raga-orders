<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'material_id',
        'description',
        'legacy_material',
        'contract',
        'order_quantity',
        'qty_unit',
        'price_per_unit',
        'price_per_uon',
        'net_value',
        'vat_rate',
        'vat_value',
        'delivery_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'price_per_uon' => 'decimal:2',
        'net_value' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'vat_value' => 'decimal:2',
        'delivery_date' => 'date',
    ];

    /**
     * Get the purchase orders for the product.
     */
    public function purchaseOrders(): BelongsToMany
    {
        return $this->belongsToMany(PurchaseOrder::class, 'purchase_order_product')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }
}
