<div x-ignore>
    @if (auth()->check() && auth()->user()->usertype == 'admin')
        <div class="filters flex items-center w-full space-x-4 py-2" id="selector">
            <div class="flex flex-col w-full">
                <label for="docente" class="text-sm font-medium text-gray-700">Seleccionar Docente:</label>
                <select id="docente" wire:model="selectedDocente"
                    class="p-2 border rounded-lg focus:ring focus:ring-blue-300 w-full">
                    <option value="">Todos los Docentes</option>
                    @foreach ($docentes as $docente)
                        <option value="{{ $docente }}">{{ $docente }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col w-full">
                <label for="aula" class="text-sm font-medium text-gray-700">Seleccionar Aula:</label>
                <select id="aula" wire:model="selectedAula"
                    class="p-2 border rounded-lg focus:ring focus:ring-blue-300 w-full">
                    <option value="">Todas las Aulas</option>
                    @foreach ($aulas as $aula)
                        <option value="{{ $aula }}">{{ $aula }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div id="header1" style="text-align: center; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between;">
                <img src="{{ asset('img/logo1.png') }}" alt="Logo 1" style="height: 100px;">
                <div>
                    <h2>HORARIO SEMESTRE 2024</h2>
                    <p id="dynamic-info" style="margin-top: 10px;">
                        AULA: Todas las Aulas
                    </p>
                </div>
                <img src="{{ asset('img/logo2.png') }}" alt="Logo 2" style="height: 100px;">
            </div>
        </div>


        <script>
            document.addEventListener('livewire:load', function() {
                // Escuchar cambios en el docente seleccionado
                Livewire.on('docenteUpdated', function(docente) {
                    if (docente) {
                        document.getElementById('dynamic-info').innerHTML = 'Docente: ' + docente;
                    } else {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: Todas las Aulas';
                    }
                });

                // Escuchar cambios en el aula seleccionada
                Livewire.on('aulaUpdated', function(aula) {
                    if (aula) {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: ' + aula;
                    } else {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: Todas las Aulas';
                    }
                });
            });
        </script>




        <div id="calendar-container1" style="width: 100%; height: 700px; margin-top: 20px; overflow: hidden;">
            <div id="calendar" style="height: 100%;"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                // Inicializar FullCalendar
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'es',
                    initialView: 'timeGridWeek',
                    selectable: true,
                    select: function(info) {
                        // Abrir el modal y pasar la información de tiempo seleccionado
                        Livewire.emit('openModal', info.startStr, info.endStr);
                    },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay,dayGridMonth'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    },
                    slotMinTime: '07:00:00',
                    slotMaxTime: '19:00:00',
                    allDaySlot: false,
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    height: '100%',
                    eventColor: '#3788d8',

                    // Agregar la funcionalidad de clic para editar evento
                    eventClick: function(info) {
                        // Emitimos el ID del evento y el día seleccionado al hacer clic
                        Livewire.emit('editEvent', info.event.id, info.event.start.getDay());
                    }

                });

                calendar.render();

                // Escuchar eventos de Livewire para actualizar el calendario
                window.addEventListener('updateCalendar', function(event) {
                    calendar.removeAllEvents();
                    calendar.addEventSource(event.detail.events);
                    calendar.render();
                });

                // Funcionalidad del botón de imprimir
                document.getElementById('printBtn').addEventListener('click', function() {
                    window.print();
                });
            });
        </script>
    @elseif (auth()->check() && auth()->user()->usertype == 'user')
        <div class="filters px-12 py-4 flex items-center space-x-4 w-full" id="selector">
            <!-- Campo para el código de docente -->
            <div class="flex-1">
                <label for="docente" class="block text-sm font-medium text-gray-700">Código de Docente</label>
                <input type="text" id="docente" wire:model="codigoDocente" placeholder="Código de Docente"
                    class="mt-1 p-2 w-full border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>

            <!-- Selector para seleccionar Aula -->
            <div class="flex-1">
                <label for="aula" class="block text-sm font-medium text-gray-700">Seleccionar Aula:</label>
                <select id="aula" wire:model="selectedAula"
                    class="mt-1 p-2 w-full border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todas las Aulas</option>
                    @foreach ($aulas as $aula)
                        <option value="{{ $aula }}">{{ $aula }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="header1" style="text-align: center; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between;">
                <img src="{{ asset('img/logo1.png') }}" alt="Logo 1" style="height: 100px;">
                <div>
                    <h2>HORARIO SEMESTRE 2024</h2>
                    <p id="dynamic-info" style="margin-top: 10px;">
                        AULA: Todas las Aulas
                    </p>
                </div>
                <img src="{{ asset('img/logo2.png') }}" alt="Logo 2" style="height: 100px;">
            </div>
        </div>


        <script>
            document.addEventListener('livewire:load', function() {
                // Escuchar cambios en el docente seleccionado
                Livewire.on('docenteUpdated', function(docente) {
                    if (docente) {
                        document.getElementById('dynamic-info').innerHTML = 'Docente: ' + docente;
                    } else {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: Todas las Aulas';
                    }
                });

                // Escuchar cambios en el aula seleccionada
                Livewire.on('aulaUpdated', function(aula) {
                    if (aula) {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: ' + aula;
                    } else {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: Todas las Aulas';
                    }
                });
            });
        </script>




        <div id="calendar-container1" style="width: 100%; height: 700px; margin-top: 20px; overflow: hidden;">
            <div id="calendar" style="height: 100%;"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                // Inicializar FullCalendar
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'es',
                    initialView: 'timeGridWeek',
                    selectable: true,
                    select: function(info) {
                        // Abrir el modal y pasar la información de tiempo seleccionado
                        Livewire.emit('openModal', info.startStr, info.endStr);
                    },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay,dayGridMonth'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    },
                    slotMinTime: '07:00:00',
                    slotMaxTime: '19:00:00',
                    allDaySlot: false,
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    height: '100%',
                    eventColor: '#3788d8',

                    // Agregar la funcionalidad de clic para editar evento
                    eventClick: function(info) {
                        // Emitimos el ID del evento y el día seleccionado al hacer clic
                        Livewire.emit('editEvent', info.event.id, info.event.start.getDay());
                    }

                });

                calendar.render();

                // Escuchar eventos de Livewire para actualizar el calendario
                window.addEventListener('updateCalendar', function(event) {
                    calendar.removeAllEvents();
                    calendar.addEventSource(event.detail.events);
                    calendar.render();
                });

                // Funcionalidad del botón de imprimir
                document.getElementById('printBtn').addEventListener('click', function() {
                    window.print();
                });
            });
        </script>
    @else
        <div class="filters px-12 py-4 flex items-center space-x-4 w-full" id="header">
            <!-- Selector para Ciclo -->
            <div class="flex-1">
                <label for="ciclo" class="block text-sm font-medium text-gray-700">Seleccionar Ciclo:</label>
                <select id="ciclo" wire:model="selectedCiclo"
                    class="mt-1 p-2 w-full border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos los Ciclos</option>
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo }}">{{ $ciclo }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Selector para Curso -->
            <div class="flex-1">
                <label for="curso" class="block text-sm font-medium text-gray-700">Seleccionar Curso:</label>
                <select id="curso" wire:model="selectedCurso"
                    class="mt-1 p-2 w-full border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos los Cursos</option>
                    @foreach ($cursos as $curso)
                        <option value="{{ $curso }}">{{ $curso }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Selector para Grupo -->
            <div class="flex-1">
                <label for="grupo" class="block text-sm font-medium text-gray-700">Seleccionar Grupo:</label>
                <select id="grupo" wire:model="selectedGrupo"
                    class="mt-1 p-2 w-full border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos los Grupos</option>
                    @foreach ($grupos as $grupo)
                        <option value="{{ $grupo }}">{{ $grupo }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="header1" style="text-align: center; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between;">
                <img src="{{ asset('img/logo1.png') }}" alt="Logo 1" style="height: 100px;">
                <div>
                    <h2>HORARIO SEMESTRE 2024</h2>
                    <p id="dynamic-info" style="margin-top: 10px;">
                        AULA: Todas las Aulas
                    </p>
                </div>
                <img src="{{ asset('img/logo2.png') }}" alt="Logo 2" style="height: 100px;">
            </div>
        </div>


        <script>
            document.addEventListener('livewire:load', function() {
                // Escuchar cambios en el docente seleccionado
                Livewire.on('docenteUpdated', function(docente) {
                    if (docente) {
                        document.getElementById('dynamic-info').innerHTML = 'Docente: ' + docente;
                    } else {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: Todas las Aulas';
                    }
                });

                // Escuchar cambios en el aula seleccionada
                Livewire.on('aulaUpdated', function(aula) {
                    if (aula) {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: ' + aula;
                    } else {
                        document.getElementById('dynamic-info').innerHTML = 'AULA: Todas las Aulas';
                    }
                });
            });
        </script>




        <div id="calendar-container1" style="width: 100%; height: 700px; margin-top: 20px; overflow: hidden;">
            <div id="calendar" style="height: 100%;"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                // Inicializar FullCalendar
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'es',
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,timeGridDay,dayGridMonth'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    },
                    slotMinTime: '07:00:00',
                    slotMaxTime: '19:00:00',
                    allDaySlot: false,
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    height: '100%',
                    eventColor: '#3788d8',

                    // Agregar la funcionalidad de clic para editar evento
                    eventClick: function(info) {
                        // Emitimos el ID del evento y el día seleccionado al hacer clic
                        Livewire.emit('editEvent', info.event.id, info.event.start.getDay());
                    }

                });

                calendar.render();

                // Escuchar eventos de Livewire para actualizar el calendario
                window.addEventListener('updateCalendar', function(event) {
                    calendar.removeAllEvents();
                    calendar.addEventSource(event.detail.events);
                    calendar.render();
                });

                // Funcionalidad del botón de imprimir
                document.getElementById('printBtn').addEventListener('click', function() {
                    window.print();
                });
            });
        </script>
    @endif
    <style>
        /* Estilos optimizados para imprimir FullCalendar 6 */
        @media print {

            /* Ocultar los elementos no necesarios durante la impresión */
            #selector,
            #nav,
            #header,
            button,
            .btn,
            .fc-header-toolbar {
                display: none !important;
            }

            /* Hacer visible el calendario y su encabezado */
            #header1,
            #calendar-container1 {
                visibility: visible !important;
                display: block !important;
                position: relative !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
            }



            /* Ajustar el tamaño del contenedor del calendario */
            #calendar-container1 {
                height: 635px !important;
                overflow: visible !important;
            }

            /* Asegurar que los días del calendario se distribuyan uniformemente */
            .fc-col-header-cell,
            .fc-daygrid-day {
                width: calc(100% / 7) !important;
                /* Distribuir uniformemente los días de la semana */
            }

            /* Ajustar la altura de las horas del calendario */
            .fc-timegrid-slot {
                height: auto !important;
                /* Ajuste dinámico de altura según el contenido */
            }

            /* Eliminar cualquier borde y usar todo el ancho disponible */
            .fc {
                width: 100% !important;
                border: none !important;
            }

            /* Configuración para imprimir en formato horizontal (landscape) */
            @page {
                size: landscape;
                margin: 2%;
            }

            /* Asegurar que los márgenes y rellenos de todos los elementos estén desactivados */
            * {
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }
        }
    </style>




    <div class="px-2 py-1"><button id="printBtn" class="btn btn-primary" style="margin-bottom: 10px;">
            Imprimir Horario
        </button></div>
</div>
