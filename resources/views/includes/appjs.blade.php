@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/20250519184626app.js') }}" defer></script> --}}


@else

    <script src="{{ asset('js/20250519184626app.js') }}" defer></script>

@endif

