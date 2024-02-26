<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WarehouseDetail extends Model
{
    use HasFactory;

    public function work_plate():HasOne
    {
        return $this->hasOne(WorkPlate::class, 'wp_id');
    }
}
