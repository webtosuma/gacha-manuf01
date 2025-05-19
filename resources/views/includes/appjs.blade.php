@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>


@else

    <script src="{{ asset('js/20250516173037app.js') }}" defer></script>

@endif

