<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    public function posts(){
        return $this->belongsToMany(Post::class);
    }

    protected $fillable = [
        'users_id',
        'nombre',
        'categories_id',
        'direccion',
        'descripcion',
        'tokens_id',
    ];
}
