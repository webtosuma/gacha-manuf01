<div class="border- bottom mb-3" id="sideMenuAccordion">

    @php
    $style_class = 'list-group-item border-0 p-2 px-3 w-100 fw-bold ';
    $style_class = 'btn btn-info text-white w-100 fw-bold mb-1 ';
    @endphp


    @guest
    <!-- ログイン前 -->

        <!--ログイン/会員登録-->
        <div class="">
            @php
            $menu = [
                'route' => route('login'),
                'icon'  => 'bi-person-fill',
                'label' => 'ログイン/会員登録',
            ];
            $icon_class = $menu['icon']." text-info bi fs-3";
            @endphp
            <a href="{{ $menu['route'] }}"
            class="btn btn-dark text-info  w-100 fw-bold mb-1"
            style="border-radius: 2rem  2rem;">
                <div class="d-flex align-items-center gap-2">

                    {{-- <div class="col-auto" style="width: 2.6rem;">
                        <ratio-image-component
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                        style_class="ratio ratio-1x1 rounded-pill border bg-white"
                        url="{{ asset( 'storage/site/image/user_no_image.png' ) }}"
                        ></ratio-image-component>
                    </div> --}}

                    <div class="d-flex align-items-center justify-content-center
                    bg-info rounded-pill"
                    style="width:2.6rem; height:2.6rem;">
                        <i class="{{$icon_class}} text-dark"></i>
                    </div>

                    <span>{{ $menu['label'] }}</span>

                </div>
            </a>
        </div>

    @else
    <!-- ログイン後 -->

        <!--マイメニュー-->
        <div class="">
            {{-- <span class="p-3">{{ Auth::user()->name }}さん</span> --}}
            @php
            $menu = [
                'route' => '',
                'icon'  => 'bi-person-fill',
                'label' => 'マイメニュー',
            ];
            $icon_class = $menu['icon']." text-info bi fs-3";
            @endphp

            <button  class="{{$style_class}}"
            data-bs-toggle="collapse" href="#collapseMyMenu"
            role="button" aria-expanded="false" aria-controls="collapseMyMenu"
            type="button"
            style="border-radius: 2rem  2rem;">
                <div class="d-flex align-items-center gap-2">

                    <div class="col-auto" style="width: 2.6rem;">
                        <ratio-image-component
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                        style_class="ratio ratio-1x1 rounded-pill border bg-white"
                        url="{{ Auth::user()->image_path }}"
                        ></ratio-image-component>
                    </div>

                    <div class="text-start">
                        <div>{{ Auth::user()->name }}さん</div>
                        <span class="fw-normal" style="font-size:.8rem;">{{ $menu['label'] }}</span>
                    </div>

                    <span class="ms-3 dropdown-toggle"></span>

                </div>
            </button>

            <div class="collapse  showww "
            id="collapseMyMenu">

                <ul class="list-unstyled m-0 gap-3 mx-3">


                    <!--アカウント-->
                    <li class="mb-2"><a
                    href="{{ route('settings.acount') }}"
                    class="list-group-item rounded-pill px-4 w-100 text-start
                    btn btn-info text-white
                    ">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle fs-4"></i>
                            {{ __('アカウント') }}
                        </div>
                    </a></li>


                    <!--発送住所-->
                    <li class="mb-2"><a
                    href="{{ route('settings.shipped_address') }}"
                    class="list-group-item rounded-pill px-4 w-100 text-start
                    btn btn-info text-white
                    ">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-pin-map fs-4"></i>
                            {{ __('発送住所') }}
                        </div>
                    </a></li>


                    <!--その他設定-->
                    <li class="mb-2"><a
                    href="{{ route('settings') }}"
                    class="list-group-item rounded-pill px-4 w-100 text-start
                    btn btn-info text-white
                    ">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-gear fs-4"></i>
                            {{ __('その他設定') }}
                        </div>
                    </a></li>


                    <!--ログアウト-->
                    <li class="mb-2">
                        <form action="{{ route('admin_auth.logout') }}" method="POST">
                            @csrf
                            <button  class="list-group-item rounded-pill px-4 w-100 text-start
                            btn btn-info text-white
                            " type="submit">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right fs-4"></i>
                                    {{ __('ログアウト') }}
                                </div>
                            </button>
                        </form>
                    </li>

                </ul>

            </div>


        </div>

    @endguest


    <!--キーワード検索-->
    <div class="">
        @php
        $menu = [
            'route' => '#',
            'icon'  => 'bi-search',
            'label' => 'キーワード検索',
        ];
        $icon_class = $menu['icon']." text-info bi fs-3";
        @endphp
        <a href="{{ $menu['route'] }}"
        data-bs-toggle="offcanvas" data-bs-target="#offcanvaSearch"
        aria-controls="offcanvaSearch"
        class="{{$style_class}}"
        style="border-radius: 2rem  2rem;">
            <div class="d-flex align-items-center gap-2">

                <div class="d-flex align-items-center justify-content-center
                bg-white rounded-pill"
                style="width:2.6rem; height:2.6rem;">
                    <i class="{{$icon_class}}"></i>
                </div>

                <span>{{ $menu['label'] }}</span>


            </div>
        </a>

    </div>


    <!--発送-->
    <div class="">
        @php
        $menu = [
            'route' => '#',
            'icon'  => 'bi-box-seam',
            'label' => '発送',
        ];
        $icon_class = $menu['icon']." text-info bi fs-3";
        @endphp
        <a href="{{ $menu['route'] }}"
        class="{{$style_class}}"
        style="border-radius: 2rem  2rem;">
            <div class="d-flex align-items-center gap-2">

                <div class="d-flex align-items-center justify-content-center
                bg-white rounded-pill"
                style="width:2.6rem; height:2.6rem;">
                    <i class="{{$icon_class}}"></i>
                </div>

                <span>{{ $menu['label'] }}</span>

            </div>
        </a>
    </div>


    <!--お知らせ-->
    <div>
        @php
        $menu = [
            'route' => route('infomation'),
            'icon'  => 'bi-bell',
            'label' => 'お知らせ',
        ];
        $icon_class = $menu['icon']." text-info bi fs-3";
        @endphp

        @php
        $infomations_count =
        \App\Http\Controllers\InfomationController::GetInfomationsQuery()
        ->whereNotIn( 'type', ['ec'] )
        ->limit(3)->count();
        @endphp
        @if( $infomations_count>0 )
            <a href="{{ $menu['route'] }}"
            class="{{$style_class}}"
            style="border-radius: 2rem  2rem;">
                <div class="d-flex align-items-center gap-2">

                    <div class="d-flex align-items-center justify-content-center
                    bg-white rounded-pill"
                    style="width:2.6rem; height:2.6rem;">
                        <i class="{{$icon_class}}"></i>
                    </div>

                    <span>{{ $menu['label'] }}</span>

                </div>
            </a>
        @endif
    </div>


</div>



<div class="border- bottom mb-3">

    <!-- フッターメニュー -->
    @include('manuf.includes.footer_menu')


    {{-- <form action="{{ route('admin_auth.logout') }}" method="POST">
        @csrf
        <button  class="list-group-item border-0 p-2 px-3 w-100 text-start" type="submit">
            <i class="bi bi-box-arrow-right fs-4 me-3"></i>
            {{ __('ログアウト') }}
        </button>
    </form> --}}

</div>



