@php
    $active_class = "text-primary fw-bold border-end border-bottom border-start border-top border-primary border-2 bg-white active_menu disabled";


    /* メインメニュー */
    $menu_array = [
        [
            'route' => route('admin.gacha'),
            'key'   => 'gacha',
            'icon'  => 'bi-gift',
            'label' => 'ガチャ管理',
        ],
        [
            'route' => route('admin.sponsor_ad'),
            'key'   => 'sponsor_ad',
            'icon'  => 'bi-badge-ad',
            'label' => '広告管理',
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


    /* 登録管理メニュー */
    $ragistation_array = [
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
            'route' => route('admin.movie'),
            'key'   => 'movie',
            'label' => '演出動画',
        ],
        [
            'route' => route('admin.point_sail'),
            'key'   => 'point',
            'label' => '販売ポイント',
        ],
        [
            'route' => route('admin.ticket_store'),
            'key'   => 'ticket_store',
            'label' => 'チケット用商品',
        ],
        [
            'route' => route('admin.infomation'),
            'key'   => 'infomation',
            'label' => 'お知らせ',
        ],
    ];


    /* レポートメニュー */
    $report_array = [
        [
            'route' => route('admin.point_history'),
            'key'   => 'point_history',
            'label' => 'ポイント売上',
        ],
        [
            'route' => '#',
            'key'   => 'gacha_history',
            'label' => 'ガチャレポート(*製作中)',
        ],
        [
            'route' => '#',
            'key'   => 'shipped_history',
            'label' => '発送申請レポート(*製作中)',
        ],
    ];

@endphp
<div class="d-flex flex-column justify-content-between py-3 px-2">
    <div class="border-bottom bg-" id="sideMenuAccordion">

        <!-- 登録管理 -->
        <button  class="list-group-item border-0 p-2 px-3 w-100 text-start dropdown-toggle"
        data-bs-toggle="collapse" href="#collapseAdminRagistationMenu" role="button" aria-expanded="false" aria-controls="collapseAdminRagistationMenu"
        type="button" >
            <i class="bi bi-globe text-primary fs-4 me-3"></i>
            {{ __('登録管理') }}
        </button>
        <div class="collapse ps-3 {{ isset($active_submenu)&&$active_submenu==true ? 'show' :  ''}}"
        id="collapseAdminRagistationMenu">

            @foreach ($ragistation_array as $menu)
            <a href="{{ $menu['route'] }}"
            class="list-group-item border-0 p-2 px-3 w-100 text-start
            {{ isset($active_key)&&$active_key==$menu['key'] ? $active_class :  ''}}"
            style="border-radius: 2rem  2rem;"
            >{{ $menu['label'] }}</a>
            @endforeach

        </div>


        <!-- レポート -->
        <button  class="list-group-item border-0 p-2 px-3 w-100 text-start dropdown-toggle"
        data-bs-toggle="collapse" href="#collapseAdminReportMenu" role="button" aria-expanded="false" aria-controls="collapseAdminReportMenu"
        type="button" >
            <i class="bi bi-graph-up text-primary fs-4 me-3"></i>
            {{ __('レポート') }}
        </button>
        <div class="collapse ps-3 {{ isset($active_report_menu)&&$active_report_menu==true ? 'show' :  ''}}"
        id="collapseAdminReportMenu">

            @foreach ($report_array as $menu)
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

    </div>


    <a href="{{ route('gacha_category') }}" target="_blank"
    class="list-group-item border-0 p-2 px-3 w-100 text-start" style="border-radius: 2rem  2rem;">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-house fs-4"></i>
            <span>{{ 'ユーザーサイト' }}</span>
        </div>
    </a>


    <form action="{{ route('admin_auth.logout') }}" method="POST">
        @csrf
        <button  class="list-group-item border-0 p-2 px-3 w-100 text-start" type="submit">
            <i class="bi bi-box-arrow-right fs-4 me-3"></i>
            {{ __('ログアウト') }}
        </button>
    </form>


    <div class="list-group-item border-0 p-2 px-3 w-100 text-start mb-5">
        <div class="my- d-flex">
            <a href="https://twitter.com/CardFesta7627" rel="nofollow" target="_blank">
                <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                alt="xロゴ" class="d-block p-2" style=" width:2rem; height:2rem;">
            </a>
            <a href="https://note.com/cardfesta" rel="nofollow" target="_blank">
                <img src="{{asset('storage/site/image/note-logo/main/icon.png')}}"
                alt="noteロゴ" class="d-block p-" style=" width:2rem; height:2rem;">
            </a>
            <a href="https://www.instagram.com/cardfesta/" rel="nofollow" target="_blank">
                <img src="{{asset('storage/site/image/instagram-logo/01/gradient.png')}}"
                alt="インスタグラムロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
            </a>
            <a href="https://www.tiktok.com/@cardfesta" rel="nofollow" target="_blank">
                <img src="{{asset('storage/site/image/tiktok-icons/black_circle.png')}}"
                alt="tiktokロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
            </a>
        </div>
    </div>


</div>


