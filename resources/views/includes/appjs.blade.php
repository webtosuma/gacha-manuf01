@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/20260305163801app.js') }}" defer></script> --}}

@else

    <script src="{{ asset('js/20260306225129app.js') }}" defer></script>

@endif

