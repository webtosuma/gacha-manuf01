@if ( env('APP_DEBUG') )

    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/20260512084114app.js') }}" defer></script> --}}


@else

    <script src="{{ asset('js/20260518174203app.js') }}" defer></script>

@endif

