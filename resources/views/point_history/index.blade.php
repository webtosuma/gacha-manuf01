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
            <div class="">所持ポイント：<span class="fs-3 fw-bold">
                <number-comma-component number="{{ Auth::user()->point.'pt' }}"></number-comma-component>
            </span></div>
            <div class="col-auto">
                <a href="{{ route('point_sail') }}" class="btn btn-warning text-white rounded-pill shadow">ポイント購入</a>
            </div>
        </div></li>


        @forelse ($point_histories as $point_history)

            @switch( $point_history->reason_id)
                @case(11)
                    {{-- ポイント購入 --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i')}}</div>
                            <div class="fw-bold"><span class="text-primary">●</span>ポイント購入（カード決済）</div>
                            <div class="">購入金額：¥
                                <number-comma-component number="{{ $point_history->price }}"></number-comma-component>
                            </div>
                        </div>

                        <div class="">
                            {{'+'}}
                            <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                            {{'pt'}}
                        </div>
                    </div></li>
                    @break

                @case(12)
                    {{-- 商品のポイント交換 --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="form-text">{{ $point_history->created_at->format('Y/m/d H:i') }}</div>
                            <div class="fw-bold"><span class="text-primary">●</span>商品のポイント交換</div>
                        </div>

                        <div class="">
                            {{'+'}}
                            <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                            {{'pt'}}
                        </div>
                    </div></li>
                    @break
                @case(13)
                    {{-- キャンペーン付与 --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="form-text">{{ $point_history->created_at->format('Y/m/d H:i') }}</div>
                            <div class="fw-bold"><span class="text-warning">●</span>キャンペーン付与</div>
                        </div>

                        <div class="">
                            {{'+'}}
                            <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                            {{'pt'}}
                        </div>
                    </div></li>
                    @break
                @case(14)
                    {{-- 特別付与 --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="form-text">{{ $point_history->created_at->format('Y/m/d H:i') }}</div>
                            <div class="fw-bold"><span class="text-warning">●</span>特別付与</div>
                        </div>

                        <div class="">
                            {{'+'}}
                            <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                            {{'pt'}}
                        </div>
                    </div></li>
                    @break


                @case(21)
                    {{-- ガチャる --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <div class="form-text">{{ $point_history->created_at->format('Y/m/d H:i') }}</div>
                            <div class="fw-bold">
                                <span class="text-danger">●</span>ガチャ
                            </div>
                            <div class="">
                                <span>{{ '['.$point_history->user_gacha_history->gacha->name.']' }}</span>
                                <span>{{ '（'.$point_history->user_gacha_history->play_count.'回）' }}</span>
                            </div>
                        </div>

                        <div class="text-danger">
                            <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                            {{'pt'}}
                        </div>
                    </div></li>
                    @break

                @case(22)
                    {{-- 商品発送 --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        @php
                        $text_color = $point_history->value >= 0 ? 'text-secondary' : 'text-danger';
                        $sine = $point_history->value > 0 ? '+' : ( $point_history->value < 0 ? '-' : '' );
                        @endphp
                        <div class="">
                            <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i')}}</div>
                            <div class="fw-bold"><span class="{{$text_color}}">●</span>商品発送</div>
                            <div class="">配送料・手数料</div>
                        </div>

                        <div class="{{$text_color}}">
                            <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                            {{'pt'}}
                        </div>

                    </div></li>
                    @break

                @default
                    {{-- その他 --}}
                    <li class="list-group-item bg-white py-3"><div class="d-flex align-items-center justify-content-between">
                        @php
                        $text_color = $point_history->value >= 0 ? 'text-secondary' : 'text-danger';
                        $sine = $point_history->value > 0 ? '+' : ( $point_history->value < 0 ? '-' : '' );
                        @endphp
                        <div class="">
                            <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i')}}</div>
                            <div class="fw-bold"><span class="{{$text_color}}">●</span>その他</div>
                        </div>

                        <div class="{{$text_color}}">
                            <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                            {{'pt'}}
                        </div>
                    </div></li>
        @endswitch
        @empty
            <li class="list-group-item bg-white py-3 fw-bold">ご利用はありません。</li>
        @endforelse

    </ul>
</div>
@endsection
