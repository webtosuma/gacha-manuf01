<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasHumberge" aria-labelledby="offcanvasHumbergeLabel"
style="max-width:90vw; min-width:30vw;">

    <div class="offcanvas-header align-items-center py-2 ">
        <!--閉じる-->
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        <h5 class="m-0"> マイメニュー</h5>
        <div class=""></div>
    </div>


    <div class="offcanvas-body px-0 pt-0">


        @include('mypage.menu01')

        @include('mypage.menu02')

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
