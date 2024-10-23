<div>
    <!-- Modal -->
    @if ($showModal)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Editar Evento</h2>

                    <!-- Mensaje de éxito -->
                    @if (session()->has('message'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <!-- Edición del curso -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                        <input type="text" wire:model="selectedCourse"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                    </div>

                    <!-- Selector de aulas disponibles -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aula</label>
                        <select wire:model="selectedAula"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option value="">Selecciona un aula</option>
                            @foreach ($aulas as $aula)
                                <option value="{{ $aula }}">{{ $aula }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Edición de la hora de inicio -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora de Inicio</label>
                        <select wire:model="startTime"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @for ($i = 7; $i <= 18; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ $i }}:00</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Edición de la hora de fin -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora de Fin</label>
                        <select wire:model="endTime"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @for ($i = 8; $i <= 19; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ $i }}:00</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="deleteEvent"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            Eliminar
                        </button>
                        <button type="button" wire:click="saveEvent"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Guardar
                        </button>
                        <button type="button" wire:click="$set('showModal', false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
