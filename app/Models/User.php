<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type', //Se añade este type para identificar si es usuario o doctor
        'email',
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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //sirve para declarar que users tiene una relación con doctor
    //cada user id se refiere a un doctor id
    public function doctor(){
        return $this->hasOne(Doctor::class, 'doc_id');

    }

    public function user_details(){
        return $this->hasOne(UserDetails::class, 'user_id');

    }

    //Un usuario puede tener muchos appointments
    public function appointments(){
        return $this->hasMany(Appointments::class, 'user_id');

    }

    //Un usuario puede tener muchas reviews
    public function reviews(){
        return $this->hasMany(Reviews::class, 'user_id');

    }

}