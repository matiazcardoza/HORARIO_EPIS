<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Docente;
use Livewire\WithPagination;
use App\Models\Course;

class Docentes extends Component
{
    use WithPagination;

    public $search = ''; // Campo para búsqueda
    public $selectedDocenteId;
    public $selectedCourses = []; // Almacenar los IDs de los cursos seleccionados para eliminación
    public $showDeleteModal = false;

    protected $paginationTheme = 'tailwind'; // Usar paginación estilo Tailwind

    public $courses; // Asegurarse de que es una colección de Eloquent

    // Reglas de validación
    protected $rules = [
        'selectedCourses' => 'array',
        'selectedCourses.*' => 'exists:courses,id',
    ];

    public function updatingSearch()
    {
        // Reinicia la paginación al realizar una nueva búsqueda
        $this->resetPage();
    }

    public function confirmDelete($docenteId)
    {
        $docente = Docente::findOrFail($docenteId);
        $this->selectedDocenteId = $docenteId;

        // Obtener los cursos relacionados con el docente
        $this->courses = Course::where('docente', $docente->nombre_docente)->get(); // Mantén esto como una colección

        // Verificar si la colección no está vacía y pluck the IDs
        if ($this->courses->isNotEmpty()) {
            $this->selectedCourses = $this->courses->pluck('id')->toArray(); // Pluck IDs como array
        } else {
            $this->selectedCourses = []; // Si no hay cursos, poner un array vacío
        }

        $this->showDeleteModal = true; // Mostrar modal de eliminación
    }

    public function deleteSelectedCourses()
    {
        // Validar antes de realizar la acción
        $this->validate();

        // Actualizar los cursos seleccionados para que tengan "Sin Docente"
        Course::whereIn('id', $this->selectedCourses)->update(['docente' => 'Sin Docente']);

        // Verificar si el docente sigue teniendo cursos
        $docente = Docente::find($this->selectedDocenteId);
        $remainingCourses = Course::where('docente', $docente->nombre_docente)->count();

        // Si no tiene más cursos, eliminar al docente
        if ($remainingCourses === 0) {
            $docente->delete();
            session()->flash('message', 'Docente eliminado correctamente y los cursos han sido actualizados a "Sin Docente".');
        } else {
            session()->flash('message', 'Los cursos seleccionados fueron eliminados, pero el docente sigue teniendo otros cursos.');
        }

        // Cerrar el modal y recargar la lista
        $this->showDeleteModal = false;
        $this->resetPage(); // Recargar la lista de docentes
    }

    public function render()
    {
        // Obtener los docentes filtrados por nombre
        $docentes = Docente::where('nombre_docente', 'like', '%' . $this->search . '%')->paginate(10);

        return view('livewire.docente', ['docentes' => $docentes]);
    }

    protected $listeners = ['docenteUpdated' => '$refresh', 'confirmDelete'];
}
