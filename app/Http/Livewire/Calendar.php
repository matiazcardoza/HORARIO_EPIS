<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Docente;
use Illuminate\Support\Facades\Log;

class Calendar extends Component
{
    public $events = [];
    public $docentes = [];
    public $aulas = [];
    public $selectedDocente = '';
    public $selectedAula = '';
    public $codigoDocente = '';

    public $cursos = [];
    public $selectedCurso = '';

    public $ciclos = [];
    public $selectedCiclo = '';

    public $grupos = [];
    public $selectedGrupo = '';

    protected $listeners = ['eventUpdated' => 'loadEvents', 'eventAdded' => 'loadEvents'];

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
                $parte = trim($parte); // Asegurarse de que no haya espacios vacíos
                if ($parte !== '') { // Filtrar los valores vacíos
                    $aulas[] = $parte;
                }
            }
        }

        // Eliminar aulas duplicadas
        $this->aulas = array_unique($aulas);

        // Cargar los docentes
        $this->docentes = Course::distinct()->pluck('docente')->filter()->toArray();

        $this->cursos = Course::distinct()->pluck('nombre_curso')->filter()->toArray();

        $this->ciclos = Course::distinct()->pluck('ciclo')->filter()->toArray();

        $this->grupos = Course::distinct()->pluck('grupo')->filter()->toArray();

        // Cargar todos los eventos inicialmente
        $this->loadEvents();
    }

    public function loadEvents()
    {
        // Inicializamos la consulta
        $query = Course::query();

        // Registrar el código ingresado


        // Filtrar por código de docente si se proporcionó
        if ($this->codigoDocente && !$this->selectedAula) {
            // Buscar el docente por código usando la tabla Docente
            $docente = Docente::where('codigo_docente', 'like', '%' . $this->codigoDocente . '%')->first();
            if ($docente) {

                // Filtrar cursos que tengan el nombre de docente relacionado al código
                $query->where('docente', $docente->nombre_docente);
            } else {

                // Si no se encuentra, limpiar eventos y retornar
                $this->events = [];
                $this->dispatchBrowserEvent('updateCalendar', ['events' => $this->events]);
                return;
            }
        } elseif ($this->selectedDocente && !$this->selectedAula) {
            // Si se ha seleccionado un docente por nombre
            $query->where('docente', $this->selectedDocente);
        }

        // Filtro por aula
        if ($this->selectedAula) {
            $query->where('numero_aula', 'like', '%' . $this->selectedAula . '%');
        }

        if ($this->selectedCurso) {
            $query->where('nombre_curso', 'like', '%' . $this->selectedCurso . '%');
        }

        if ($this->selectedCiclo) {
            $query->where('ciclo', $this->selectedCiclo);
        }

        if ($this->selectedGrupo) {
            $query->where('grupo', $this->selectedGrupo);
        }

        // Obtener los cursos filtrados
        $courses = $query->get();

        $this->events = []; // Reiniciar eventos

        // Generar eventos para cada curso
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



    public function updatedCodigoDocente()
    {
        $this->selectedAula = '';
        if (strlen($this->codigoDocente) === 6) {

            $this->loadEvents();
        } else {
            // Si no tiene 6 dígitos, limpiar los eventos
            $this->events = [];
            $this->dispatchBrowserEvent('updateCalendar', ['events' => $this->events]);
        }
    }


    public function updatedSelectedDocente()
    {
        // Limpiar el filtro de aula al seleccionar un docente
        $this->selectedAula = '';
        $this->emit('docenteUpdated', $this->selectedDocente);
        $this->loadEvents();
    }

    public function updatedSelectedAula()
    {
        // Limpiar el filtro de docente al seleccionar un aula
        $this->codigoDocente = '';
        $this->selectedDocente = '';
        $this->emit('aulaUpdated', $this->selectedAula);
        $this->loadEvents();
    }

    public function updatedSelectedCurso()
    {

        $this->loadEvents();
    }

    public function updatedSelectedCiclo()
    {
        $this->loadEvents();
    }

    public function updatedSelectedGrupo()
    {
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

                // Dividir las aulas por "/"
                $aulas = explode('/', $course->numero_aula);

                // Contar cuántas aulas hay
                $totalAulas = count($aulas);

                // Definir el orden de los días en los que el curso tiene clases
                $diasConClases = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

                // Filtrar los días que efectivamente tienen clases
                $diasActivos = array_filter($diasConClases, function ($dia) use ($course) {
                    return !empty($course->$dia);  // Solo incluir los días con eventos
                });

                // Encontrar el índice del día actual dentro de los días activos
                $indiceDelDia = array_search($day, $diasActivos);

                // Si el índice del día es válido, asignamos el aula respetando el orden
                if ($indiceDelDia !== false) {
                    $aulaParaElDia = $aulas[$indiceDelDia % $totalAulas]; // Asignar el aula respetando el orden
                }

                // Solo agregar el evento si el aula para el día coincide con la selección o no hay selección
                if (isset($aulaParaElDia) && ($aulaParaElDia === $this->selectedAula || $this->selectedAula === '')) {
                    $this->events[] = [
                        'id' => $course->id,
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
