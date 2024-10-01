<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <br/>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <div class="row">
                                    <form action="{{url('courses/import') }}" method="post" enctype="multipart/form-data">
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
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in admin!") }}
                </div>
                <div class="row">
                    @if (count($courses))
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <td>codigo</td>
                                        <td>area</td>
                                        <td>curso</td>
                                        <td>ciclo</td>
                                        <td>tcurso</td>
                                        <td>grupo</td>
                                        <td>turno</td>
                                        <td>aula</td>
                                        <td>estu</td>
                                        <td>docente</td>
                                        <td>lunes</td>
                                        <td>marrtes</td>
                                        <td>miercoles</td>
                                        <td>jueves</td>
                                        <td>viernes</td>
                                    </tr>
                                </thead>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td>{{ $course->codigo_curso}}</td>
                                        <td>{{ $course->area_curricular}}</td>
                                        <td>{{ $course->nombre_curso}}</td>
                                        <td>{{ $course->ciclo}}</td>
                                        <td>{{ $course->tipo_curso}}</td>
                                        <td>{{ $course->grupo}}</td>
                                        <td>{{ $course->turno}}</td>
                                        <td>{{ $course->numero_aula}}</td>
                                        <td>{{ $course->numero_estudiantes}}</td>
                                        <td>{{ $course->docente}}</td>
                                        <td>{{ $course->lunes}}</td>
                                        <td>{{ $course->martes}}</td>
                                        <td>{{ $course->miercoles}}</td>
                                        <td>{{ $course->jueves}}</td>
                                        <td>{{ $course->viernes}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
