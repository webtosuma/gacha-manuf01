<div class="position-relative"
data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $gacha->name }}">

    <!--loading-->
    <div class="ratio ratio-4x3">
        <div class="bg-dark d-flex align-items-center justify-content-center"
        style="z-index:0;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


    @if( $gacha->is_published && $gacha->initial_timezone ){{-- (時間帯限定)カウントダウン --}}
        <u-countdown-gacha
        text="販売開始まであと"
        initial_time="{{$gacha->initial_timezone}}"
        ></u-countdown-gacha>
    @endif



    <!--gacha image-->
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:0;">
        <ratio-image-component
        url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3 bg-body"
        ></ratio-image-component>
    </div>




    @if ($gacha->remaining_count==0)
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:3; background: rgba(0, 0, 0, .7);"
    ><div class="d-flex align-items-center justify-content-center h-100 fs-3 text-white"
    >SOLD OUT</div></div>
    @endif

    <!-- ワンチャンス限定 -->
    @if ($gacha->type=='one_chance')
    <div class="position-absolute p-2 top-0 end-0 text-end w-100">
        <img src="{{  asset( 'storage/site/image/gacha_type/one_chance.png' ) }}" style="width:30%;" alt="">
    </div>
    @endif

    <!-- 1回限定 -->
    @if ($gacha->type=='one_time')
    <div class="position-absolute p-2 top-0 end-0 text-end w-100">
        <img src="{{  asset( 'storage/site/image/gacha_type/one_time.png' ) }}" style="width:30%;" alt="">
    </div>
    @endif


    <!-- 1日限定 -->
    @if ($gacha->type=='only_oneday')
    <div class="position-absolute p-2 top-0 end-0 text-end w-100">
        <img src="{{  asset( 'storage/site/image/gacha_type/only_oneday.png' ) }}" style="width:30%;" alt="">
    </div>
    @endif


    <!-- 新規会委員限定 -->
    @if ($gacha->type=='only_new_user')
    <div class="position-absolute p-2 top-0 end-0 text-end w-100">
        <img src="{{  asset( 'storage/site/image/gacha_type/only_new_user.png' ) }}" style="width:30%;" alt="">
    </div>
    @endif



    <!-- 会員ランク限定 -->
    @if ($gacha->user_rank_id!='')
    <div class="position-absolute p-2 bottom-0 end-0 text-end w-100">
        <img src="{{ $gacha->user_rank->image_path }}" style="width:35%;" alt="">
    </div>
    @endif


    {{-- <!-- 広告ガチャ -->
    @if ($gacha->sponsor_ad)
    <div class="position-absolute p-2 bottom-0 end-0 text-end ">
        <div class="p-2 px-3 border border-2 border-white text-secondary fw-bold"
        style="background: rgb(255 255 255 / 70%);">広告</div>
    </div>
    @endif --}}

</div>
