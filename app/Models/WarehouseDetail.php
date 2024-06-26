<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseDetail extends Model
{
    use HasFactory;

    public function work_plate(): BelongsTo
    {
        return $this->belongsTo(WorkPlate::class, 'wp_id');
    }

    protected $fillable = [
        'max_payload',
        'payload',
    ];
}
