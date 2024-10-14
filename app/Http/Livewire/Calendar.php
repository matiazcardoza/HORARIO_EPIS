<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Course;
use Illuminate\Support\Facades\Log;

class Calendar extends Component
{
    public $events = [];
    public $docentes = [];
    public $aulas = [];
    public $selectedDocente = '';
    public $selectedAula = '';

    public function mount()
    {
        // Obtener todas las aulas disponibles, incluidas las que tienen "/"
        $aulasRaw = Course::pluck('numero_aula')->filter()->toArray();

        $aulas = [];

        // Procesar las aulas, separando las que tienen "/"
        foreach ($aulasRaw as $aula) {
            // Si el aula tiene "/", separamos las partes y las añadimos al array
            $partesAula = explode('/', $aula);
            foreach ($partesAula as $parte) {
                $aulas[] = trim($parte);  // Asegurarse de que no haya espacios
            }
        }

        // Eliminar aulas duplicadas
        $this->aulas = array_unique($aulas);

        // Cargar los docentes
        $this->docentes = Course::distinct()->pluck('docente')->filter()->toArray();

        // Cargar todos los eventos inicialmente
        $this->loadEvents();
    }

    public function loadEvents()
    {
        // Inicializamos la consulta
        $query = Course::query();

        // Filtro independiente por docente
        if ($this->selectedDocente && !$this->selectedAula) {
            $query->where('docente', $this->selectedDocente);
        }

        // Filtro independiente por aula
        if ($this->selectedAula && !$this->selectedDocente) {
            // No se usa like ni orWhere; el aula será tratada con precisión más adelante
            $query->where(function ($q) {
                $q->where('numero_aula', 'like', '%' . $this->selectedAula . '%');
            });
        }

        // Obtener los cursos filtrados
        $courses = $query->get();
        $this->events = []; // Reiniciar los eventos

        // Generar eventos para cada día de la semana
        foreach ($courses as $course) {
            $this->generateWeeklyEvents($course, 'lunes');
            $this->generateWeeklyEvents($course, 'martes');
            $this->generateWeeklyEvents($course, 'miercoles');
            $this->generateWeeklyEvents($course, 'jueves');
            $this->generateWeeklyEvents($course, 'viernes');
        }

        // Emitir evento a JavaScript para actualizar el calendario
        $this->dispatchBrowserEvent('updateCalendar', ['events' => $this->events]);
    }


    public function updatedSelectedDocente()
    {
        // Limpiar el filtro de aula al seleccionar un docente
        $this->selectedAula = '';
        $this->loadEvents();
    }

    public function updatedSelectedAula()
    {
        // Limpiar el filtro de docente al seleccionar un aula
        $this->selectedDocente = '';
        $this->loadEvents();
    }

    public function generateWeeklyEvents($course, $day)
    {
        if (!empty($course->$day)) {
            // Dividir el rango de horas y limpiar espacios
            $timeRange = array_map('trim', explode('-', $course->$day));

            if (count($timeRange) === 2) {
                $startTime = $timeRange[0];
                $endTime = $timeRange[1];

                // Dividir las aulas por "/", si es necesario
                $aulas = explode('/', $course->numero_aula);

                // Determinar el aula correspondiente al día actual
                $aulaParaElDia = '';
                switch ($day) {
                    case 'lunes':
                        $aulaParaElDia = $aulas[0] ?? '';  // Aula para lunes
                        break;
                    case 'martes':
                        $aulaParaElDia = $aulas[1] ?? $aulas[0]; // Aula para martes (usa la segunda si existe)
                        break;
                        // Repetir para los demás días si es necesario
                    case 'miercoles':
                        $aulaParaElDia = $aulas[1] ?? $aulas[0];
                        break;
                    case 'jueves':
                        $aulaParaElDia = $aulas[1] ?? $aulas[0];
                        break;
                    case 'viernes':
                        $aulaParaElDia = $aulas[1] ?? $aulas[0];
                        break;
                }

                // Solo agregar el evento si el aula para el día coincide con la selección
                if ($aulaParaElDia === $this->selectedAula || $this->selectedAula === '') {
                    $this->events[] = [
                        'title' => $course->nombre_curso . ' - Aula ' . $aulaParaElDia,
                        'daysOfWeek' => [$this->mapDayToNumber($day)],
                        'startTime' => $startTime . ':00',
                        'endTime' => $endTime . ':00',
                        'startRecur' => now()->startOfWeek()->format('Y-m-d'),
                        'endRecur' => now()->addYear()->endOfWeek()->format('Y-m-d'),
                    ];
                }
            }
        }
    }

    public function mapDayToNumber($day)
    {
        return match ($day) {
            'lunes' => 1,
            'martes' => 2,
            'miercoles' => 3,
            'jueves' => 4,
            'viernes' => 5,
            default => null,
        };
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
