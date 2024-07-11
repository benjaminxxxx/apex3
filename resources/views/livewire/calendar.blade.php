<div>
    <x-pop class="flex justify-center">
        <div id="calendar" class="w-full"></div>
    </x-pop>
    @push('scripts')
    <script>
        const calendar = new VanillaCalendar('#calendar');
        calendar.init();
    </script>
    @endpush
</div>
