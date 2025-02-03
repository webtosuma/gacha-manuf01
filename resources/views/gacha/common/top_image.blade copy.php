<div class="position-relative"
data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $gacha->name }}">

    <!--loading-->
    <div class="ratio {{config('app.gacha_card_ratio')}} ">
        <div class="bg- d-flex align-items-center justify-content-center"
        style="z-index:0;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


    {{-- (時間帯限定)カウントダウン --}}
    @if( $gacha->is_published && $gacha->initial_timezone )
        <u-countdown-gacha
        text="販売開始まであと"
        initial_time="{{$gacha->initial_timezone}}"
        ></u-countdown-gacha>
    @endif



    <!--gacha image-->
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:0;">
        <ratio-image-component
        url="{{ $gacha->image_path }}" style_class="ratio {{config('app.gacha_card_ratio')}}  bg-body"
        ></ratio-image-component>
    </div>


    <!--アド確定予告-->
    @if( $gacha->add_chance_image_path )
        <div class="position-absolute top-0 start-0 w-100 h-100 gacha_chance"
        style="z-index:10;">
            <div class="position-relative">

                <ratio-image-component
                url="{{ $gacha->add_chance_image_path }}"
                style_class="ratio {{config('app.gacha_card_ratio')}}  bg-body"
                ></ratio-image-component>

            </div>
        </div>
    @endif

    <!-- アド確定予告メーター -->
    @if($gacha->add_chance_image_path)
        @php $ration = ( 1 - ( ($gacha->add_chance_count+0) /10) ) *100; @endphp
        <div class="position-absolute bottom-0 start-0 w-100 px-5 py-1"
        style="z-index:11; opacity:.8;">
            <div class="progress bg-danger-subtle text-danger fw-bold">
                @php $count_down_label = 'あと'.($gacha->add_chance_count+1).'回でアド確定'; @endphp
                <div class="progress-bar bg-danger" role="progressbar"
                style="width: {{$ration}}%" aria-valuenow="{{$ration}}" aria-valuemin="0" aria-valuemax="{{$ration}}"
                >{{$ration>50 ? $count_down_label : '' }}</div>
                {{ $ration>50 ? '' : $count_down_label }}
            </div>
        </div>
    @endif

    <!-- 個人ガチャ 個人PLAY数 -->
    @if($gacha->have_user_rank)
        <div class="position-absolute bottom-0 end-0"
        style="z-index:11; opacity:.8;">
            <div class="bg-dark text-white px-2 mb-3 border border-2 border-info"
            style="border-radius: 50rem 0 0 50rem; border-right:none !important;">
                <span style="font-size:.8rem;">個人PLAY数</span>
                <span class="fs-5">{{$gacha->user_played_count}}</span>
                <span style="font-size:.6rem;">回</span>
            </div>
        </div>
    @endif


    <!--売り切れ-->
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
