<div class="p-6 sm:px-8 sm:pb-8">
    <div id="calendar"></div>
</div>

@script
    <script>
        const calendarEl = document.getElementById('calendar');
        const calendar = new Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locales: ['de', 'en'],
            firstDay: 1,
            events: @json($meetings),
            views: {
                dayGridMonth: { buttonText: 'month' },
                listWeek: { buttonText: 'list' }
            },
            headerToolbar: {
                right: 'dayGridMonth,listWeek today prev,next'
            },
        });
        calendar.render();
    </script>
@endscript
