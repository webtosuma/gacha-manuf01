@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>


@else

    <script src="{{ asset('js/20250410173118app.js') }}" defer></script>

@endif

