<!--トップー-->
<section class="p- pb-md-5">
    <div class="mx-auto overflow-auto px-0" style="max-width:1200px;">


        <div class="d-none d-md-block overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-16x9 w-100"
            ></ratio-image-component>
        </div>
        <div class="d-md-none  overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-1x1 w-100"
            ></ratio-image-component>
        </div>


    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:1200px;">
    @foreach ($gacha->discriptions as $discription)
        <section class="py-5 col-12">

            <div class="container overflow-auto" style="max-width:600px;">

                <!-- 賞ラベル -->
                @switch( $discription->gacha_rank_id )
                    @case('101')
                        <div class="col-12">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/rank/ss01.png')}}" alt="1等賞">
                        </div>

                        <!--カード画像-->
                        <div class="row gy-3 mb-3 justify-content-center">
                            @foreach ($gacha->rank_a_prizes as $gacha_prize)
                                <div class="col-6 position-relative">
                                    <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded-3"
                                    url="{{$gacha_prize->prize->image_path}}"
                                    ></ratio-image-component>

                                   <div class="position-absolute bottom-0 end-0 translate-middle
                                   bg-dark text-white px-2 rounded fs-5">{{'×'.$gacha_prize->max_count}}</div>
                                </div>
                            @endforeach
                        </div>
                        @break
                    @case('102')
                        <div class="col-12">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/rank/s01.png')}}" alt="1等賞">
                        </div>

                        <!--カード画像-->
                        <div class="row gy-3 mb-3 justify-content-center">
                            @foreach ($gacha->rank_b_prizes as $gacha_prize)
                                <div class="col-4 position-relative">
                                    <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded-3"
                                    url="{{$gacha_prize->prize->image_path}}"
                                    ></ratio-image-component>

                                <div class="position-absolute bottom-0 end-0 translate-middle
                                bg-dark text-white px-2 rounded fs-5">{{'×'.$gacha_prize->max_count}}</div>
                                </div>
                            @endforeach
                        </div>
                        @break
                    @case('103')
                        <div class="col-12">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/rank/a01.png')}}" alt="1等賞">
                        </div>

                        <!--カード画像-->
                        <div class="row gy-3 mb-3 justify-content-center">
                            @foreach ($gacha->rank_c_prizes as $gacha_prize)
                                <div class="col-4 position-relative">
                                    <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded-3"
                                    url="{{$gacha_prize->prize->image_path}}"
                                    ></ratio-image-component>

                                <div class="position-absolute bottom-0 end-0 translate-middle
                                bg-dark text-white px-2 rounded fs-5">{{'×'.$gacha_prize->max_count}}</div>
                                </div>
                            @endforeach
                        </div>
                        @break
                    @case('104')
                        <div class="col-12">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/rank/b01.png')}}" alt="1等賞">
                        </div>

                        <!--カード画像-->
                        {{-- @if ( $discription->image_path )
                            <img class="d-block w-100 shadow" style="border-radius:1rem;"
                            src="{{$discription->image_path }}" alt="商品画像">
                        @else --}}
                            <div class="row gy-3 mb-3 justify-content-center">
                                @foreach ($gacha->rank_d_prizes as $gacha_prize)
                                    <div class="col-4 position-relative">
                                        <ratio-image-component
                                        style_class="ratio ratio-3x4 rounded-3"
                                        url="{{$gacha_prize->prize->image_path}}"
                                        ></ratio-image-component>

                                    <div class="position-absolute bottom-0 end-0 translate-middle
                                    bg-dark text-white px-2 rounded fs-5">{{'×'.$gacha_prize->max_count}}</div>
                                    </div>
                                @endforeach
                            </div>
                        {{-- @endif --}}
                        @break
                    @case('105')
                        <div class="col-8 mx-auto mb-3">
                            <div class="fw-bold text-center" style="font-size:3rem;">Rank C</div>
                            {{-- <img class="d-block w-100"
                            src="{{asset('storage/site/image/gacha/04prize.png')}}" alt="4等賞"> --}}
                        </div>
                        @break
                    @case('106')
                        <div class="col-8 mx-auto mb-3">
                            <div class="fw-bold text-center" style="font-size:3rem;">Rank D</div>
                            {{-- <img class="d-block w-100"
                            src="{{asset('storage/site/image/gacha/04prize.png')}}" alt="4等賞"> --}}
                        </div>
                        @break

                @endswitch

                <!-- 商品画像 -->
                {{-- @if ( $discription->image_path )
                    <img class="d-block w-100 shadow" style="border-radius:1rem;"
                    src="{{$discription->image_path }}" alt="商品画像">
                @endif --}}

                <!-- 商品説明文 -->
                @if ( $discription->sorce )
                <p class="p-3 mt-2 form-text text-secondary" style="border-radius:1rem; background:rgb(255, 255, 255, .9);"
                ><replace-text-component text="{{$discription->sorce_text }}"></replace-text-component></p>
                @endif

            </div>
        </section>
    @endforeach
</div>
