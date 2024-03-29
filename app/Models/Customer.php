<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'users_id';

    protected $fillable = [
        'users_id',
        'nombre',
        'apellidos',
        'sexo',
        'fecha_nacimiento',
    ];
}
