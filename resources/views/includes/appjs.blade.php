@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/20250522100610app.js') }}" defer></script> --}}


@else

    <script src="{{ asset('js/20250613091356app.js') }}" defer></script>

@endif

