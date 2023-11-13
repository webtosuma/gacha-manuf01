@extends('layouts.app')

<!----- title ----->
@section('title','ポイント履歴')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">ポイント履歴</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3>ポイント履歴</h3>
    <ul class="list-group list-group-flush">

        <li class="list-group-item bg-white py-4 fs-"><div class="d-flex align-items-center justify-content-between">
            {{-- 所持ポイント --}}
            <div class="">所持ポイント：<span class="fs-3 fw-bold">{{ Auth::user()->point.'pt' }}</span></div>
            <a href="{{ route('point_sail') }}" class="btn btn-warning rounded-pill shadow">ポイント購入</a>

        </div></li>


        @forelse ($point_histories as $point_history)

            @switch( $point_history->reason_id)
                @case(11)
                    {{-- ポイント購入 --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i')}}</div>
                            <div class="fw-bold"><span class="text-primary">●</span>ポイント購入（カード決済）</div>
                            <div class="">購入金額：¥{{ $point_history->price }}</div>
                        </div>

                        <div class="">{{'+'.$point_history->value.'pt'}}</div>
                    </div></li>
                    @break
                @case(21)
                    {{-- ガチャる --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i')}}</div>
                            <div class="fw-bold"><span class="text-danger">●</span>ガチャ：[ガチャタイトル]（N回）</div>
                        </div>

                        <div class="text-danger">{{$point_history->value.'pt'}}</div>
                    </div></li>
                    @break
                @default
                    <div class="">sonota</div>
            @endswitch
        @empty
            <li class="list-group-item bg-white py-3 fw-bold">ご利用はありません。</li>
        @endforelse

    </ul>
</div>
@endsection
