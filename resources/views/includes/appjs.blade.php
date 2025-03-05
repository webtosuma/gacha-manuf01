@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>


@else

    <script src="{{ asset('js/20250305144849app.js') }}" defer></script>

@endif

