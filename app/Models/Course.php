<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_curso',
        'area_curricular',
        'nombre_curso',
        'ciclo',
        'tipo_curso',
        'grupo',
        'turno',
        'numero_aula',
        'numero_estudiantes',
        'docente',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes'
    ];
}
