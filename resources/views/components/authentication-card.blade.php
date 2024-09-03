<div class="min-h-screen flex">
    <div class="flex bg-cyan-600 md:bg-white flex-col justify-center items-center w-full md:w-2/5 h-100 ">
        {{ $slot }}
    </div>
    <div class="hidden md:flex justify-center items-center h-100 flex-1 bg-cyan-600">
        {{ $logo }}
    </div>
</div>