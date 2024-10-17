<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bienvenido, {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <br />
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-6">
                            <div class="row">
                                <form action="{{ url('courses/import') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-6">
                                        <input type="file" name="document">
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-primary" type="submit">IMPORTAR</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ url('courses/export') }}" enctype="multipart/form-data">
                                <button class="btn btn-success">EXPORTAR</button>
                            </form>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.add-docente') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Agregar Nuevo Docente
                </a>

                <div class="py-12 px-12">
                    @livewire('calendar')
                </div>
            </div>
        </div>
    </div>
</div>
