<div>
    @if ($isOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-xl w-1/3">
                    <h2 class="text-xl font-semibold mb-4">Editar Docente</h2>

                    <!-- Formulario para editar -->
                    <form wire:submit.prevent="save">
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

                        <div class="flex justify-end">
                            <button type="button" wire:click="closeModal"
                                class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
                                Cancelar
                            </button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
