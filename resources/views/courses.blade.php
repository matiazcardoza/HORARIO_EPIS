<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                    <button class="btn btn-succes">EXPORTAR</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>