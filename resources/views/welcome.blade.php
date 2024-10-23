<x-app-layout>
    @if (Route::has('login'))
        <!-- Hero Section with Background Image -->
        <div id="header" class="relative bg-cover bg-center text-white py-24"
            style="background-image: url('{{ asset('img/fondoi.jpg') }}');">
            <div class="absolute inset-0 "></div> <!-- Overlay for better text readability -->

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold mb-6">
                    ¡Bienvenido a HORARIOEPIS!
                </h1>
                <p class="text-lg mb-8">
                    Organiza tu horario de forma fácil y rápida. Accede o regístrate para empezar a gestionar tus
                    actividades de manera eficiente.
                </p>

                <!-- Login and Register Buttons -->
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm bg-white text-purple-700 hover:bg-gray-100 px-6 py-3 rounded-md transition duration-300 shadow-lg font-semibold no-underline">
                            Inicio
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm bg-white text-purple-700 hover:bg-gray-100 px-6 py-3 rounded-md transition duration-300 shadow-lg font-semibold no-underline">
                            Ingresar
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="text-sm bg-white text-purple-700 hover:bg-gray-100 px-6 py-3 rounded-md transition duration-300 shadow-lg font-semibold no-underline">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @endif

    <div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg">
                    <div class="py-12 px-12">
                        @livewire('calendar')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
