{{-- 表示：登録カード数が0いじょうのとき --}}
@if ( ($discription->g_prizes->count() || $discription->image) && $discription->gacha_rank_id<900)
    <section class="py-5 col-12"
    style="margin-bottom:20rem;"
    >
        <div class="container overflow-auto"
        data-aos="zoom-out"
        >


            <!-- Rankラベル(広告がある時は非表示) -->
            @if ( !$gacha->sponsor_ad )
                @if ($discription->rank_label_image)
                    <div class="col-12 mx-auto py-5 my-3">
                        <img class="d-block w-100"
                        src="{{ $discription->rank_label_image }}"
                        alt="{{ $discription->rank_label }}">
                    </div>
                @else

                    <div class="text-center py-5 my-3 text-white" style="font-size:3rem;">{{$discription->rank_label }}</div>

                @endif
            @endif



            <!--商品画像-->
            @if ( $discription->image )
                <img class="d-block w-100"
                src="{{ $discription->image_path }}"
                alt="{{ $discription->rank_label.'商品画像' }}">
            @else

                @php
                $col = $discription->g_prizes->count()==1 ? 'col-12' : ($discription->gacha_rank_id < 400 ? 'col-6' : 'col-3') ;
                $fs = $discription->gacha_rank_id < 400 ? 'fs-1' : '' ;
                @endphp
                <div class="row g-2 mb-3 justify-content-center">
                    @foreach ($discription->g_prizes as $gacha_prize)


                        <div class="{{ $col }}">
                            <div class="position-relative">
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded-2"
                                url="{{$gacha_prize->prize->image_path}}"
                                ></ratio-image-component>

                                @if( $discription->gacha_rank_id <= 300 && $discription->gacha_rank_id!=10 )
                                    <!--登録枚数-->
                                    <div class="position-absolute bottom-0 end-0 p-1">
                                        <div class="bg-dark text-white px-2 rounded {{$fs}}"
                                        >{{'×'.$gacha_prize->max_count}}</div>
                                    </div>
                                @endif
                            </div>
                        </div>


                    @endforeach
                </div>

            @endif


            <!-- 商品説明文 -->
            @if ( $discription->sorce )
                <p class="p-3 mt-2 form-text text-secondary" style="border-radius:1rem; background:rgb(255, 255, 255, .9);"
                ><replace-text-component text="{{$discription->sorce_text }}"></replace-text-component></p>
            @endif


        </div>
    </section>
@endif
