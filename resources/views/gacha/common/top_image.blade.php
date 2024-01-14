<div class="position-relative">
    <ratio-image-component
    url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3"
    ></ratio-image-component>

    @if ($gacha->remaining_count==0)
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:10; background: rgba(0, 0, 0, .7);"
    ><div class="d-flex align-items-center justify-content-center h-100 fs-1 text-white"
    >売り切れました</div></div>
    @endif

    <!-- 1回限定 -->
    @if ($gacha->type=='one_time')
    <div class="position-absolute top-0 end-0">
        <div class="m-2 p-2 px-3 border border-danger bg-info text-white fs-5 rounded-3"
        >{{$gacha->types()[$gacha->type]}}</div>
    </div>
    @endif


    <!-- 1日限定 -->
    @if ($gacha->type=='only_oneday')
    <div class="position-absolute top-0 end-0">
        <div class="m-2 p-2 px-3 border border-white bg-danger text-white fs-5 rounded-3"
        >{{$gacha->types()[$gacha->type]}}</div>
    </div>
    @endif

</div>
