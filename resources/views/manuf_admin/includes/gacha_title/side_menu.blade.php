@php
    $active_class = "text-info fw-bold border-info border-2 bg- active_menu disabled";


    /* メインメニュー */
    $menu_array = [
        [
            'route' => route('admin.gacha_title.show',$gacha_title),
            'key'   => 'gacha_title.show',
            'label' => '基本情報',
        ],
        [
            'route' => route('admin.gacha_title.title_prize',$gacha_title),
            'key'   => 'gacha_title.title_prize',
            'label' => 'タイトル商品',
        ],
        [
            'route' => route('admin.gacha_title.machine',$gacha_title),
            'key'   => 'gacha_title.machine',
            'label' => '筺体',
        ],
        [
            'route' => route('admin.gacha_title.movie.edit',$gacha_title),
            'key'   => 'admin.gacha_title.movie',
            'label' => '演出動画',
        ],
        [
            'route' => route('admin.gacha_title.published.edit',$gacha_title),
            'key'   => 'admin.gacha_title.published',
            'label' => '販売・公開 期間',
        ],
        [
            'route' => route('admin.gacha_title.history',$gacha_title),
            'key'   => 'admin.gacha_title.history',
            'label' => '履歴',
        ],
    ];


    $machine_menu_array = [
        [
            'route' => route('admin.gacha_title.machine',$gacha_title),
            'key'   => 'gacha_title.machine',
            'label' => '基本情報',
        ],
        [
            'route' => route('admin.gacha_title.machine',$gacha_title),
            'key'   => 'gacha_title.machine',
            'label' => '口数',
        ],
        [
            'route' => route('admin.gacha_title.machine',$gacha_title),
            'key'   => 'gacha_title.machine',
            'label' => '履歴',
        ],
        [
            'route' => route('admin.gacha_title.machine',$gacha_title),
            'key'   => 'gacha_title.machine',
            'label' => '排出履歴',
        ],

    ];

@endphp
<div class="d-flex flex-column px-2">
    <div class="border-bottom bg-" id="sideMenuAccordion">


        @foreach ($menu_array as $menu)
            @php
            $style_class = 'btn btn-light fw-bold border-0 p-0 px-3 mb-2 d-block '.( isset($active_key)&&$active_key==$menu['key'] ? $active_class :  '')
            @endphp

            <a href="{{ $menu['route'] }}"
            class="{{$style_class}}"
            style="border-radius: 2rem  2rem;">
                <div class="d-flex align-items-center gap-3">


                    <span>{{ $menu['label'] }}</span>


                    @switch( $menu['key'] )
                        @case('gacha_title.title_prize')
                            <!--タイトル商品-->
                            @if( $gacha_title->title_prizes->count()<1 )
                                <i class="bi bi-exclamation-circle-fill text-danger"></i>
                            @endif
                            @break

                        @case('gacha_title.machine')
                            <!--筺体-->
                            @if( $gacha_title->machines->count()<1 )
                                <i class="bi bi-exclamation-circle-fill text-danger"></i>
                            @endif
                            @break

                        @case('admin.gacha_title.movie')
                            <!--演出動画-->
                            @if( $gacha_title->movies->count()<1 )
                                <i class="bi bi-exclamation-circle-fill text-danger"></i>
                            @endif
                            @break

                        @case('admin.gacha_title.published')
                            <!--販売・公開 期間-->
                            @if( ! $gacha_title->is_published )
                                <i class="bi bi-exclamation-circle-fill text-danger"></i>
                            @endif
                            @break

                        @default
                    @endswitch

                </div>
            </a>


            <!--machine_menu-->
            @if($menu['key']==='gacha_title.machine' && isset($machine) )
                <div class="d-flex flex-column px-3 mb-4">

                    @foreach ($machine_menu_array as $menu)
                        <a href="{{ $menu['route'] }}"
                        class="btn btn-sm btn-light rounded-pill border-0 p-0 px-3 mb-2 d-block">
                            <div class="d-flex align-items-center gap-3">


                                <span>{{ $menu['label'] }}</span>


                            </div>
                        </a>
                    @endforeach

                </div>
            @endif


        @endforeach

    </div>



</div>

<!--ロゴ-->
{{-- <div class="list-group-item border-0 p-2 px-3 w-100 text-start">
    <a class="navbar-brand  text-primary" href="{{ route('admin.home') }}">
        <h1 class="fs-6 m-0 text-center d-flex flex- align-items-center gap-2">
            <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" class="d-brock" style="width: 8rem;">
            <br>
        </h1>
        <span class="fw-bold text-secondary" style="font-size:.8rem;">管理者ダッシュボード</span>
    </a>
</div>
<div class="list-group-item border-0 p-0 px-3 w-100 text-start">

    <!--ログイン管理者-->
    <span>{{ Auth::user()->name }}さん</span>

</div>
 --}}
