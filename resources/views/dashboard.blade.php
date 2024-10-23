<x-app-layout>
    <div>
        <x-slot name="header" id="header">
            <h2 id="header" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Bienvenido, {{ Auth::user()->name }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                    @livewire('add-event')
                    @livewire('edit-event')

                    <div class="py-12 px-12">
                        @livewire('calendar')
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
