<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $appends = ['mass'];

    protected $with = ['type'];

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Noti::class, 'order_id', 'id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'id', 'vehicle_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    protected $cast = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected function senderAddress(): Attribute
    {
        return Attribute::make(
            get: fn() => getAddress($this->attributes['sender_address_id']),
        );
    }

    protected function receiverAddress(): Attribute
    {
        return Attribute::make(
            get: fn() => getAddress($this->attributes['receiver_address_id']),
        );
    }

    protected function mass(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->details->sum('mass'),
        );
    }
}
