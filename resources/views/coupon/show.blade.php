{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title',$coupon->title)


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('coupon') }}">クーポン</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$coupon->title}}</li>
            </ol>
        </nav>
    </div>





    <div class="container py-md-4 pb-5 mb-5">
        {{-- <h3 class="d-none d-md-block">クーポン</h3> --}}

        <div class="col-md-6 col-lg-4 mx-auto">
            <div class="row g-4 mt-3">
                <!--image-->
                <div class="col-12">
                    <div class="position-relative">
                        <div class="ratio {{config('app.gacha_card_ratio')}} bg-body rounded-4 border
                        d-flex align-items-center justify-content-center"></div>

                        <div class="w-50 position-absolute top-50 start-50 translate-middle">

                            <ratio-image-component
                            url="{{$coupon->image_path}}"
                            style_class="ratio
                            @if( $coupon->prize ) ratio-3x4 @else ratio-1x1 @endif"
                            ></ratio-image-component>

                        </div>
                    </div>
                </div>
                <!--body-->
                <div class="col-12">
                    <div class="rounded-4 bg-white p-3">
                        <h5>{{$coupon->title}}</h5>
                        <div class="">{{$coupon->code}}</div>
                        <div class="">{{$coupon->discription_text}}</div>


                        <!--利用回数-->
                        @include('coupon.common.user_type')


                        <!--有効期限-->
                        <div class="d-flex flex-column gap-0" style="font-size:8px;">
                            @if ($coupon->expiration_at_format)
                                <span class="text-secondary">有効期限：{{$coupon->expiration_at_format}}まで</span>
                            @endif
                            @if ($coupon->is_expiration_done)
                                <span class="text-danger">有効期限切れ</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12">

                    <form action="{{route('coupon.used')}}" method="POST"
                    class="d-flex flex-column gap-3">
                        @csrf
                        @method('PATCH')
                        <button
                        name="code"
                        value="{{$coupon->code}}"
                        class="btn btn-lg btn-primary text-white rounded-pill w-100">このクーポンを使う</button>
                        <a href="{{route('coupon')}}" class="btn btn-lg btn-light border rounded-pill w-100">クーポン一覧に戻る</a>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
