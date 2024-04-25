<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkPlate extends Model
{
    use HasFactory;

    protected $hidden = [
        'address_id',
    ];

    protected $casts = [
        'cap' => 'integer',
    ];

    protected $appends = ['address', 'manager'];

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

    protected function manager(): Attribute
    {
        return Attribute::make(
            get: function () {
                return User::where('role_id', '=', RoleEnum::MANAGER)
                    ->where('wp_id', '=', $this->attributes['id'])
                    ->first(['id', 'name']);
            }
        );
    }

    protected function address(): Attribute
    {
        return Attribute::make(
            get: function () {
                $arrayAddress = explode('|', $this->original['address_id']);
                $address = getAddress($arrayAddress[0]);
                $address->{'address'} = $arrayAddress[1] ?? '';

                return $address;
            },
        );
    }

    protected function addressId(): Attribute
    {
        return Attribute::make(
            set: fn(array $address) => $address[0] . '|' . $address[1] ?? '',
        );
    }
}
