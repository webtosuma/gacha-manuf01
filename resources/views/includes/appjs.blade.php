@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/20250619093033app.js') }}" defer></script> --}}


@else

    <script src="{{ asset('js/20250708162414app.js') }}" defer></script>

@endif

