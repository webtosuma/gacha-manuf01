<div class="">

    <h6 class="fw-bold mb-2">{{$now_rank->label}}</h6>


    <div class="progress rounded-0 mb-" style="height: 1.6rem; transform: skew(-15deg);">
        <div class="progress-bar bg-gradient bg-danger" role="progressbar"
        style="width: {{$now_rank->meter_warning}}%" aria-valuenow="{{$now_rank->meter_warning}}"
        aria-valuemin="0" aria-valuemax="100"></div>

        <div class="progress-bar bg-gradient bg-primary" role="progressbar"
        style="width: {{$now_rank->meter_success}}%" aria-valuenow="{{$now_rank->meter_success}}"
        aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="text-end" style="font-size:11px;">
        pt消費数/月
        <span style="font-size:14px;">{{ number_format($now_rank->total_play_ptcount) }}</span>
        pt
    </div>

    @if($now_rank->next_rank)
        <div class="text-end mt-2" style="font-size:11px;">『{{$now_rank->next_rank->label}}』まであと、</div>
        <div class="text-end" style="font-size:14px;"
        >{{ number_format($now_rank->next_rankup_ptcount-$now_rank->total_play_ptcount) }}pt</div>
    @endif

    <a href="https://note.com/cardfesta/n/ne0c0ee493e97"
    class="my-2" style="font-size:11px;" target="_blank"
    ><i class="bi bi-question-circle me-2"></i>会員ランクについて</a>
</div>
