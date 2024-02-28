<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\Shop;

class Post extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'imagen',
        'descripcion',
        'posts_types_id',
        'fecha_publicacion',
        'fecha_inicio',
        'fecha_fin',
        'activo'
    ];
}
