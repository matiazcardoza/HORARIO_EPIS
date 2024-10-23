<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Docente;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddDocente extends Component
{
    public $nombre_docente;
    public $codigo_docente;
    public $selectedCourses = []; // Cursos seleccionados sin docente
    public $coursesWithoutDocente = []; // Cursos sin docente
    public $showModal = false;

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
        $this->validate();


        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Crear el nuevo docente
            $docente = Docente::create([
                'nombre_docente' => $this->nombre_docente,
                'codigo_docente' => $this->codigo_docente,
            ]);

            if (!$docente) {
                throw new \Exception('No se pudo crear el docente.');
            }

            // Actualizar los cursos seleccionados con el nuevo docente
            if ($this->selectedCourses) {
                Course::whereIn('id', $this->selectedCourses)
                    ->update(['docente' => $docente->nombre_docente]);
            }

            // Confirmar la transacción
            DB::commit();

            // Emitir un evento para actualizar la lista de docentes
            $this->emit('docenteAdded');

            // Mostrar un mensaje de éxito
            session()->flash('message', 'Docente creado y asignado a los cursos seleccionados correctamente.');
        } catch (\Exception $e) {
            // Si hay un error, deshacer la transacción
            DB::rollBack();
            session()->flash('error', 'Error al crear el docente: ' . $e->getMessage());
        } finally {
            // Resetear el formulario y refrescar la lista de cursos
            $this->reset(['nombre_docente', 'codigo_docente', 'selectedCourses']);
            $this->coursesWithoutDocente = Course::where('docente', 'Sin Docente')->get();
        }
    }

    protected $listeners = ['docenteAdded' => 'closeModal', 'docenteDeleted' => 'refreshCourses'];

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function refreshCourses()
    {
        $this->coursesWithoutDocente = Course::where('docente', 'Sin Docente')->get();
    }

    public function render()
    {
        return view('livewire.add-docente', [
            'coursesWithoutDocente' => $this->coursesWithoutDocente,
        ]);
    }
}
