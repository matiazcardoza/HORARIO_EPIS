<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoursesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Ignorar la fila de encabezados
        if (isset($row['codigo_del_curso']) && $row['codigo_del_curso'] === "CÓDIGO DEL CURSO") {
            return; // Ignorar la fila de encabezados
        }

        //dd($row);

        return new Course([
            'codigo_curso' => $row['codigo_del_curso'],
            'area_curricular' => $row['area_curricular1'],
            'nombre_curso' => $row['nombre_del_curso'],
            'ciclo' => $row['ciclo2'],
            'tipo_curso' => $row['tipo_de_cursos3'],
            'grupo' => $row['grupo4'],
            'turno' => $row['turno5'],
            'numero_aula' => $row['n_aula'],
            'numero_estudiantes' => $row['estu'],
            'docente' => $row['apellidos_y_nombres_del_docente'] ?? 'Sin Docente',
            'lunes' => $row['lunes'],
            'martes' => $row['martes'],
            'miercoles' => $row['miercoles'],
            'jueves' => $row['jueves'],
            'viernes' => $row['viernes'],
        ]);
    }
}
