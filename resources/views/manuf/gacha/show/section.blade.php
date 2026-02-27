@php $margin_bottom = '32'; @endphp

{{-- 表示：登録カード数が0いじょうのとき --}}
@if ( ($discription->g_prizes->count() || $discription->image) && $discription->gacha_rank_id<900)
    <section class="py- col-12 mb-55"
    style="margin-bottom:{{$margin_bottom*2}}px;"
    >
        <div class="container overflow-hidden px-3"
        >


            <!-- Rankラベル(広告がある時は非表示) -->
            @if ( !$gacha->sponsor_ad )
                @if ($discription->rank_label_image)
                    <div class="col-12 mx-auto "
                    style="margin-bottom:{{$margin_bottom}}px;">
                        <img class="d-block w-100"
                        src="{{ $discription->rank_label_image }}"
                        alt="{{ $discription->rank_label }}">
                    </div>
                @else

                    <div class="text-center  text-white"
                    style="margin-bottom:{{$margin_bottom}}px; font-size:3rem;"
                    >{{$discription->rank_label }}</div>

                @endif
            @endif



            <!--商品画像-->
            @if ( $discription->image )
                <img class="d-block w-100"
                src="{{ $discription->image_path }}"
                alt="{{ $discription->rank_label.'商品画像' }}">
            @else

                @php
                $col     = $discription->g_prizes_show_section->count()==1 ? 'col-8' : ($discription->gacha_rank_id < 400 ? 'col-6' : 'col-3') ;
                $rounded = $discription->g_prizes_show_section->count()==1 ? 'rounded-4' : 'rounded-2' ;
                $fs      = $discription->gacha_rank_id < 400 ? 'fs-1' : '' ;

                $mdal_btn_size = $discription->g_prizes_show_section->count()==1 ? '4rem'//説明文モーダルボタンサイズ
                : ($discription->gacha_rank_id < 400 ? '3rem' : '2rem')
                @endphp

                <div class="row g-2 px- justify-content-center">
                    @foreach ($discription->g_prizes_show_section as $gacha_prize)

                        @php $prize = $gacha_prize->prize; @endphp


                        <div class="{{ $col }}">
                            <div class="position-relative">
                                <ratio-image-component
                                style_class="ratio ratio-3x4 {{$rounded}}"
                                url="{{$gacha_prize->prize->image_path}}"
                                ></ratio-image-component>

                                @if(
                                    $discription->gacha_rank_id <= 399
                                    && $discription->gacha_rank_id!=10
                                )
                                    <!--登録枚数-->
                                    <div class="position-absolute bottom-0 end-0 p-1">
                                        <div class="bg-dark text-white px-2 rounded {{$fs}}"
                                        >{{'×'.($gacha_prize->sum_max_count) }}</div>
                                    </div>
                                @endif


                                <!--商品説明モーダルボタン-->
                                @if( $prize->discription_text )
                                {{-- @if( true ) --}}
                                    <div class="position-absolute w-100 text-end"
                                    style="z-index:3; top:-5%; left:5%;">
                                        <img v-if="no_btn!=1"
                                        src="{{$prize->discription_icon_path}}"
                                        alt="商品説明ボタン"
                                        class="btn btn-dark p-0 rounded-circle shadow"
                                        style="{{'width:'.$mdal_btn_size.';'}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#PrizeDiscriptionModal{{$prize->id}}"
                                        >
                                    </div>
                                @endif


                            </div>
                        </div>





                    @endforeach
                </div>

            @endif


            <!-- 商品説明文 -->
            @if ( $discription->sorce )
                <p class="p-3 mt-2 mb-0 form-text text-secondary" style="border-radius:1rem; background:rgb(255, 255, 255, .9);"
                ><replace-text-component text="{{$discription->sorce_text }}"></replace-text-component></p>
            @endif


        </div>
    </section>


    <!--商品説明モーダル-->
    <div class="overflow-hidden" style="height:0;">
        @foreach ($discription->g_prizes_show_section as $gacha_prize)
            @php $prize = $gacha_prize->prize; @endphp
            <u-prize-discription
            id         ="{{$prize->id}}"
            name       ="{{$prize->name}}"
            image_path ="{{$prize->image_path}}"
            discription="{{ $prize->discription_text }}"
            size       ="4rem"
            src_icon   ="{{$prize->discription_icon_path}}"
            no_btn     ="1"
            bg_dark    =""
            ></u-prize-discription>
        @endforeach
     </div>


@endif
