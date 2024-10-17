<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Docente;
use App\Models\Course;

class UpdateDocente extends Component
{
    public $docente_id;
    public $nombre_docente;
    public $codigo_docente;
    public $original_nombre_docente;

    public $isOpen = false; // Estado del modal

    protected $listeners = ['openModal' => 'openModal'];

    // Abrir el modal con los datos del docente
    public function openModal($id)
    {
        $docente = Docente::findOrFail($id);
        $this->docente_id = $docente->id;
        $this->nombre_docente = $docente->nombre_docente;
        $this->codigo_docente = $docente->codigo_docente;
        $this->original_nombre_docente = $docente->nombre_docente; // Guardamos el nombre original para comparar más tarde
        $this->isOpen = true;
    }

    // Cerrar el modal
    public function closeModal()
    {
        $this->reset(); // Restablecer los campos
        $this->isOpen = false;
    }

    // Guardar los cambios
    public function save()
    {
        $this->validate([
            'nombre_docente' => 'required|string|max:255',
            'codigo_docente' => 'required|string|max:255|unique:docentes,codigo_docente,' . $this->docente_id,
        ]);

        $docente = Docente::findOrFail($this->docente_id);
        $docente->update([
            'nombre_docente' => $this->nombre_docente,
            'codigo_docente' => $this->codigo_docente,
        ]);

        // Verificar si el nombre del docente cambió
        if ($this->nombre_docente !== $this->original_nombre_docente) {
            // Actualizar el nombre del docente en todos los cursos relacionados
            Course::where('docente', $this->original_nombre_docente)
                ->update(['docente' => $this->nombre_docente]);
        }

        // Emitir un evento para Livewire y disparar la alerta
        $this->emit('docenteUpdated');  // Emitir evento para refrescar la lista
        $this->dispatchBrowserEvent('docente-updated', ['message' => 'Docente actualizado correctamente.']);

        $this->closeModal(); // Cerrar el modal después de guardar
    }

    public function render()
    {
        return view('livewire.update-docente');
    }
}
