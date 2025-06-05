<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Funcionario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'funcionarios';

    protected $fillable = [
        'nome', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
