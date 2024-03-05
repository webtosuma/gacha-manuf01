{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','会員ランク')


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">会員ランク</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">会員ランク</h3>



    <ul class="list-group list-group-flush">
        {{-- 会員ランク --}}
        <li class="list-group-item bg-white py-4 fs-">
            @if( Auth::user()->now_rank )
                @php $now_rank = Auth::user()->now_rank; @endphp

                <div class="d-flex justify-content-between gap-3">
                    <div class="col-6 col-md-4">
                        <div class="mb-2">現在の会員ランク</div>

                        <ratio-image-component
                        style_class="ratio ratio-16x9 rounded-3 overflow-hidden
                        position-relative shiny"
                        url="{{ $now_rank->image_path }}"
                        ></ratio-image-component>
                    </div>
                    <div class="col">

                        <h5 class="fw-bold mb-2">{{$now_rank->label}}</h5>


                        <div class="progress rounded-0 mb-" style="height: 1.6rem; transform: skew(-15deg);">
                            <div class="progress-bar bg-gradient bg-danger" role="progressbar"
                            style="width: {{$now_rank->meter_warning}}%" aria-valuenow="{{$now_rank->meter_warning}}"
                            aria-valuemin="0" aria-valuemax="100"></div>

                            <div class="progress-bar bg-gradient bg-primary" role="progressbar"
                            style="width: {{$now_rank->meter_success}}%" aria-valuenow="{{$now_rank->meter_success}}"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-end" style="font-size:14px;">pt消費数</div>
                        {{-- <div class="text-end" style="font-size:14px;"
                        >{{ number_format($now_rank->total_play_ptcount) }} / {{ number_format($now_rank->next_rankup_ptcount) }}</div> --}}
                        <div class="text- mt-2" style="font-size:11px;">『{{$now_rank->next_rank->label}}』まであと、</div>
                        <div class="text-end fs-5" style="font-size:14px;"
                        >{{ number_format($now_rank->next_rankup_ptcount-$now_rank->total_play_ptcount) }}pt</div>
                    </div>
                </div>
            @endif
        </li>



        <!-- 一覧 -->
        @forelse ($user_rank_histories as $user_rank_history)
            <li class="list-group-item bg-white py-3">
                <div class="row align-items-center ">
                    <div class="col">

                        <div class="form-text">{{$user_rank_history->created_at->format('Y/m/d H:i')}}</div>
                        <div class="fw-bold">{{$user_rank_history->label}}</div>
                        <div class="form-text">pt消費数:{{ number_format( $user_rank_history->pt_count ) }}pt</div>

                    </div>
                    <div class="col-auto" style="width:120px;">
                        <ratio-image-component
                        style_class="ratio ratio-16x9 rounded-3 overflow-hidden
                        position-relative shiny"
                        url="{{ $user_rank_history->image_path }}"
                        ></ratio-image-component>
                    </div>
                </div>
            </li>
        @empty
            <li class="list-group-item bg-white py-3 fw-bold">履歴はありません。</li>
        @endforelse
</ul>

</div>
@endsection
