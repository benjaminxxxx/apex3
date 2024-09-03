<div>
    <x-card class="flex justify-center">
        <div id="calendar" class="w-full max-w-full xs-zoom"></div>
    </x-card>
    <style>
        @media(max-width:1540px) {
            .xs-zoom {
                zoom: 0.8;
            } 
        }
        
    </style>
    @push('scripts')
        <script>
            const calendar = new VanillaCalendar('#calendar');
            calendar.init();
        </script>
    @endpush
</div>
