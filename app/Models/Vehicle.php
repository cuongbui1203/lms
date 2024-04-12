<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'payload',
        'type_id',
        'driver_id',
        'max_payload',
        'goods_type',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function goodsType(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'goods_type', 'id');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'driver_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'vehicle_id', 'id');
    }
}
