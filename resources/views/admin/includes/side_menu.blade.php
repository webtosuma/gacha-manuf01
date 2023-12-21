@php
$active_class = "text-primary fw-bold border-end border-bottom border-start border-top border-primary border-2 bg-white active_menu disabled";

$menu_array = [
    [
        'route' => route('admin.gacha'),
        'key'   => 'gacha',
        'icon'  => 'bi-gift',
        'label' => 'ガチャ管理',
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
        'route' => route('admin.contact'),
        'key'   => 'contact',
        'icon'  => 'bi-telephone',
        'label' => 'お問い合わせ',
    ],
    [
        'route' => route('admin.register'),
        'key'   => 'register',
        'icon'  => 'bi-person-circle',
        'label' => 'サイト管理者',
    ],
];
$submenu_array = [
    [
        'route' => route('admin.category'),
        'key'   => 'category',
        'label' => 'カテゴリー',
    ],
    [
        'route' => route('admin.prize'),
        'key'   => 'prize',
        'label' => '商品',
    ],
    [
        'route' => '',
        'key'   => 'movie',
        'label' => '演出動画(準備中)',
    ],
    [
        'route' => route('admin.point'),
        'key'   => 'point',
        'label' => '販売ポイント(準備中)',
    ],
    [
        'route' => route('admin.infomation'),
        'key'   => 'infomation',
        'label' => 'お知らせ',
    ],
];
@endphp
<div class="d-flex flex-column justify-content-between py-3 px-2">
    <div class="border-bottom bg-body" id="sideMenuAccordion">

        <button  class="list-group-item border-0 p-2 px-3 w-100 text-start dropdown-toggle"
        data-bs-toggle="collapse" href="#collapseAdminSideMenu" role="button" aria-expanded="false" aria-controls="collapseAdminSideMenu"
        type="button" >
            <i class="bi bi-globe text-primary fs-4 me-3"></i>
            {{ __('登録管理') }}
        </button>
        <div class="collapse ps-3 {{ isset($active_submenu)&&$active_submenu==true ? 'show' :  ''}}"
        id="collapseAdminSideMenu">

            @foreach ($submenu_array as $menu)
            <a href="{{ $menu['route'] }}"
            class="list-group-item border-0 p-2 px-3 w-100 text-start
            {{ isset($active_key)&&$active_key==$menu['key'] ? $active_class :  ''}}"
            style="border-radius: 2rem  2rem;"
            >{{ $menu['label'] }}</a>
            @endforeach

        </div>

        @foreach ($menu_array as $menu)
            @php
            $icon_class = $menu['icon']." text-primary bi fs-4";
            $style_class = 'list-group-item border-0 p-2 px-3 w-100 '.( isset($active_key)&&$active_key==$menu['key'] ? $active_class :  '')
            @endphp

            <a href="{{ $menu['route'] }}"
            class="{{$style_class}}"
            style="border-radius: 2rem  2rem;">
                <div class="d-flex align-items-center gap-3">
                    <i class="{{$icon_class}}"></i>
                    <span>{{ $menu['label'] }}</span>

                    @php $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count(); @endphp
                    @if ( $menu['key']=='shipped' && $waiting_shippeds_count )
                        <!--商品　未発送数-->
                        <span class="badge rounded-pill bg-warning">{{$waiting_shippeds_count}}</span>
                    @endif


                    @php $unresponsed_contacts_count = Auth()->user()->admin->unresponsed_contacts->count(); @endphp
                    @if ( $menu['key']=='contact' && $unresponsed_contacts_count )
                        <!--お問い合わせ　未対応-->
                        <span class="badge rounded-pill bg-warning">{{$unresponsed_contacts_count}}</span>
                    @endif

                </div>
            </a>
        @endforeach


        <form action="{{ route('admin_auth.logout') }}" method="POST">
            @csrf
            <button  class="list-group-item border-0 p-2 px-3 w-100 text-start" type="submit">
                <i class="bi bi-box-arrow-right fs-4 me-3"></i>
                {{ __('ログアウト') }}
            </button>
        </form>
    </div>
</div>


