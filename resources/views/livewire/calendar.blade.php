<div x-ignore>
    <button id="printBtn" class="btn btn-primary" style="margin-bottom: 10px;">
        Imprimir Horario
    </button>

    <div class="filters">
        <label for="docente">Seleccionar Docente:</label>
        <select id="docente" wire:model="selectedDocente">
            <option value="">Todos los Docentes</option>
            @foreach ($docentes as $docente)
                <option value="{{ $docente }}">{{ $docente }}</option>
            @endforeach
        </select>

        <label for="aula">Seleccionar Aula:</label>
        <select id="aula" wire:model="selectedAula">
            <option value="">Todas las Aulas</option>
            @foreach ($aulas as $aula)
                <option value="{{ $aula }}">{{ $aula }}</option>
            @endforeach
        </select>
    </div>

    <div id="calendar-container" style="width: 100%; height: 700px; margin-top: 20px; overflow: hidden;">
        <div id="calendar" style="height: 100%;"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Inicializar FullCalendar sin módulos, directamente desde el CDN
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
                eventColor: '#3788d8'
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
</div>
