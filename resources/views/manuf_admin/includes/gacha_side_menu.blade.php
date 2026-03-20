@php
    $active_class = "text-info fw-bold border-info border-2 bg- active_menu disabled";


    /* メインメニュー */
    $menu_array = [
        [
            'route' => route('admin.gacha'),
            'key'   => 'gacha',
            'label' => 'タイトル詳細',
        ],
        [
            'route' => '',
            'key'   => '',
            'label' => '商品',
        ],
        [
            'route' => '',
            'key'   => '',
            'label' => '筺体',
        ],
        [
            'route' => '',
            'key'   => '',
            'label' => '演出動画',
        ],
        [
            'route' => '',
            'key'   => '',
            'label' => '販売・公開 期間',
        ],
        [
            'route' => '',
            'key'   => '',
            'label' => '履歴',
        ],
    ];



@endphp
<div class="d-flex flex-column justify-content-between py-3 px-2">
    <div class="border-bottom bg-" id="sideMenuAccordion">


        @foreach ($menu_array as $menu)
            @php
            $style_class = 'list-group-item fw-bold border-0 p-0 px-3 mb-2 w-100 '.( isset($active_key)&&$active_key==$menu['key'] ? $active_class :  '')
            @endphp

            <a href="{{ $menu['route'] }}"
            class="{{$style_class}}"
            style="border-radius: 2rem  2rem;">
                <div class="d-flex align-items-center gap-3">

                    {{-- <i class="{{$icon_class}}"></i> --}}

                    <span>{{ $menu['label'] }}</span>


                </div>
            </a>
        @endforeach

    </div>



</div>

<!--ロゴ-->
<div class="list-group-item border-0 p-2 px-3 w-100 text-start">
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

