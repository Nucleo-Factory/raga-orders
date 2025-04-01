<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable = [
        'key',
        'name',
        'category',
    ];

    public function preferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }
}
