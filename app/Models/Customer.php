<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'nombre',
        'apellidos',
        'sexo',
        'fecha_nacimiento',
    ];
}
