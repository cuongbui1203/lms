<?php

namespace App\Models;

use App\Traits\AddressTrait;
use App\Traits\RoutingAddressTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, AddressTrait, RoutingAddressTrait;

    protected $guarded = [
        'id',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Noti::class, 'order_id', 'id');
    }

    protected $cast = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
}
