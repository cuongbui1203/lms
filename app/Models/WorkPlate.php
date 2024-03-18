<?php

namespace App\Models;

use App\Traits\AddressTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkPlate extends Model
{
    use HasFactory, AddressTrait;

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

    protected function address(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getAddress($this->address_id);
            },
        );
    }

    protected $cast = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
}
