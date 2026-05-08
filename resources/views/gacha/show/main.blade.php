<!--トップー-->
<section class="overflow-hidden px-1">
    <div class="containerr mx-auto px-0">

        <div class="overflow-hidden rounded-4"
        data-aos="zoom-inin"
        >

            @include('gacha.common.top_image')

        </div>

        @if( env('SHARE_BTNS') )
            <div class="d-flex justify-content-end mt-3 d-lg-none">
                @php
                $sns_url  = request()->url();
                $sns_text = $gacha->name;
                $sns_size = '2rem';
                @endphp
                @include('includes.sns_btn')
            </div>
        @endif

    </div>
</section>
<section
data-aos="zoom-inin"
>

    <!--サブスクプラン-->
    @if($gacha->subscription_id)
        <div class="text-center fw-bold">『{{ $gacha->subscription->sub_label }}』専用</div>
    @endif



    <div class="d-flex align-items-end justify-content-start gap-2 px-2 pt-1">

        @if( $gacha->new_label && $gacha->is_type_label_text )
            <!--新着-->
            <span
            class="bg-danger text-white px-2 rounded-pill"
            style="font-size:12px;"
            >{{ 'NEW' }}</span>
        @endif

        @if( $gacha->user_rank_label && $gacha->is_type_label_text )
            <!--会員ランク限定-->
            <span class="bg-info border text-light px-2 rounded-pill"
            style="font-size:11px;"
            >{{ $gacha->user_rank_label }}</span>
        @endif

        @if( $gacha->type_label && $gacha->is_type_label_text )
            <!--限定ガチャラベル-->
            <span class="bg-body border text-dark px-2 rounded-pill"
            style="font-size:11px;"
            >{{ $gacha->type_label }}</span>
        @endif

    </div>



</section>
<!--各賞-->
<section class="overflow-hidden">
    <div
    data-aos="zoom-inin"
    class="row justify-content-center mx-auto" style="max-width:600px; margin-top:20vh;">

        @foreach ($gacha->discriptions as $discription)


            @include('gacha.show.section')


        @endforeach
    </div>
</section>


<!--商品説明モーダル-->
@foreach ($gacha->discriptions as $discription)

    <div class="overflow-hidden" style="height:0px;">
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

@endforeach

