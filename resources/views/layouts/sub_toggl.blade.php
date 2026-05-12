@if( config('app.layout_app')=='store' )

    {{-- Store　レイアウト --}}
    @include(config('app.layout_app').'.layouts.sub'  )


@elseif( config('manuf.app') )

    {{-- Manuf　レイアウト --}}
    @include('manuf.layouts.sub'  )


@else

    {{-- デフォルト　レイアウト --}}
    @include('layouts.subs.index')


@endif
