@if( !config('app.layout_app') )

    @include('admin.layouts.apps.index')

@else

    @include(config('app.layout_app').'_admin.layouts.app'  )

@endif
