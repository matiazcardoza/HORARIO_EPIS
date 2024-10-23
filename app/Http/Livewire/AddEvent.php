<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Docente;

class AddEvent extends Component
{
    public $startTime;
    public $endTime;
    public $selectedCourse;
    public $selectedAula;
    public $selectedGroup; // Verificar este campo
    public $selectedTurno;
    public $selectedArea;
    public $codigoDocente;
    public $selectedCiclo;
    public $numeroEstudiantes;

    public $courses = [];
    public $aulas = [];
    public $grupos = [];
    public $turnos = [];
    public $areas = [];

    public $showModal = false;
    public $formattedTime = '';

    public $newCourse;
    public $codigoCurso;
    public $newGroup; // Nuevo grupo si no existe en el selector
    public $dayColumn = '';

    protected $rules = [
        'selectedCourse' => 'nullable|string',
        'newCourse' => 'nullable|string|required_if:selectedCourse,null',
        'codigoCurso' => 'nullable|string|required_with:newCourse',
        'selectedAula' => 'required|string',
        'selectedGroup' => 'required|string', // Asegurarse de que el grupo no sea nulo
        'newGroup' => 'nullable|string|required_if:selectedGroup,null',
        'selectedTurno' => 'required|string',
        'selectedArea' => 'required|string',
        'codigoDocente' => 'nullable|string|exists:docentes,codigo_docente',
        'startTime' => 'required|integer|min:0|max:23',
        'endTime' => 'required|integer|min:0|max:23',
        'selectedCiclo' => 'required|integer',
        'numeroEstudiantes' => 'nullable|integer|min:1',
    ];

    protected $listeners = ['openModal' => 'handleOpenModal'];

    public function mount()
    {
        $this->aulas = Course::distinct()->pluck('numero_aula', 'numero_aula')
            ->map(function ($aula) {
                return explode('/', $aula);
            })
            ->flatten()
            ->filter(function ($aula) {
                return trim($aula) !== ''; // Filtrar los valores vacíos
            })
            ->unique()
            ->toArray();

        $this->courses = Course::distinct()->pluck('nombre_curso', 'id')->toArray();
        $this->grupos = Course::distinct()->pluck('grupo', 'grupo')->toArray(); // Verificar grupos disponibles
        $this->turnos = Course::distinct()->pluck('turno', 'turno')->toArray();
        $this->areas = Course::distinct()->pluck('area_curricular', 'area_curricular')->toArray();
    }

    public function updatedSelectedCourse($courseName)
    {
        if ($courseName) {
            $course = Course::where('nombre_curso', $courseName)->first();

            if ($course) {
                $this->selectedAula = $course->numero_aula;
                $this->selectedGroup = $course->grupo; // Asegurarse de que el grupo se cargue correctamente
                $this->selectedArea = $course->area_curricular;
                $this->selectedCiclo = $course->ciclo;
            } else {
                $this->selectedAula = null;
                $this->selectedGroup = null;
                $this->selectedArea = null;
                $this->selectedCiclo = null;
            }
        }
    }

    public function handleOpenModal($start, $end)
    {
        $this->startTime = intval(substr($start, 11, 2));
        $this->endTime = intval(substr($end, 11, 2));

        $this->formattedTime = str_pad($this->startTime, 2, '0', STR_PAD_LEFT) . ' - ' . str_pad($this->endTime, 2, '0', STR_PAD_LEFT);

        $dayOfWeek = date('w', strtotime($start));

        switch ($dayOfWeek) {
            case 1:
                $this->dayColumn = 'lunes';
                break;
            case 2:
                $this->dayColumn = 'martes';
                break;
            case 3:
                $this->dayColumn = 'miercoles';
                break;
            case 4:
                $this->dayColumn = 'jueves';
                break;
            case 5:
                $this->dayColumn = 'viernes';
                break;
            default:
                $this->dayColumn = null;
        }

        if (is_null($this->dayColumn)) {
            session()->flash('error', 'No se puede programar eventos los fines de semana.');
            return;
        }

        $hour = $this->startTime;
        $this->selectedTurno = $hour < 12 ? 'M' : 'T';

        $this->showModal = true;
    }

    public function addEvent()
    {
        try {
            $this->validate();

            $course = $this->newCourse ? $this->createNewCourse() : $this->updateExistingCourse();

            if ($course) {
                session()->flash('message', 'El evento se guardó correctamente.');
                $this->resetForm();
                $this->showModal = false;
                $this->emit('eventAdded');
            } else {
                session()->flash('error', 'No se pudo guardar el curso. Intente de nuevo.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al procesar su solicitud: ' . $e->getMessage());
        }
    }

    public function createNewCourse()
    {
        $docente = $this->codigoDocente ?: 'Sin Docente';

        // Asignar el grupo seleccionado o nuevo grupo
        $grupo = $this->selectedGroup ?: ($this->newGroup ?: 'Sin Grupo');

        return Course::create([
            'nombre_curso' => $this->newCourse,
            'codigo_curso' => $this->codigoCurso,
            'numero_aula' => $this->selectedAula,
            'grupo' => $grupo, // Asignar grupo correctamente
            'turno' => $this->selectedTurno,
            'area_curricular' => $this->selectedArea,
            'docente' => $docente,
            'ciclo' => $this->selectedCiclo,
            'numero_estudiantes' => $this->numeroEstudiantes ?: 0, // Valor predeterminado si es nulo
            $this->dayColumn => $this->startTime . ' - ' . $this->endTime
        ]);
    }


    public function updateExistingCourse()
    {
        $docente = Docente::where('codigo_docente', $this->codigoDocente)->first();

        if ($docente) {
            $course = Course::where('nombre_curso', $this->selectedCourse)
                ->where('docente', $docente->nombre_docente)
                ->first();

            if ($course) {
                $existingAulas = explode('/', $course->numero_aula);
                $aulasPorSemana = [];

                $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

                foreach ($diasSemana as $index => $dia) {
                    if (!empty($course->$dia)) {
                        $aulaDelDia = $existingAulas[$index] ?? $existingAulas[0];
                        $aulasPorSemana[$index] = $aulaDelDia;
                    } elseif ($dia == $this->dayColumn) {
                        $aulasPorSemana[$index] = $this->selectedAula;
                    } else {
                        $aulasPorSemana[$index] = $existingAulas[0];
                    }
                }

                $course->numero_aula = implode('/', $aulasPorSemana);
                $course->{$this->dayColumn} = $this->formattedTime;
                $course->save();

                return $course;
            }
        }

        return null;
    }

    public function resetForm()
    {
        $this->reset(['selectedCourse', 'selectedAula', 'selectedGroup', 'selectedTurno', 'selectedArea', 'codigoDocente', 'newCourse', 'newGroup', 'startTime', 'endTime', 'selectedCiclo', 'numeroEstudiantes']);
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.add-event');
    }
}
