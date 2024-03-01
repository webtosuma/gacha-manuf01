{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','マイページ')


@section('content')
<!--breadcrumb-->
<div class="container mt-">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">マイページ</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3 class="d-none d-md-block ">マイページ</h3>

    <div class="mx-auto mt-4 bg-white" style="max-width:400px;">




        <div class="offcanvas-body px-0 pt-0">

            <section class="bg-dark bg-gradient text-white p-3">
                <!-- プロフィール -->
                <div class="row align-items-center mb-3">
                    <div class="col">
                        <a href="{{ route('settings.acount') }}" class="d-block text-white">
                            <div class="row align-items-center g-2">


                                <div class="col-auto" style="width: 2.4rem;">
                                    <ratio-image-component
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                                    style_class="ratio ratio-1x1 rounded-pill border bg-light"
                                    url="{{ Auth::user()->image_path }}"
                                    ></ratio-image-component>
                                </div>

                                <div class="col" style="font-size:16px;">
                                    <div class="">{{ Auth::user()->name }}さん</div>
                                    @if( Auth::user()->twitter_id )
                                        <div class="form-text">
                                            {{-- X(旧twitter)ID： --}}
                                            <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                                            alt="xロゴ" class="d-inline-block" style="height:1rem;">


                                            {{ Auth::user()->twitter_id }}
                                        </div>
                                    @endif
                                </div>


                            </div>
                        </a>
                    </div>


                    <div class="col-auto" style="width: 6rem;">
                        <div class="collapse multi-collapse show" id="collapseMypageOpen">
                            <button class="btn btn-link text-decoration-none text-end w-100 p-0"
                            type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false"
                            aria-controls="collapseMypage collapseMypageClose"
                            style="font-size:11px;">閉じる<i class="bi bi-chevron-up"></i></button>
                        </div>
                        <div class="collapse multi-collapse" id="collapseMypageClose">
                            <button class="btn btn-link text-decoration-none text-end w-100 p-0"
                            type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false"
                            aria-controls="collapseMypage collapseMypageOpen"
                            style="font-size:11px;">開く<i class="bi bi-chevron-down"></i></button>
                        </div>
                    </div>
                </div>


                <div class="collapse multi-collapse show" id="collapseMypage" >
                    <!-- 会員ランク -->
                    @if( Auth::user()->now_rank )
                        @php $now_rank = Auth::user()->now_rank; @endphp

                        <div class="d-flex justify-content-between gap-3">
                            <div class="col-6">
                                <div style="font-size:14px;" class="mb-2">会員ランク：</div>

                                <ratio-image-component
                                style_class="ratio ratio-16x9 rounded-3 overflow-hidden
                                position-relative shiny"
                                url="{{ $now_rank->image_path }}"
                                ></ratio-image-component>
                            </div>
                            <div class="col">

                                <h6 class="fw-bold mb-2">{{$now_rank->label}}</h6>


                                <div class="progress rounded-0 mb-" style="height: 1.6rem; transform: skew(-15deg);">
                                    <div class="progress-bar bg-gradient bg-danger" role="progressbar"
                                    style="width: {{$now_rank->meter_warning}}%" aria-valuenow="{{$now_rank->meter_warning}}"
                                    aria-valuemin="0" aria-valuemax="100"></div>

                                    <div class="progress-bar bg-gradient bg-primary" role="progressbar"
                                    style="width: {{$now_rank->meter_success}}%" aria-valuenow="{{$now_rank->meter_success}}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-end" style="font-size:14px;">pt消費数</div>
                                {{-- <div class="text-end" style="font-size:14px;"
                                >{{ number_format($now_rank->total_play_ptcount) }} / {{ number_format($now_rank->next_rankup_ptcount) }}</div> --}}
                                <div class="text- mt-2" style="font-size:11px;">『{{$now_rank->next_rank->label}}』まであと、</div>
                                <div class="text-end" style="font-size:14px;"
                                >{{ number_format($now_rank->next_rankup_ptcount-$now_rank->total_play_ptcount) }}pt</div>


                                <a href="#" class="my-2" style="font-size:11px;"
                                ><i class="bi bi-question-circle me-2"></i>会員ランクについて</a>
                            </div>
                        </div>
                    @endif


                    <!-- 所持ポイント -->
                    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-2">
                        <div class="col-auto pe-2">
                            <div class="rounded-circle border border-white fw-bold bg-gradient text-white
                            d-flex align-items-center justify-content-center mx-auto
                            " style=" width:1rem; height:1rem; margin:.4rem 0; font-size:11px;">P</div>
                        </div>
                        <div class="col">
                            <div  style="font-size:14px;">所持ポイント：</div>
                            <div class="">
                                <span class="fs-5 fw-bold">
                                    <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                                </span>
                                <span>pt</span>
                            </div>
                            {{-- <a href="#" style="font-size:11px;" target="_blank"
                            ><i class="bi bi-question-circle me-2"></i>ポイントについて</a> --}}
                        </div>
                        <div class="col-auto">
                            {{-- <a href="{{ route('point_sail') }}"
                            class="d-block btn py-1  btn-warning text-white rounded-pill shadow w-100">
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="">
                                        <div class="rounded-circle border border-white fw-bold bg-gradient text-white
                                        d-flex align-items-center justify-content-center mx-auto
                                        " style=" width:1rem; height:1rem; margin:.4rem 0; font-size:11px;">P</div>
                                    </div>

                                    <div class="">ポイント購入</div>
                                </div>
                            </a> --}}

                            <a href="{{ route('point_sail') }}" class="btn btn- btn-warning text-white rounded-pill shadow">ポイント購入</a>
                        </div>
                    </div>


                    <!-- 所持チケット -->
                    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-2">
                        <div class="col-auto fs-5 pe-2">
                            <img src="{{asset('storage/site/image/ticket/white.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:1.4rem; height:1.4rem; margin:.2rem 0;">
                        </div>
                        <div class="col">
                            <div  style="font-size:14px;">所持チケット：</div>
                            <div class="">
                                <span class="fs-5 fw-bold">
                                    <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                                </span>
                                <span>枚</span>
                            </div>
                            <a href="#" style="font-size:11px;" target="_blank"
                            ><i class="bi bi-question-circle me-2"></i>チケットについて</a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('ticket_store') }}"
                            class="d-block btn py-1 btn-success text-white rounded-pill shadow w-100">
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="bi bi-gift fs-5 "></i>

                                    <div class="">商品と交換</div>
                                </div>
                            </a>

                            {{-- <a href="{{ route('ticket_store') }}"
                            class="btn btn-sm btn-success text-white rounded-pill shadow px-3">商品と交換</a> --}}
                        </div>
                    </div>


                    <!-- 取得した商品 -->
                    <div class="">
                        <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-2">
                            <div class="col-auto fs-5 pe-2">
                                <i class="bi bi-files"></i>
                            </div>
                            <div class="col">
                                <div  style="font-size:14px;">取得した商品：</div>
                                <div class="">
                                    <span class="fs-5 fw-bold">
                                        <number-comma-component number="{{ Auth::user()->u_prizes_count }}"></number-comma-component>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('user_prize') }}" style="font-size:11px;"
                                >一覧を見る<i class="bi bi-chevron-right"></i></a>
                            </div>
                        </div>
                        <div class="row g-2 mt-2 px- mx-1 border-bottom pb-2">
                            @foreach (Auth::user()->best_u_prizes as $u_prize)
                                <div class="col-3 text-center">
                                    <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded-3"
                                    url="{{$u_prize->prize->image_path}}"
                                    ></ratio-image-component>

                                    <div class="mt-1 w-100 border rounded-pill d-inline-block" style="font-size:11px;">
                                        {{number_format($u_prize->prize->point).'pt'}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>



            <!-- メニュー -->
            <div class="bg-white border- p-3">
                <h6 class="fw-bole pb-0">メニュー</h6>
                <div class="row g-2">
                    <div class="col-3">
                        <a href="{{ route('ticket_sail') }}" class="btn rounded-3 text-primary shadow-sm fw-bold p-2 w-100" style="font-size:8px; line-height:18px;">
                            <img src="{{asset('storage/site/image/ticket/darks.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:1.4rem; height:1.4rem; margin:.2rem 0;">

                            <div class="text-secondary">チケット購入</div>
                        </a>
                    </div>
                    {{-- <div class="col-3">
                        <a href="{{ route('ticket_store') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" >
                            <i class="bi bi-gift fs-5 "></i>
                            <div class="text-secondary" style="font-size:8px; line-height:18px;">チケット交換</div>
                        </a>
                    </div> --}}
                    <div class="col-3">
                        <a href="{{ route('infomation') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                            <i class="bi bi-megaphone fs-5 "></i>
                            <div class="text-secondary">お知らせ</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('settings') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                            <i class="bi bi-gear-fill fs-5 "></i>
                            <div class="text-secondary">設定</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;" type="submit">
                                <i class="bi bi-box-arrow-right fs-5 "></i>
                                <div class="text-secondary">ログアウト</div>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- 履歴 -->
            <div class="bg-white border- p-3">
                <h6 class="fw-bole pb-0">履歴</h6>
                <div class="row g-2">
                    <div class="col-3">
                        <a href="{{ route('gacha_history') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                            <i class="bi bi-stars fs-5 "></i>
                            <div class="text-secondary">ガチャ</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('point_history') }}" class="btn rounded-3 text-primary shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                            <div class="rounded-circle border border-dark fw-bold bg-gradient text-dark
                            d-flex align-items-center justify-content-center mx-auto
                            " style=" width:1rem; height:1rem; margin:.4rem 0; font-size:11px;">P</div>
                            <div class="text-secondary">ポイント</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{route('ticket_history')}}" class="btn rounded-3 text-primary shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                            <img src="{{asset('storage/site/image/ticket/dark.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:1.4rem; height:1.4rem; margin:.2rem 0;">

                            <div class="text-secondary">チケット</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('user_rank_history') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                            <i class="bi bi-person-square fs-5 "></i>
                            <div class="text-secondary">会員ランク</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('shipped') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                            <i class="bi bi-box-seam fs-5 "></i>
                            <div class="text-secondary">
                                <span>発送</span>

                                @php $unread_count = Auth::user()->unread_send_shippeds_count; @endphp
                                @if ( $unread_count )
                                    <!--お問い合わせ　未対応-->
                                    <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                                @endif
                            </div>
                        </a>
                    </div>

                </div>
            </div>


            <!-- SNS -->
            <div class="bg-white border- p-3">
                <h6 class="fw-bole pb-0">公式SNS</h6>
                <div class="row g-2">
                    <div class="col-3">
                        <a href="https://twitter.com/CardFesta7627" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:8px;" target="_blank">
                            <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                            alt="xロゴ" class="d-block mx-auto"  style=" width:1rem; height:1rem; margin:.4rem 0;">
                            {{-- <div class="text-secondary">タイムライン</div> --}}
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="https://note.com/cardfesta" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:8px;" target="_blank">
                            <img src="{{asset('storage/site/image/note-logo/main/icon.png')}}"
                            alt="noteロゴ" class="d-block mx-auto"  style=" width:1.8rem; height:1.8rem; margin:.0rem 0;">
                            {{-- <div class="text-secondary">ブログ</div> --}}
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="https://www.instagram.com/cardfesta/" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:8px;" target="_blank">
                            <i class="bi bi-instagram fs-5 "></i>
                            {{-- <div class="text-secondary">インスタグラム</div> --}}
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="https://www.tiktok.com/@cardfesta" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:8px;" target="_blank">
                            <i class="bi bi-tiktok fs-5 "></i>
                            {{-- <div class="text-secondary">TikTok</div> --}}
                        </a>
                    </div>
                </div>
            </div>

            <!-- PWAインストールボタン -->
            <div class="px-3">
                <pwa-install-btn></pwa-install-btn>
            </div>


            <div class="list-group list-group-flush">

                <!--お友達紹介キャンペーン-->
                @php
                $canpaing_introductory_active = \App\Http\Controllers\CanpaingIntroductoryController::active();
                @endphp
                @if( $canpaing_introductory_active )
                    @php
                    # キャンペーン画像
                    $canpaing = new \App\Http\Controllers\CanpaingIntroductoryController;
                    $image_path = $canpaing::imagePath();
                    @endphp


                    <div class="list-group-item p-3 p-2">
                        <div class="row g-2">
                            <div class="col">
                                <a href="{{route('canpaing.introductory')}}" class="d-block rounded-4 overflow-hidden">
                                    <ratio-image-component
                                    style_class="ratio ratio-4x3"
                                    url="{{ $image_path }}"
                                    ></ratio-image-component>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="list-group-item border-0 py-3">

                    <div class="fw-bold text-center mb-2">{{ config('app.name') }}をシェアする</div>
                    @include('includes.sns_btn')

                </div>
                <!-- フッターメニュー -->
                <div class="list-group-item py-3 border-0 d-flex flex-column gap-3">


                    @include('includes.footer_menu')


                </div>

            </div>
        </div>



    </div>

</div>
@endsection
