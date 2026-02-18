
@php $rogo_height = 54; $rogo_border_height = $rogo_height + 8; @endphp
<header class="position-fixed w-100 pt-1" style="z-index:100;">

    @if ( config('app.debug') )
        {{-- <h6 class="text-danger text-center m-0 bg-light">TEST MODE</h6> --}}
    @endif
    @php $now = now()->format('Ymd-Hi'); @endphp
    {{-- @if( $now > '20240711-1000' && $now < '20250711-1130' )
        <div class="bg-danger text-center text-white">本日AM11:00より、メンテナンスを行います。</div>
    @endif --}}

    <div class="container py-2 px-0 position-relative">

        <!-- rogo -->
        <div class="position-absolute top-50 start-50 translate-middle bg-white rounded-pill
        d-flex align-items-center justify-content-center overflow-hidden"
        style="width:{{$rogo_height}}px; height:{{$rogo_height}}px; z-index:2;">
            <h1 class="d-flex align-items-center gap-3 m-0 ">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="w-100">
                </a>
            </h1>
        </div>
        <div class="position-absolute top-50 start-50 translate-middle bg-primary rounded-pill p-1
        d-flex align-items-center justify-content-center"
        style="width:{{$rogo_border_height}}px; height:{{$rogo_border_height}}px; z-index:1;"></div>



        <!-- point -->
        @auth
        <div class="position-absolute" style="bottom:-1.5rem; right:.5rem;">

            <a href="{{route('point_sail')}}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" title="ポイントを購入する"
            class="d-block text-decoration-none text-dark mx-2">

                <div class="position-relative">

                    <div class="position-absolute top-50 start-0 translate-middle">
                        @include('includes.point_icon')
                    </div>


                    <div class="rounded-pill bg-white text- fw-bold border
                    d-flex align-items-center justify-content-end px-3
                    " style="width:7.4rem; height:1.6rem;">

                        <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                        <span>pt</span>

                    </div>

                    <div class="position-absolute top-50 start-100 translate-middle">
                        <i class="bi bi-plus-circle-fill fs-5"></i>
                    </div>

                </div>

            </a>

        </div>
        @endauth





        <nav class="d-flex justify-content-between align-items-center p- border border-primary border-3 bg-primary bg-gradient
        rounded-pill ps-3 mx-1 shadow" style="background:rgb(255, 255, 255, .9);">

            {{-- <h1 class="d-flex align-items-center gap-3 m-0 ">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock" style="height:2.4rem;">
                </a>
            </h1> --}}
            <div class="row align-items-center" style="width:8rem;">
                <div class="col p-0">
                    <a href="{{ route('ticket_store') }}"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="チケット交換"
                    class="btn p-1 btn- rounded-0 w-100 borderr-start h-100" >
                        <img src="{{asset('storage/site/image/ticket/white.png')}}"
                        alt="チケット" class="d-block mx-auto"  style=" width:26px; height:26px;">

                        {{-- <div class="text-white mt-1" style="font-size:8px; line-height:8px;">商品と交換</div> --}}
                    </a>
                </div>
                <div class="col p-0">
                    <a href="{{ route('infomation') }}"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="お知らせ"
                    class="btn p-1 pb-2 btn- rounded-0 w-100 borderr-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" class="bi bi-megaphone" viewBox="0 0 16 16">
                            <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"/>
                        </svg>

                        {{-- <div class="text-white mt-1" style="font-size:8px; line-height:8px;">お知らせ</div> --}}
                    </a>
                </div>
                <div class="col p-0">
                    <a href="https://twitter.com/CardFesta7627"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="タイムライン"
                    class="btn p-1 btn- rounded-0 w-100 borderr-start borderr-end">

                        <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
                        alt="タイムライン" class="d-block mx-auto m-1" style=" width:20px; height:20px;">
                        {{-- <div class="text-white mt-1" style="font-size:8px; line-height:8px;">タイムライン</div> --}}
                    </a>
                </div>
            </div>


            <div class="d-flex align-items-center ">

                @guest

                    <!-- ログイン前 -->
                    <a class="btn btn-sm rounded-pill btn-outline-light fw-bold me-2"
                    href="{{ route('login') }}">{{ __('会員登録/ログイン') }}</a>

                @else
                    <!-- ログイン中 -->
                    <!-- ハンバーガーボタン -->
                    <button class="btn rounded-pill ms-2 p-0
                    d-flex align-items-center gap-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                    >
                        <div class="col-auto h-100" style="width: 3rem;">
                            @php $now_rank = Auth::user()->now_rank; @endphp
                            @if(isset($now_rank->image_path))
                                <ratio-image-component
                                style_class="ratio ratio-16x9 overflow-hidden
                                position-relative shiny"
                                url="{{ $now_rank->image_path }}"
                                ></ratio-image-component>
                            @endif
                        </div>
                        <div class="col-auto" style="width: 2rem;">
                            <ratio-image-component
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                            style_class="ratio ratio-1x1 rounded-pill bg-white"
                            url="{{ Auth::user()->image_path }}"
                            ></ratio-image-component>
                        </div>
                    </button>
                @endguest
            </div>

        </nav>

    </div>
</header>
