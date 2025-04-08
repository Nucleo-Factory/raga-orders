<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $fillable = [
        'release_date',
        'material',
        'short_text',
        'purchase_requisition',
        'supplying_plant',
        'unit_of_measure',
        'plant',
        'planned_delivery_time',
        'mrp_controller',
        'vendor_name',
        'vendor_code',
    ];

}
