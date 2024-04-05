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
                            <div class="form-text text-white">
                                {{-- X(旧twitter)ID： --}}
                                <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
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
                style_class="ratio ratio-16x9 rounded- overflow-hidden
                position-relative shiny"
                url="{{ $now_rank->image_path }}"
                ></ratio-image-component>
            </div>
            <div class="col">
                @include('mypage.user_rank')
            </div>
        </div>
        @endif


        <!-- 所持ポイント -->
        <div class=" mt-3 border-top pt-2">
            <div  style="font-size:14px;">所持ポイント：</div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="col-auto pe-2">

                    @include('includes.point_icon')

                </div>
                <div class="col">
                    <div class="">
                        <span class="fs-5 fw-bold">
                            <number-comma-component number="{{ Auth::user()->point }}"></number-comma-component>
                        </span>
                        <span>pt</span>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('point_sail') }}" class="btn btn- btn-warning text-white rounded-pill shadow">ポイント購入</a>
                </div>
            </div>
        </div>

        <!-- 所持チケット -->
        <div class="mt-3 border-top pt-2">
            <div  style="font-size:14px;">所持チケット：</div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="col-auto fs-5 pe-2">
                    <img src="{{asset('storage/site/image/ticket/success.png')}}"
                    alt="チケット" class="d-block mx-auto"  style=" width:2rem; height:2rem; margin:.2rem 0;">
                </div>
                <div class="col">
                    <div class="">
                        <span class="fs-5 fw-bold">
                            <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                        </span>
                        <span>枚</span>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('ticket_store') }}"
                    class="d-block btn py-1 btn-success text-white rounded-pill shadow w-100">
                        <div class="d-flex gap-2 align-items-center">

                            <div class="">チケット交換</div>
                        </div>
                    </a>
                </div>
            </div>
            <a href="https://note.com/cardfesta/n/ne78f9144184a"
            style="font-size:11px;" target="_blank"
            ><i class="bi bi-question-circle me-2"></i>チケットについて</a>
        </div>

        <!-- 取得した商品 -->
        <a href="{{ route('user_prize') }}" class="d-block text-white mt-3 border-top pt-2">
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
        </a>
    </div>
</section>
