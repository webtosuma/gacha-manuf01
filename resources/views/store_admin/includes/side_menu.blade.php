@php
    $active_class = "text-primary fw-bold border-end border-bottom border-start border-top border-primary border-2 bg-white active_menu disabled";


    /* メインメニュー */
    $menu_array = [
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
            'route' => route('admin.infomation'),
            'key'   => 'infomation',
            'label' => 'お知らせ',
        ],
        [
            'route' => route('admin.back_ground'),
            'key'   => 'back_ground',
            'label' => 'サイト背景',
        ],
        [
            'route' => route('admin.maintenance'),
            'key'   => 'maintenance',
            'label' => 'メンテナンス設定',
        ],

    ];

    if( \App\Http\Controllers\AdminLogController::logStartupSetting() && Auth::user()->admin->master ){
        $ragistation_array[] = [
            'route' => route('admin.log'),
            'key'   => 'log',
            'label' => '操作履歴',
        ];
    }


    /* レポートメニュー */
    $report_array = [

        [
            'route' => route('admin.point_history'),
            'key'   => 'point_history',
            'label' => 'ポイント売上',
        ],
        [
            'route' => route('admin.point_sales_report'),
            'key'   => 'point_sales_report',
            'label' => 'ポイント売上(改正版)',
        ],
        // [
        //     'route' => '#',
        //     'key'   => 'gacha_history',
        //     'label' => 'ガチャレポート(*製作中)',
        // ],
        // [
        //     'route' => '#',
        //     'key'   => 'shipped_history',
        //     'label' => '発送申請レポート(*製作中)',
        // ],
        [
            'route' => route('admin.store.sales_report'),
            'key'   => 'sales_report',
            'label' => 'EC売上',
        ],
    ];


    /* ガチャメニュー */
    $gachas_array = [
        [
            'route' => route('admin.prize'),
            'key'   => 'prize',
            'label' => '商品管理',
        ],
        [
            'route' => route('admin.movie'),
            'key'   => 'movie',
            'label' => '演出動画',
        ],
        [
            'route' => route('admin.point_sail'),
            'key'   => 'point_sail',
            'label' => '販売ポイント',
        ],
        // [
        //     'route' => route('admin.ticket_store'),
        //     'key'   => 'ticket_store',
        //     'label' => 'チケット用商品',
        // ],
        // [
        //     'route' => route('admin.subscription'),
        //     'key'   => 'subscription',
        //     'label' => 'サブスク管理',
        // ],
        [
            'route' => route('admin.gacha'),
            'key'   => 'gacha',
            'label' => 'ガチャ管理',
        ],
        [
            'route' => route('admin.shipped'),
            'key'   => 'shipped',
            'label' => '発送受付',
        ],

    ];


    /* ECメニュー */
    $stores_array = [
        [
            'route' => route('admin.store_item'),
            'key'   => 'store_item',
            'label' => 'EC商品',
        ],
        [
            'route' => route('admin.store.shipped'),
            'key'   => 'store_shipped',
            'label' => 'EC発送受付',
        ],
    ];


    // サブスクの追加
    if( env('SUBSCRIPTION') )
    {
        $gachas_array[] = [
            'route' => route('admin.subscription'),
            'key'   => 'subscription',
            'label' => 'サブスクポイント管理',
        ];
    }


@endphp
<div class="d-flex flex-column justify-content-between py-3 px-2">
    <!--ロゴ-->
    <div class="list-group-item border-0 p-2 px-3 w-100 text-start">
        <a class="navbar-brand  text-primary" href="{{ route('admin.home') }}">
            <h1 class="fs-6 m-0 text-center d-flex flex- align-items-center gap-2">
                <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="width: 8rem;">
                <br>
            </h1>
            <span class="fw-bold text-dark" style="font-size:.8rem;">STORE ダッシュボード</span>
        </a>
    </div>
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



        <!-- ガチャ -->
        @if( ! config('store.no_gacha') )
            @php $waiting_shippeds_count = Auth()->user()->admin->waiting_shippeds->count(); @endphp

            <button  class="list-group-item border-0 p-2 px-3 w-100 text-start dropdown-toggle position-relative"
            data-bs-toggle="collapse" href="#collapseAdminGachaMenu" role="button" aria-expanded="false"
            aria-controls="collapseAdminGachaMenu"
            type="button" >
                    <i class="bi bi-gift text-primary fs-4 me-3"></i>

                    <span>{{ __('ガチャ') }}</span>

                    @if ( $waiting_shippeds_count )
                        <!--商品　未発送数-->
                        <span class="position-absolute top-50 end-0 translate-middle-y
                        badge rounded-pill bg-warning fs-5"><i class="bi bi-box-seam"></i></span>
                    @endif
            </button>
            <div class="collapse ps-3 {{ isset($active_gacha_menu)&&$active_gacha_menu==true ? 'show' :  ''}}"
            id="collapseAdminGachaMenu">

                @foreach ($gachas_array as $menu)
                <a href="{{ $menu['route'] }}"
                class="list-group-item border-0 p-2 px-3 w-100 text-start
                {{ isset($active_key)&&$active_key==$menu['key'] ? $active_class :  ''}}"
                style="border-radius: 2rem  2rem;">
                    <div class="d-flex align-items-center gap-3">
                        <span>{{ $menu['label'] }}</span>

                        @if ( $menu['key']=='shipped' && $waiting_shippeds_count )
                            <!--商品　未発送数-->
                            <span class="badge rounded-pill bg-warning">{{$waiting_shippeds_count}}</span>
                        @endif
                    </div>
                </a>
                @endforeach

            </div>
        @endif

        <!-- EC販売 -->
        @php $waiting_store_shippeds_count = \App\Models\StoreHistory::forAdminWaitingCount(); @endphp
        <button  class="list-group-item border-0 p-2 px-3 w-100 text-start dropdown-toggle position-relative"
        data-bs-toggle="collapse" href="#collapseAdminStoreMenu" role="button" aria-expanded="false"
        aria-controls="collapseAdminStoreMenu"
        type="button" >
            <i class="bi bi-cart4 text-primary fs-4 me-3"></i>

            {{ __('EC販売') }}


            @if ( $waiting_store_shippeds_count )
                <!--商品　未発送数-->
                <span class="position-absolute top-50 end-0 translate-middle-y
                badge rounded-pill bg-warning fs-5"><i class="bi bi-box-seam"></i></span>
            @endif
        </button>
        <div class="collapse ps-3 {{ isset($active_store_menu)&&$active_store_menu==true ? 'show' :  ''}}"
        id="collapseAdminStoreMenu">

            @foreach ($stores_array as $menu)
            <a href="{{ $menu['route'] }}"
            class="list-group-item border-0 p-2 px-3 w-100 text-start
            {{ isset($active_key)&&$active_key==$menu['key'] ? $active_class :  ''}}"
            style="border-radius: 2rem  2rem;">
                <div class="d-flex align-items-center gap-3">

                    <span>{{ $menu['label'] }}</span>

                    @if ( $menu['key']=='store_shipped' && $waiting_store_shippeds_count )
                        <!--商品　未発送数-->
                        <span class="badge rounded-pill bg-warning">{{$waiting_store_shippeds_count}}</span>
                    @endif

                </div>
            </a>
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


    <a href="{{ route('home') }}" target="_blank"
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

        <!--SNS Links-->
        @include('includes.sns_links')

    </div>

    <div class="list-group-item border-0 p-2 px-3 w-100 text-start">

        <!--ログイン管理者-->
        <span>{{ Auth::user()->name }}さん</span>

    </div>

</div>


