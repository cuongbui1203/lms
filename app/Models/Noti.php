<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Noti extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $appends = ['from_address', 'to_address'];

    protected $fillable = [
        'order_id',
        'from_id',
        'to_id',
        'status_id',
        'description',
    ];

    protected $hidden = [
        'from_address_id',
        'to_address_id',
        'address_current_id',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function from(): HasOne
    {
        return $this->hasOne(WorkPlate::class, 'id', 'from_id');
    }

    public function to(): HasOne
    {
        return $this->hasOne(WorkPlate::class, 'id', 'to_id');
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    protected function fromAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $arrayAddress = explode('|', $this->attributes['from_address_id']);
                $address = getAddress($arrayAddress[0]);
                if (!is_null($address)) {
                    $address->{'address'} = $arrayAddress[1] ?? '';
                }

                return $address;
            },
        );
    }

    protected function toAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $arrayAddress = explode('|', $this->attributes['to_address_id']);
                $address = getAddress($arrayAddress[0]);
                if (!is_null($address)) {
                    $address->{'address'} = $arrayAddress[1] ?? '';
                }

                return $address;
            }
        );
    }

    protected function fromAddressId(): Attribute
    {
        return Attribute::make(
            set: fn(array $address) => $address[0] . '|' . $address[1]
        );
    }
    protected function toAddressId(): Attribute
    {
        return Attribute::make(
            set: fn(array $address) => $address[0] . '|' . $address[1]
        );
    }

}
