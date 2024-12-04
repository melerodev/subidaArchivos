<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'type', 'image', 'image64'];

    public $timestamps = false; // Deshabilitar timestamps automáticos

    // Otros atributos y métodos del modelo
}