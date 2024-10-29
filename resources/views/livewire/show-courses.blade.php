<div>
    <x-slot name="header" id="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" id="header">
            Bienvenido, {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">


                @livewire('add-event')
                @livewire('edit-event')
                <div class="px-12">
                    @livewire('calendar')
                </div>
                <div class="container mx-auto p-2" id="selector">
                    <!-- Espaciado superior -->
                    <div
                        class="flex flex-col md:flex-row justify-center items-center space-y-2 md:space-y-0 md:space-x-2">
                        <!-- Formulario de importación -->
                        <form action="{{ url('courses/import') }}" method="post" enctype="multipart/form-data"
                            class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                            @csrf
                            <div>
                                <input type="file" name="document"
                                    class="p-2 border rounded-md focus:ring-2 focus:ring-blue-300">
                            </div>
                            <div>
                                <button
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200"
                                    type="submit">
                                    IMPORTAR
                                </button>
                            </div>
                        </form>

                        <!-- Botón de exportación -->
                        <form action="{{ url('courses/export') }}" enctype="multipart/form-data">
                            <button
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition duration-200">
                                EXPORTAR
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
