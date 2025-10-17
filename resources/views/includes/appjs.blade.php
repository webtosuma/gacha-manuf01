@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/20250926143513app.js') }}" defer></script> --}}


@else

    <script src="{{ asset('js/20251016213906app.js') }}" defer></script>

@endif

