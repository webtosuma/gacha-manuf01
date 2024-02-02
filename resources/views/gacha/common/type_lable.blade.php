<!-- 1回限定 -->
@if ($gacha->type=='one_time')
<div class="position-absolute" style="z-index:2; top:-.8rem; right:0rem; transform: skew(-15deg);">
    <div class="p-1 px-4 bg-info-subtle bg-gradient text-info fw-bold border border-info fs-6 "
    >{{$gacha->types()[$gacha->type]}}</div>
</div>
@endif


<!-- 1日限定 -->
@if ($gacha->type=='only_oneday')
<div class="position-absolute" style="z-index:2; top:-1.0rem; right:0rem; transform: skew(-15deg);">
    <div class="p-1 px-4 bg-danger-subtle bg-gradient text-danger fw-bold border border-danger fs-6"
    >{{$gacha->types()[$gacha->type]}}</div>
</div>
@endif
