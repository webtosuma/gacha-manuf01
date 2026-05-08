@if( !config('store.admin') )

    @include('admin.layouts.apps.index')

@else

    @include('store_admin.layouts.app'  )

@endif
