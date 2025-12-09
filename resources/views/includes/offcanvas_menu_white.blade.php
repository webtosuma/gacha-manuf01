<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasHumberge" aria-labelledby="offcanvasHumbergeLabel"
style="max-width:90vw; min-width:30vw;">

    <div class="offcanvas-header align-items-center border-bottom">
        <h5 id="offcanvasHumbergeLabel" class="m-0">
            <a href="{{ route('settings.acount') }}" class="d-block text-dark">
                <div class="row align-items-center g-2">


                    <div class="col-auto" style="width: 3rem;">
                        <ratio-image-component
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                        style_class="ratio ratio-1x1 rounded-pill border"
                        url="{{ Auth::user()->image_path }}"
                        ></ratio-image-component>
                    </div>

                    <div class="col">
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
        </h5>

        <!--閉じる-->
        <button type="button" class="btn-close text-reset border" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>


    <div class="offcanvas-body px-0 pt-0">
        @guest
            <div class="d-flex gap-3 p-3">
                <div class="col">
                    <a href="" class="btn btn-lg btn-primary text-white rounded-pill border-warning border-3 shadow w-100">会員登録</a>
                </div>
                <div class="col">
                    <a href="" class="btn btn-lg rounded-pill shadow w-100">ログイン</a>
                </div>
            </div>
        @else
            <!-- 所持ポイント -->
            <div class="p-3 bg-white border-top pt-2">
                <div  style="font-size:14px;">所持ポイント：</div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="col-auto pe-2">

                        @include('includes.point_icon')

                    </div>
                    <div class="col">
                        <div class="">
                            <span class="fs-3 fw-bold">
                                <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                            </span>
                            <span>pt</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('point_sail') }}" class="btn btn- btn-warning text-white rounded-pill shadow">ポイント購入</a>
                    </div>
                </div>
                <div class="form-text ">{{Auth::user()->point_deadline_text}}</div>
            </div>
            <!-- 所持チケット -->
            @if( config('u_rank_ticket.ticket',false) )
                <div class="p-3 bg-white border-top pt-2">
                    <div  style="font-size:14px;">所持チケット：</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="col-auto fs-5 pe-2">
                            <i class="bi bi-ticket-perforated-fill fs-2 text-success"></i>

                            {{-- <img src="{{asset('storage/site/image/ticket/success.png')}}"
                            alt="チケット" class="d-block mx-auto"  style=" width:1.6rem; height:1.6rem; margin:.2rem 0;"> --}}
                        </div>
                        <div class="col">
                            <div class="">
                                <span class="fs-3 fw-bold">
                                    <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                                </span>
                                <span>枚</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('ticket_store') }}"
                            class="d-block btn py-1 btn-success rounded-pill shadow w-100">
                                <div class="d-flex gap-2 text-white align-items-center">

                                    <div class="">チケット交換</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- <a href="https://note.com/cardfesta/n/ne78f9144184a"
                    style="font-size:11px;" target="_blank"
                    ><i class="bi bi-question-circle me-2"></i>チケットについて</a> --}}
                </div>
            @endif
        @endguest


        <div class="list-group list-group-flush">


            <!-- 取得した商品 -->
            <a href="{{ route('user_prize') }}"
            class="list-group-item list-group-item-action
            d-block text-dark mt-3 border- pt-2">
                <div  style="font-size:14px;">取得した商品：</div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="col-auto fs-3 pe-2">
                        <i class="bi bi-files"></i>
                    </div>
                    <div class="col">
                        <div class="">
                            <span class="fs-5 fw-bold">
                                <number-comma-component number="{{ Auth::user()->u_prizes_count }}"></number-comma-component>
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="">一覧を見る<i class="bi bi-chevron-right"></i></span>
                    </div>
                </div>
                <div class="row g-2 mt-2 px- mx-1 pb-2">
                    @foreach (Auth::user()->best_u_prizes as $u_prize)
                        <div class="col-3 text-center">
                            <ratio-image-component
                            style_class="ratio ratio-3x4 rounded-3"
                            url="{{$u_prize->prize->image_path}}"
                            ></ratio-image-component>

                            {{-- <div class="mt-1 w-100 border rounded-pill d-inline-block" style="font-size:11px;">
                                {{number_format($u_prize->prize->point).'pt'}}
                            </div> --}}
                            @if( ! config('app.no_exchange_point') )
                                <div class="mt-1 w-100 border rounded-pill d-inline-block" style="font-size:11px;">
                                    {{number_format($u_prize->point).'pt'}}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </a>





            <a href="{{ route('gacha_history') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative"
            >ガチャ履歴

                <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                ><i class="bi bi-clock-history fs-5"></i></div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            <a href="{{ route('point_history') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative"
            >ポイント履歴


                <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                ><i class="bi bi-p-circle fs-5"></i></div>


                <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            @if( config('u_rank_ticket.ticket',false) )
                <a href="{{ route('ticket_history') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative"
                >チケット履歴


                    <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                    ><i class="bi bi-ticket-perforated-fill fs-5"></i></div>



                    <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                    ><i class="bi bi-chevron-right"></i></div>
                </a>
            @endif

            <a href="{{ route('shipped') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative">
                発送履歴
                @php $unread_count = Auth::user()->unread_send_shippeds_count; @endphp
                @if ( $unread_count )
                    <!--お問い合わせ　未対応-->
                    <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                @endif


                <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                ><i class="bi bi-box-seam fs-5"></i></div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                ><i class="bi bi-chevron-right"></i></div>
            </a>
            @if( config('app.coupon') )
                <a href="{{ route('coupon') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative"
                >クーポン


                    <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                    ><i class="bi bi-ticket-perforated fs-5"></i></div>

                    <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                    ><i class="bi bi-chevron-right"></i></div>
                </a>
            @endif
            <a href="{{ route('settings') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative"
            >会員情報設定


                <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                ><i class="bi bi-gear-fill fs-5"></i></div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            @if( \App\Models\Text::getGuide() )
                <a href="{{ route('guide') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative"
                >利用ガイド

                    <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                    ><i class="bi bi-book fs-5"></i></div>

                    <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                    ><i class="bi bi-chevron-right"></i></div>
                </a>
            @endif

            <a href="{{ route('infomation') }}" class="list-group-item list-group-item-action py-3 px-5 position-relative"
            >お知らせ

                <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                ><i class="bi bi-megaphone fs-5"></i></div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                ><i class="bi bi-chevron-right"></i></div>
            </a>

            {{-- <a href="{{route('timeline')}}"  target="_blank"
            class="list-group-item list-group-item-action py-3 px-5 position-relative">


                <span>タイムライン</span>

                <div class="position-absolute top-50 start-0 translate-middle-y p-3">
                    <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                    alt="xロゴ" class="d-inline-block" style="height:1.125rem;">
                </div>

                <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                ><i class="bi bi-chevron-right"></i></div>
            </a> --}}

            @if ( Auth::check() )
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="list-group-item list-group-item-action py-3 px-5 position-relative"
                    type="submit">{{ __('ログアウト') }}

                        <div class="position-absolute top-50 start-0 translate-middle-y p-3"
                        ><i class="bi bi-box-arrow-right fs-5"></i></div>

                        <div class="position-absolute top-50 end-0 translate-middle-y p-3"
                        ><i class="bi bi-chevron-right"></i></div>
                    </button>
                </form>
            @endif



            <!--お友達紹介キャンペーン-->
            @php
            $canpaing_introductory_active = \App\Http\Controllers\CanpaingIntroductoryController::active();
            @endphp
            @if( $canpaing_introductory_active )
                @php
                    # キャンペーン画像
                    $canpaing = new \App\Http\Controllers\CanpaingIntroductoryController;

                    $image_path = $canpaing::imagePath();
                    $point = number_format( $canpaing::grantPoint() );
                    $key = $canpaing::createKey( Auth::user() );
                    $url = route('canpaing.introductory.register',$key);
                @endphp

                <div class="list-group-item bg-white py-3 border-0">
                    @include('canpaing.introductory_card')
                </div>
            @endif
            <div class="list-group-item bg-white">

                <!-- PWAインストールボタン -->
                <div class="">
                    <pwa-install-btn
                    r_about_pwa="{{route('about_pwa')}}"
                    ></pwa-install-btn>
                </div>

                {{-- <div class="fw-bold text-center mb-2">{{ config('app.name') }}をシェアする</div>
                @include('includes.sns_btn') --}}


                <!--ロゴ-->
                <div class="text-center my-3">
                    <a class="navbar-brand" href="{{ route('gacha_category') }}">
                        <img src="{{asset('storage/site/image/logo.png')}}"
                        alt="{{ config('app.name') }}" class="d-brock mx-auto" style="height:4rem;">
                    </a>
                    <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
                </div>


                <!--SNS Links-->
                @include('includes.sns_links')


                <!-- フッターメニュー -->
                @include('includes.footer_menu')


            </div>

        </div>
    </div>
</div>
