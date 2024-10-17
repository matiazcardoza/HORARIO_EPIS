<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Docentes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-12">
                    <div class="px-6 py-4">
                        <div class="flex space-x-4 mb-4">
                            <!-- Campo de entrada para buscar docentes por nombre -->
                            <input type="text" wire:model="search" class="p-2 border rounded w-full"
                                placeholder="Buscar por nombre de docente">
                        </div>
                    </div>

                    @if ($docentes->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre del Docente
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Código del Docente
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($docentes as $docente)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $docente->nombre_docente }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $docente->codigo_docente }}</td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <!-- Botón para abrir el modal de edición -->
                                            <button wire:click="$emit('openModal', {{ $docente->id }})"
                                                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-700">
                                                Editar
                                            </button>
                                            <!-- Botón para eliminar -->
                                            <button wire:click="confirmDelete({{ $docente->id }})"
                                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="px-6 py-4">
                            No existe ningún registro coincidente
                        </div>
                    @endif

                    <div class="px-6 py-3">
                        <!-- Paginación -->
                        {{ $docentes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación de cursos relacionados -->
    @if ($showDeleteModal)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-xl w-1/3">
                    <h2 class="text-xl font-semibold mb-4">Eliminar Docente y Seleccionar Cursos</h2>

                    <p>Seleccione los cursos de los que desea eliminar al docente:</p>

                    <!-- Listado de cursos -->
                    @foreach ($courses as $course)
                        <div class="flex items-center my-2">
                            <!-- Checkbox para seleccionar los cursos -->
                            <input type="checkbox" wire:model="selectedCourses" value="{{ $course->id }}">
                            <label class="ml-2">{{ $course->nombre_curso }}</label>
                        </div>
                    @endforeach

                    <div class="flex justify-end mt-4">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
                            Cancelar
                        </button>
                        <button wire:click="deleteSelectedCourses" class="bg-red-500 text-white px-4 py-2 rounded">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        window.addEventListener('docente-updated', event => {
            alert(event.detail.message); // Muestra una alerta con el mensaje enviado
        });
    </script>

    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Componente para el modal de edición -->
    @livewire('update-docente')
</div>
