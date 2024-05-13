<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'dob',
        'username',
        'address_id',
        'img_id',
    ];

    protected $guarded = [
        'role_id',
        'password',
    ];

    protected $appends = [
        'address',
    ];

    protected $with = ['role'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'address_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y/m/d H:i',
        'password' => 'hashed',
        'dob' => 'date:Y/m/d',
    ];

    protected function address(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (array_key_exists('address_id', $this->attributes)) {
                    $arrayAddress = explode('|', $this->attributes['address_id']);
                    $address = getAddress($arrayAddress[0]);
                    if ($address) {
                        $address->{'address'} = $arrayAddress[1] ?? '';
                    }

                    return $address;
                }

                return null;
            },
        );
    }

    protected function addressId(): Attribute
    {
        return Attribute::make(
            set: fn(array $address) => $address[0] . '|' . $address[1] ?? '',
        );
    }

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
