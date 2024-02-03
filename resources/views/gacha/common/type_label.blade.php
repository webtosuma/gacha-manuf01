<!-- 1回限定 -->
@if ($gacha->type=='one_time')
<div class="position-absoluteeee" style="z-index:2; top:-.8rem; right:0rem; transform: skew(-15deg);">
    <div class="p-2 py-1 px- bg- bg-gradient text-warning fw-bold border border-3 border-warning fs- "
    style="background-color: rgba(0, 0, 0, .7); font-size:.8rem;"
    >{{$gacha->types()[$gacha->type]}}</div>
</div>
@endif


<!-- 1日限定 -->
@if ($gacha->type=='only_oneday')
<div class="position-absoluteeee" style="z-index:2; top:-1.0rem; right:0rem; transform: skew(-15deg);">
    <div class="p-2 py-1 px- bg- bg-gradient text-white fw-bold border border-3 border-danger fs-"
    style="background-color: rgba(216, 85, 150, .7); font-size:.8rem;"
    >{{$gacha->types()[$gacha->type]}}</div>
</div>
@endif


{{-- <!-- NEW -->
@if ($gacha->type=='nomal')
    @include('gacha.common.new_label')
@endif --}}
