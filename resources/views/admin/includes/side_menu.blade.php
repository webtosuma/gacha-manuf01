@php
$active_class = "text-primary fw-bold border-bottom border-start border-top border-primary border-2 bg-white active_menu disabled";

$menu_array = [
    [
        'route' => route('admin.prize'),
        'key'   => 'prize',
        'icon'  => 'bi-gift',
        'label' => '商品管理',
    ],
    [
        'route' => route('admin.gacha'),
        'key'   => 'gacha',
        'icon'  => 'bi-globe',
        'label' => 'ガチャ管理',
    ],
    [
        'route' => route('admin.point'),
        'key'   => 'point',
        'icon'  => 'bi-coin',
        'label' => 'ポイント管理',
    ],
    [
        'route' => route('admin.point_history'),
        'key'   => 'point_history',
        'icon'  => 'bi-graph-up',
        'label' => 'ポイント売上',
    ],
    [
        'route' => route('admin.shipped'),
        'key'   => 'shipped',
        'icon'  => 'bi-cart4',
        'label' => '発送受付',
    ],
    [
        'route' => route('admin.user'),
        'key'   => 'user',
        'icon'  => 'bi-people',
        'label' => '登録ユーザー',
    ],
    [
        'route' => '',
        'key'   => '',
        'icon'  => 'bi-person-circle',
        'label' => 'サイト管理者',
    ],
];
@endphp
<div class="d-flex flex-column justify-content-between py-3">
    <div class="border-bottom bg-body" id="sideMenuAccordion">
        @foreach ($menu_array as $menu)
            @php
            $icon_class = $menu['icon']." text-primary bi fs-4 me-3";
            $style_class = 'list-group-item border-0 p-2 ps-3 pe-5 w-100 '.( isset($active_key)&&$active_key==$menu['key'] ? $active_class :  '')
            @endphp

            <a href="{{ $menu['route'] }}"
            class="{{$style_class}}"
            style="border-radius: 2rem 0 0 2rem;"
            ><i class="{{$icon_class}}"></i>{{ $menu['label'] }}</a>
        @endforeach


        <form action="{{ route('admin_auth.logout') }}" method="POST">
            @csrf
            <button  class="list-group-item border-0 p-2 ps-3 pe-5 w-100 text-start" type="submit">
                <i class="bi bi-box-arrow-right fs-4 me-3"></i>
                {{ __('ログアウト') }}
            </button>
        </form>
    </div>
</div>


