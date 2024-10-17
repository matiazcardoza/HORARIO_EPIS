<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Docente;
use App\Models\Course;
use Illuminate\Support\Facades\DB; // Agregar para manejar transacciones

class AddDocente extends Component
{
    public $nombre_docente;
    public $codigo_docente;
    public $selectedCourses = []; // Cursos seleccionados sin docente
    public $coursesWithoutDocente = []; // Cursos sin docente

    protected $rules = [
        'nombre_docente' => 'required|string|max:255',
        'codigo_docente' => 'required|string|max:255|unique:docentes,codigo_docente',
        'selectedCourses' => 'required|array',
        'selectedCourses.*' => 'exists:courses,id',
    ];

    public function mount()
    {
        // Obtener todos los cursos que tienen el campo 'docente' como 'Sin Docente'
        $this->coursesWithoutDocente = Course::where('docente', 'Sin Docente')->get();
    }

    public function addDocente()
    {
        // Validar los datos de entrada
        $this->validate();

        DB::beginTransaction(); // Iniciar una transacción para asegurar consistencia

        try {
            // Crear el nuevo docente en la tabla 'docentes'
            $docente = Docente::firstOrCreate(
                ['nombre_docente' => $this->nombre_docente],
                ['codigo_docente' => $this->codigo_docente]
            );

            // Actualizar los cursos seleccionados en la tabla 'courses'
            Course::whereIn('id', $this->selectedCourses)->update(['docente' => $docente->nombre_docente]);

            DB::commit(); // Confirmar transacción si todo sale bien

            // Emitir un evento para Livewire que actualice la lista de docentes
            $this->emit('docenteAdded');

            // Mostrar un mensaje de éxito
            session()->flash('message', 'Docente creado y asignado a los cursos seleccionados correctamente.');

            // Resetear el formulario y refrescar la lista de cursos sin docente
            $this->reset(['nombre_docente', 'codigo_docente', 'selectedCourses']);
            $this->coursesWithoutDocente = Course::where('docente', 'Sin Docente')->get();
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir cambios si algo falla
            session()->flash('error', 'Error al crear el docente: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.add-docente', [
            'coursesWithoutDocente' => $this->coursesWithoutDocente,
        ]);
    }
}
