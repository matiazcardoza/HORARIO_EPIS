<div>
    <!-- Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto" style="display: {{ $showModal ? 'block' : 'none' }}">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md w-full max-w-lg">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Agregar Nuevo Evento</h2>

                <!-- Mensajes de éxito y error -->
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

                <!-- Formulario para agregar un nuevo evento -->
                <form wire:submit.prevent="addEvent">
                    <!-- Nombre del curso -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del
                            Curso</label>
                        <select wire:model="selectedCourse"
                            class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">Seleccione un curso</option>
                            @foreach ($courses as $id => $course)
                                <option value="{{ $course }}">{{ $course }}</option>
                            @endforeach
                        </select>

                        <!-- Nuevo curso para admin -->
                        @if (!$selectedCourse && auth()->check() && auth()->user()->usertype == 'admin')
                            <input type="text" wire:model="newCourse" placeholder="Ingresar nuevo curso"
                                class="w-full p-2 border rounded-lg mt-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            @error('newCourse')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        @endif

                        <!-- Código del curso -->
                        @if ($newCourse)
                            <input type="text" wire:model="codigoCurso" placeholder="Ingresar código del curso"
                                class="w-full p-2 border rounded-lg mt-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            @error('codigoCurso')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>

                    <!-- Número de aula -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número de Aula</label>
                        <select wire:model="selectedAula"
                            class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">Seleccione un aula</option>
                            @foreach ($aulas as $aula)
                                <option value="{{ $aula }}">{{ $aula }}</option>
                            @endforeach
                        </select>
                        @error('selectedAula')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ciclo -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ciclo</label>
                        <select wire:model="selectedCiclo"
                            class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">Seleccione un ciclo</option>
                            @foreach (range(1, 10) as $ciclo)
                                <option value="{{ $ciclo }}">{{ $ciclo }}</option>
                            @endforeach
                        </select>
                        @error('selectedCiclo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Grupo -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</label>
                        <select wire:model="selectedGroup"
                            class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">Seleccione un grupo</option>
                            @foreach ($grupos as $grupo)
                                <option value="{{ $grupo }}">{{ $grupo }}</option>
                            @endforeach
                        </select>
                        @if (!$selectedGroup && auth()->check() && auth()->user()->usertype == 'admin')
                            <input type="text" wire:model="newGroup" placeholder="Ingresar nuevo grupo"
                                class="w-full p-2 border rounded-lg mt-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                        @endif
                        @error('newGroup')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Turno (automático) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Turno</label>
                        <input type="text" wire:model="selectedTurno"
                            class="w-full p-2 border rounded-lg bg-gray-200 dark:bg-gray-700 dark:text-gray-200"
                            readonly />
                    </div>

                    <!-- Área Curricular -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Área
                            Curricular</label>
                        <select wire:model="selectedArea"
                            class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">Seleccione un área</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area }}">{{ $area }}</option>
                            @endforeach
                        </select>
                        @error('selectedArea')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Código del Docente -->
                    @if (!$newCourse)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Código del
                                Docente</label>
                            <input type="text" wire:model="codigoDocente"
                                class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            @error('codigoDocente')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Horario (Inicio - Fin) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Horario (Inicio -
                            Fin)</label>
                        <input type="text" value="{{ $formattedTime }}"
                            class="w-full p-2 border rounded-lg bg-gray-200 dark:bg-gray-700 dark:text-gray-200"
                            readonly />
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="resetForm"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para mostrar errores -->
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('mostrarError', message => {
                alert(message); // Mostrar el mensaje de error como una alerta
            });
        });
    </script>
</div>
