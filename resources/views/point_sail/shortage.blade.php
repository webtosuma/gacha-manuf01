@extends('layouts.app')

<!----- title ----->
@section('title','ポイントが不足しています。')


@section('content')
<!--breadcrumb-->
<div class="container mt-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item"><a href="{{ $gacha->route }}">{{$gacha->name}}</a></li>
          <li class="breadcrumb-item active" aria-current="page">不足ポイント確認</li>
        </ol>
    </nav>
</div>


<div class="container py-4 mb-5">
    <h3 class="text-center text-danger my-3">ポイントが不足しています</h3>

    <ul class="list-group list-group-flush mb-5 mx-auto" style="max-width:600px;">
        <li class="list-group-item bg-white py-5">
            <div class="mx-auto" style="max-width:400px;">
                <a href="{{$gacha->route}}"
                class="card shadow bg-white border-0
                text-dark text-center overflow-hidden text-decoration-none
                hover_anime" style="border-radius:1rem;">


                    <!--image-->
                    @include('gacha.common.top_image')

                    <!--metter-->
                    @include('gacha.common.metter')

                </a>
            </div>
        </li>
        <li class="list-group-item bg-white py-4 text-center">

            <div class="mb-3">
                @php $play_point = $gacha->one_play_point; @endphp
                <div class="fw-bold">1回ガチャる {{ number_format($play_point) }}pt</div>


                @php $shortage_point = $play_point - Auth::user()->point; @endphp
                @if ( $shortage_point > 0)
                    <div class="fw-bold text-danger"
                    >{{ number_format($play_point - Auth::user()->point) }}pt不足しています。</div>
                @else
                    <div class="fw-bold">不足はありません。</div>
                @endif
            </div>

            <div class="mb-3">
                @php $play_point = $gacha->one_play_point * 10; @endphp
                <div class="fw-bold">10連続ガチャる {{ number_format($play_point) }}pt</div>


                @php $shortage_point = $play_point - Auth::user()->point; @endphp
                @if ( $shortage_point > 0)
                    <div class="fw-bold text-danger"
                    >{{ number_format($play_point - Auth::user()->point) }}pt不足しています。</div>
                @else
                    <div class="fw-bold">不足はありません。</div>
                @endif
            </div>

            <div class="text-form">
                現在の所有ポイント：{{ number_format(Auth::user()->point) }}pt
            </div>
        </li>
    </ul>

    <div class="mx-auto" style="max-width:600px;">
        <a href="{{ route('point_sail') }}" class="btn btn-lg btn-warning text-white rounded-pill shadow w-100"
        >ポイントを購入する</a>
    </div>
</div>
@endsection
