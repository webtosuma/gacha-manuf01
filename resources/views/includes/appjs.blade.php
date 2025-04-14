@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>


@else

    <script src="{{ asset('js/20250414161901app.js') }}" defer></script>

@endif

