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


    @if( $gacha->initial_timezone ){{-- (時間帯限定)カウントダウン --}}
        <u-countdown-gacha
        text="販売開始まであと"
        initial_time="{{$gacha->initial_timezone}}"
        ></u-countdown-gacha>
    @endif



    <!--gacha image-->
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:0;">
        <ratio-image-component
        url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3"
        ></ratio-image-component>
    </div>




    @if ($gacha->remaining_count==0)
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:3; background: rgba(0, 0, 0, .7);"
    ><div class="d-flex align-items-center justify-content-center h-100 fs-3 text-white"
    >売り切れました</div></div>
    @endif


    <!-- 1回限定 -->
    @if ($gacha->type=='one_time')
    <div class="position-absolute p-2 top-0 end-0 text-end w-100">
        <img src="{{  asset( 'storage/site/image/gacha_type/one_time.png' ) }}" style="width:30%;" alt="">
    </div>

    {{-- <div class="position-absolute p-2 pe-3 top-0 end-0">
        <div class="p-2 px-4 bg- bg-gradient text-warning fw-bold border border-3 border-warning fs-5 "
        style="z-index:2; transform: skew(-15deg); background-color: rgba(0, 0, 0, .8)"
        >{{$gacha->types()[$gacha->type]}}</div>
    </div> --}}
    @endif


    <!-- 1日限定 -->
    @if ($gacha->type=='only_oneday')
    <div class="position-absolute p-2 top-0 end-0 text-end w-100">
        <img src="{{  asset( 'storage/site/image/gacha_type/only_oneday.png' ) }}" style="width:30%;" alt="">
    </div>

    {{-- <div class="position-absolute p-2 pe-3 top-0 end-0">
        <div class="p-2 px-4 bg- bg-gradient text-white fw-bold border border-3 border-danger fs-5"
        style="z-index:2; transform: skew(-15deg); background-color: rgba(216, 85, 150, .8)"
        >{{$gacha->types()[$gacha->type]}}</div>
    </div> --}}
    @endif


    <!-- 新規会委員限定 -->
    @if ($gacha->type=='only_new_user')
    <div class="position-absolute p-2 top-0 end-0 text-end w-100">
        <img src="{{  asset( 'storage/site/image/gacha_type/only_new_user.png' ) }}" style="width:30%;" alt="">
    </div>

    {{-- <div class="position-absolute p-2 pe-3 top-0 end-0">
        <div class="p-2 px-4 bg- bg-gradient text-white fw-bold border border-3 border-success fs-5"
        style="z-index:2; transform: skew(-15deg); background-color: rgba(85, 216, 177, .8)"
        >{{$gacha->types()[$gacha->type]}}</div>
    </div> --}}
    @endif



    <!-- 会員ランク限定 -->
    @if ($gacha->user_rank_id!='')
    <div class="position-absolute p-2 bottom-0 end-0 text-end w-100">
        <img src="{{ $gacha->user_rank->image_path }}" style="width:35%;" alt="">
    </div>
    @endif


</div>
