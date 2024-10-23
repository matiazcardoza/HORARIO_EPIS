<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Course;

class ShowCourses extends Component
{
    public $courses;
    public $docentes = [];
    public $aulas = [];

    public function mount()
    {
        // Aquí se carga la colección de cursos
        $this->courses = Course::all(); // Asegúrate de que esto devuelva una colección
    }
    public function render()
    {
        return view('livewire.show-courses');
    }
}
