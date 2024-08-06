<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Mockery\Generator\Generator;
//use Illuminate\Auth\MustVerifyEmail;
use App\Models\Traits\GeneratesIban;
use App\Traits\HasCurrencyConversion;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, GeneratesIban,HasCurrencyConversion;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'iban',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getIbanAttribute($value)
    {
        return Str::pad($this->attributes['iban'] . random_int(0, 99999), 22, '0', STR_PAD_LEFT);
    }
}
