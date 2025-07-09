@if( !config('store.admin') )

    @include('admin.layouts.apps.index')

@else

    @include(config('app.layout_app').'_admin.layouts.app'  )

@endif
