<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkPlate extends Model
{
    use HasFactory;

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'wp_id');
    }

    public function detail(): HasOne
    {
        return $this->hasOne(WarehouseDetail::class, 'wp_id');
    }

    protected function addressId(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => getAddress($value),
        );
    }
}
