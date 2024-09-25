<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;

class CoursesImport implements ToModel
{
    public function model(array $row)
    {
        return new Course([
            'codigo_curso' => $row[0],
            'area_curricular' => $row[1],
            'nombre_curso' => $row[2],
            'ciclo' => $row[3],
            'tipo_curso' => $row[4],
            'grupo' => $row[5],
            'turno' => $row[6],
            'numero_aula' => $row[7],
            'numero_estudiantes' => $row[8],
            'docente' => $row[10],
            'lunes' => $row[11],
            'martes' => $row[12],
            'miercoles' => $row[13],
            'jueves' => $row[14],
            'viernes' => $row[15],
        ]);
    }
}
