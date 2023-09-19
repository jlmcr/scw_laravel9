{{-- custom-img/fondo-escritorio.png --}}
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white" style="background-image: url({{ asset('') }}); background-size:auto 100%; background-repeat:no-repeat; background-position: center" >
{{--     background: url(url) no-repeat center --}}
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg" style="background-color: rgba(207, 207, 218, 0.842); color: black">
        {{ $slot }}
    </div>
</div>
