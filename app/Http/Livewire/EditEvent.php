<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Course;

class EditEvent extends Component
{
    public $eventId;
    public $selectedCourse;
    public $selectedAula;
    public $selectedGroup;
    public $selectedTurno;
    public $selectedArea;
    public $codigoDocente;
    public $startTime;
    public $endTime;
    public $dayColumn;  // Representa el día de la semana en el que ocurre el evento

    public $showModal = false;
    public $aulas = [];  // Variable para las aulas disponibles

    protected $listeners = ['editEvent' => 'loadEvent'];

    public function loadEvent($eventId, $selectedDay)
    {
        $this->eventId = $eventId;

        // Cargar los datos del evento
        $course = Course::find($eventId);

        if ($course) {
            $this->selectedCourse = $course->nombre_curso;

            // Definir los días de la semana para obtener el día correcto basado en el seleccionado
            $diasSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            $this->dayColumn = $diasSemana[$selectedDay];  // Cargar el día seleccionado

            // Cargar las horas del día seleccionado
            if (!empty($course->{$this->dayColumn})) {
                list($this->startTime, $this->endTime) = explode(' - ', $course->{$this->dayColumn});
            }

            // Dividir las aulas en función del día seleccionado
            $aulas = explode('/', $course->numero_aula);

            // Asignar el aula correspondiente al día seleccionado
            $this->selectedAula = $aulas[$selectedDay - 1] ?? $aulas[0]; // Tomar el aula correcta para el día

            // Cargar todas las aulas disponibles (de la base de datos), separando las combinadas
            $aulasRaw = Course::pluck('numero_aula')->filter()->toArray();
            $aulasDisponibles = [];

            foreach ($aulasRaw as $aula) {
                // Separar las aulas combinadas por "/"
                $partesAula = explode('/', $aula);
                foreach ($partesAula as $parte) {
                    $parte = trim($parte);  // Limpiar los espacios adicionales
                    if ($parte !== '') {  // Filtrar los valores vacíos
                        $aulasDisponibles[] = $parte;
                    }
                }
            }

            // Eliminar duplicados y asignar todas las aulas separadas a la lista de selección
            $this->aulas = array_unique($aulasDisponibles);

            $this->showModal = true;  // Mostrar el modal
        }
    }






    public function saveEvent()
    {
        $course = Course::find($this->eventId);

        if ($course) {
            $course->nombre_curso = $this->selectedCourse;

            // Obtener las aulas actuales separadas
            $aulas = explode('/', $course->numero_aula);

            // Definir los días de la semana
            $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

            // Encontrar el índice del día que estamos editando
            $indiceDia = array_search($this->dayColumn, $diasSemana);

            if ($indiceDia !== false) {
                // Asegurarnos de que la cantidad de aulas coincida con los eventos en la semana
                // Si no hay suficientes aulas en el array, agregamos vacíos para que coincida con los días
                while (count($aulas) < count($diasSemana)) {
                    $aulas[] = '';  // Agregar espacios vacíos para completar los días
                }

                // Reemplazar o agregar el aula para el día seleccionado
                $aulas[$indiceDia] = $this->selectedAula;

                // Verificar si todas las aulas son iguales
                $aulasUnicas = array_unique(array_filter($aulas));  // Eliminamos vacíos y duplicados
                if (count($aulasUnicas) === 1) {
                    // Si todas las aulas son iguales, dejamos solo una
                    $course->numero_aula = $aulasUnicas[0];
                } else {
                    // Si hay aulas diferentes, las concatenamos con '/'
                    $course->numero_aula = implode('/', $aulas);
                }

                // Actualizar el rango de horas en el día correspondiente
                $course->{$this->dayColumn} = $this->startTime . ' - ' . $this->endTime;

                // Guardar los cambios en la base de datos
                $course->save();

                session()->flash('message', 'Evento actualizado correctamente.');
                $this->showModal = false;
                $this->emit('eventUpdated');
            } else {
                session()->flash('error', 'No se pudo identificar el día seleccionado.');
            }
        }
    }






    public function deleteEvent()
    {
        Course::destroy($this->eventId);

        session()->flash('message', 'Evento eliminado correctamente.');
        $this->showModal = false;
        $this->emit('eventDeleted');
    }

    public function render()
    {
        return view('livewire.edit-event');
    }
}
