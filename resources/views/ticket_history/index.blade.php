{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','チケット履歴')


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">チケット履歴</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="d-none d-md-block ">チケット履歴</h3>



    <ul class="list-group list-group-flush">
        {{-- 所持チケット --}}
        <li class="list-group-item bg-white py-4 fs-">
            <div class="d-flex justify-content-between align-items-center bg-white">
                <div class="col">
                    <div class="">所持チケット：</div>
                    <div class="">
                        <span class="fs-3 fw-bold">
                            <number-comma-component number="{{ Auth::user()->ticket }}"></number-comma-component>
                        </span>
                        <span>枚</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="mb-">
                        <a href="{{ route('ticket_store') }}"
                        class="d-block btn py-1 btn-success text-white rounded-pill shadow w-100">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="">チケット交換</div>
                            </div>
                        </a>
                    </div>
                    {{-- <div class="mt-3">
                        <a href="{{ route('ticket_sail') }}"
                        class="d-block btn py-1 border-success text-success rounded-pill shadow w-100">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="">
                                    <div class="rounded-circle border border-success fw-bold bg-gradient text-success
                                    d-flex align-items-center justify-content-center mx-auto
                                    " style=" width:1rem; height:1rem; margin:.4rem 0; font-size:11px;">P</div>
                                </div>

                                <div class="">チケット購入</div>
                            </div>
                        </a>
                    </div> --}}
                </div>
            </div>
        </li>



        <!-- 一覧 -->
        @forelse ($ticket_histories as $ticket_history)
            <li class="list-group-item bg-white py-3">
                <div class="row align-items-center ">
                    <div class="col">

                        <div class="d-flex align-items-center justify-content-between">
                            @php
                            $text_color = $ticket_history->value >= 0 ? 'text-primary' : 'text-danger';
                            $text_color = $ticket_history->reason_id==14 ? 'text-warning' : $text_color;
                            $sine = $ticket_history->value > 0 ? '+' : ( $ticket_history->value < 0 ? '-' : '' );
                            @endphp
                            <div class="">
                                <div class="form-text">{{$ticket_history->created_at->format('Y/m/d H:i')}}</div>
                                <div class="fw-bold"><span class="{{$text_color}}">●</span>{{ $ticket_history->reason }}</div>
                            </div>

                            <div class="col-auto {{$ticket_history->value >= 0 ?'':'text-danger'}}">
                                {{$ticket_history->value >= 0 ?'+':''}}
                                <number-comma-component number="{{ $ticket_history->value }}"></number-comma-component>
                                {{'枚'}}
                            </div>
                        </div>



                    </div>
                </div>
            </li>

        @empty
            <li class="list-group-item bg-white py-3 fw-bold">履歴はありません。</li>
        @endforelse
    </ul>

</div>
@endsection
