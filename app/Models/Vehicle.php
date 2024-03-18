<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function driver(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'driver_id');
    }

    protected $cast = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
}
