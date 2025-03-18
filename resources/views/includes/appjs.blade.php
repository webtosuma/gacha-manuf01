@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>


@else

    <script src="{{ asset('js/20250318130356app.js') }}" defer></script>

@endif

