@extends('layouts.app')

<!----- title ----->
@section('title','ポイント購入完了')


@section('script')
    {{--- 紙吹雪　CDN ---}}
    @include('includes.confetti_js')
@endsection


@section('content')
<!--breadcrumb-->
<div class="container mt-md-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
          <li class="breadcrumb-item active" aria-current="page">ポイント購入完了</li>
        </ol>
    </nav>
</div>


<div class="container py-md-4 mb-5">
    <h3 class="text-center my-3">ポイント購入が完了しました</h3>

    <div class="form-text borderrrr border-danger p-3 mx-auto mb-3" style="max-width:600px;">
        通信が混み合っている場合、決済サービスとの通信が遅れる場合があり、ポイントの反映が<strong>1分程</strong>、遅れる場合があります。
    </div>



    <ul class="list-group list-group-flush mb-5 mx-auto" style="max-width:600px;">
        <li class="list-group-item bg-white py-">
            <div class="row gy-3 my-3 mx-auto" style="max-width:400px;">
                <div class="col-6">ポイント数</div>
                <div class="col-6 border-bottom fw-bold text-center">
                    <div class="fs-5">{{ number_format($point_sail->value * $rank_ratio).'pt' }}</div>
                </div>

                {{-- @if( isset($source) )
                    <div class="col-6">支払い方法</div>
                    <div class="col-6 border-bottom fw-bold text-center">{{ $source }}</div>
                @endif --}}


                <div class="col-6">サービス</div>
                <div class="col-6 border-bottom fw-bold text-center">

                    {{-- 会員ランク還元 --}}
                    @if( $rank_ratio > 1 )
                    <div class="badge border border-danger rounded-pill fw-bold px-3 mb-1">
                        <span class="text-danger fw-bold fs-">{{ $point_sail->value*($rank_ratio-1) }}</span>
                        <span class="text-danger fw-bold">pt 還元！</span>
                    </div>
                    @endif


                    {{-- チケット還元 --}}
                    @if( !env('NEW_TICKET_SISTEM_NOTICKET') && $point_sail->ticket > 0 )
                    <div class="badge border border-success rounded-pill fw-bold px-3 my-1">
                        <span class="text-success fw-bold">チケット</span>
                        <span class="text-success fw-bold fs-">{{ $point_sail->ticket }}</span>
                        <span class="text-success fw-bold">枚 プレゼント！</span>
                    </div>
                    @endif

                </div>


                <div class="col-6">支払い金額</div>
                <div class="col-6 border-bottom fw-bold text-center">{{ number_format($point_sail->price).'円（税込）' }}</div>

            </div>


            <div class="row gy-3 my-3 mx-auto mt-4 align-items-end" style="max-width:400px;">
                <div class="col-6">現在の所有ポイント</div>
                <div class="col-6 border-bottom fw-bold text-center fs-5 text-warning">{{number_format( Auth::user()->point ).'pt'}}</div>
            </div>
        </li>
    </ul>




    @if( $before_gacha)
        <div class="mb- py-5 mx-auto" style="max-width:400px;">

            <h5 class="fw-bold text-center mb-3">前回のガチャを続ける</h5>

            @php $gacha = $before_gacha; @endphp
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
        {{-- <div class="mx-auto mb-5" style="max-width:400px;">
            <a href="{{ route('gacha_category') }}" class="btn btn-lg btn-primary text-white rounded-pill shadow w-100"
            >他のガチャを見る</a>
        </div>
    @else
        <div class="mx-auto mb-5" style="max-width:400px;">
            <a href="{{ route('gacha_category') }}" class="btn btn-lg btn-primary text-white rounded-pill shadow w-100"
            >ガチャを見る</a>
        </div> --}}
    @endif


    <!--おすすめガチャ-->
    <div class="mb- py-5 mx-auto" style="max-width:400px;">

        @include('gacha.common.result_gachas')

    </div>


    {{-- <div class="mb- py-5 mx-auto" style="max-width:400px;">

        <h5 class="fw-bold text-center mb-5">おすすめガチャ</h5>


        @foreach ($gachas as $num => $gacha)
            @if( $num < 3 )

                <a href="{{ $gacha->route }}"
                class="card border-secondary border-0 shadow bg-white mb-5
                text-dark text-center overflow-hidden text-decoration-none
                hover_anime" style="border-radius:1rem;">


                    <!--image-->
                    @include('gacha.common.top_image')

                    <!--metter-->
                    @include('gacha.common.metter')

                </a>

            @endif
        @endforeach

    </div> --}}


</div>
@endsection
