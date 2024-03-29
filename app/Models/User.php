<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guard = [
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'updated_at' => 'timestamp',
        'created_at' => 'timestamp'
    ];

    // protected function createdAt(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => (new Carbon($value))->format('Y-m-d H:i:s'),
    //     );
    // }

    // protected function updatedAt(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => (new Carbon($value))->format('Y-m-d H:i:s'),
    //     );
    // }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function img(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'img_id');
    }

    public function work_plate(): BelongsTo
    {
        return $this->belongsTo(WorkPlate::class, 'wp_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'id', 'driver_id');
    }
}