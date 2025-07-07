@if( config('app.layout_app')!='store' )

    @include('layouts.subs.index')

@else

    @include(config('app.layout_app').'.layouts.sub'  )

@endif
