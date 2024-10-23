<div>
    <!-- Botón para abrir el modal -->
    <button wire:click="$set('showModal', true)"
        class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
        Agregar Nuevo Docente
    </button>

    <!-- Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto" style="display: {{ $showModal ? 'block' : 'none' }}">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-lg">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Agregar Nuevo Docente</h2>

                <!-- Mostrar mensaje de éxito o error -->
                @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-500 text-white p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Formulario para agregar nuevo docente -->
                <form wire:submit.prevent="addDocente">
                    <!-- Nombre del docente -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del
                            Docente</label>
                        <input type="text" wire:model="nombre_docente"
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                        @error('nombre_docente')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Código del docente -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código del
                            Docente</label>
                        <input type="text" wire:model="codigo_docente"
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                        @error('codigo_docente')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Seleccionar cursos sin docente -->
                    <!-- Seleccionar cursos sin docente -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seleccionar Cursos sin
                            Docente</label>
                        @foreach ($coursesWithoutDocente as $course)
                            <div class="flex items-center my-2">
                                <!-- Checkbox para seleccionar los cursos -->
                                <input type="checkbox" wire:model="selectedCourses" value="{{ $course->id }}"
                                    class="form-checkbox h-4 w-4 text-blue-600 dark:text-blue-500" />
                                <!-- Mostrar el curso y su grupo -->
                                <label class="ml-2 text-gray-700 dark:text-gray-300">
                                    {{ $course->nombre_curso }} (Grupo: {{ $course->grupo }})
                                    <!-- Aquí se muestra el grupo -->
                                </label>
                            </div>
                        @endforeach
                        @error('selectedCourses')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>



                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
