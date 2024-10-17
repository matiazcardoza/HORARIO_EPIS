<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Agregar Nuevo Docente y Asignar Cursos</h2>

    <!-- Mostrar mensaje de éxito -->
    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Formulario para agregar nuevo docente -->
    <form wire:submit.prevent="addDocente">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nombre del Docente</label>
            <input type="text" wire:model="nombre_docente" class="w-full p-2 border rounded" />
            @error('nombre_docente')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Código del Docente</label>
            <input type="text" wire:model="codigo_docente" class="w-full p-2 border rounded" />
            @error('codigo_docente')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Seleccionar cursos sin docente -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Seleccionar Cursos sin Docente</label>
            @foreach ($coursesWithoutDocente as $course)
                <div class="flex items-center my-2">
                    <input type="checkbox" wire:model="selectedCourses" value="{{ $course->id }}" />
                    <label class="ml-2">{{ $course->nombre_curso }}</label>
                </div>
            @endforeach
            @error('selectedCourses')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
        </div>
    </form>
</div>
