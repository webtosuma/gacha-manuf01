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
        // [
        //     'route' => route('admin.sponsor_ad'),
        //     'key'   => 'sponsor_ad',
        //     'icon'  => 'bi-badge-ad',
        //     'label' => '広告管理',
        // ],
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
            'key'   => 'point_sail',
            'label' => '販売ポイント',
        ],
        // [
        //     'route' => route('admin.ticket_store'),
        //     'key'   => 'ticket_store',
        //     'label' => 'チケット用商品',
        // ],
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


    # サブスクの追加
    if( env('SUBSCRIPTION') )
    {
        $ragistation_array[] = [
            'route' => route('admin.subscription'),
            'key'   => 'subscription',
            'label' => 'サブスク管理',
        ];
    }
    # クーポンの追加
    if( config('app.coupon') )
    {
        $ragistation_array[] = [
            'route' => route('admin.coupon'),
            'key'   => 'coupon',
            'label' => 'クーポン管理',
        ];
    }
    # 買取表の追加
    if( config('app.purchase') )
    {
        $ragistation_array[] = [
            'route' => route('admin.purchase'),
            'key'   => 'purchase',
            'label' => '買取表管理',
        ];
    }
    # アンケート
    if( config('app.survey') )
    {
        $ragistation_array[] = [
            'route' => route('admin.survey'),
            'key'   => 'survey',
            'label' => 'アンケート登録',
        ];
    }


    # 操作履歴
    if( \App\Http\Controllers\AdminLogController::logStartupSetting() && Auth::user()->admin->master ){
        $ragistation_array[] = [
            'route' => route('admin.log'),
            'key'   => 'log',
            'label' => '操作履歴',
        ];
    }

    # アクセスログ
    if(
        \App\Http\Controllers\AdminAccessLogController::logStartupSetting()
        && in_array( Auth::user()->email, config('app.fobees_emails'))//システム運営者のみ
        && Auth::user()->admin->master//管理権限者のみ
    ){
        $ragistation_array[] = [
            'route' => route('admin.access_log'),
            'key'   => 'access_log',
            'label' => 'アクセスログ',
        ];
    }


    /* レポートメニュー */
    $report_array = [
        // [
        //     'route' => route('admin.point_history'),
        //     'key'   => 'point_history',
        //     'label' => 'ポイント売上',
        // ],
        [
            'route' => route('admin.point_sales_report'),
            'key'   => 'point_sales_report',
            'label' => 'ポイント売上',
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

    @if( config('app.purchase') )
        <!--買取表-->
        <a href="{{ route('purchase') }}" target="_blank"
        class="list-group-item border-0 p-2 px-3 w-100 text-start" style="border-radius: 2rem  2rem;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-card-checklist fs-4"></i>
                <span>{{ '買取表' }}</span>
            </div>
        </a>
    @endif

    @if( config('app.event_gacha') )
        <!--イベント-->
        <a href="{{ route('event.gacha') }}" target="_blank"
        class="list-group-item border-0 p-2 px-3 w-100 text-start" style="border-radius: 2rem  2rem;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-balloon fs-4"></i>
                <span>{{ 'イベントガチャ' }}</span>
            </div>
        </a>
    @endif


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


</div>


