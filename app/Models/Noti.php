<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Noti extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'order_id',
        'from_id',
        'to_id',
        'status_id',
        'description',
    ];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
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
}
