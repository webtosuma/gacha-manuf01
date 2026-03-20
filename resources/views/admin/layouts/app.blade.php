@if( config('store.admin') )

    {{-- EC管理者　レイアウト --}}
    @include('store_admin.layouts.app'  )


@elseif( config('manuf.app') )

    {{-- Manuf管理者　レイアウト --}}
    @include('manuf_admin.layouts.app'  )


@else

    {{-- 管理者　レイアウト --}}
    @include('admin.layouts.apps.index')


@endif
