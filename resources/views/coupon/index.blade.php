{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','クーポン')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
            <li class="breadcrumb-item active" aria-current="page">クーポン</li>
            </ol>
        </nav>
    </div>





    <div class="container py-md-4 pb-5 mb-5">
        <h3 class="d-none d-md-block">クーポン</h3>

        <div class="row g-5 mt-3 mx-0">
            <div class="col-12 col-lg-4 order-lg-2">


                <form action="{{route('coupon.show')}}" class="card card-body bg-white h-100">
                    <h5 class="">クーポンコード入力</h5>
                    <div class="input-group">
                        <input name="code"
                        class="form-control form-control-lg" type="text" placeholder="クーポンコードを入力" >

                        <button class="btn btn-primary text-white">決定</button>
                    </div>
                    <p class="form-text">
                        クーポンコードをお持ちの方は、こちらの入力ボックスにクーポンコードを入力し、クーポンをご利用ください。
                    </p>
                </form>

            </div>
            <div class="col-12 col-lg">


                <h5>配布クーポン</h5>
                <div class="">
                    @forelse ($coupons as $coupon)
                        <hr class="m-">
                        <form action="{{route('coupon.show')}}">
                            <button
                            name="code"
                            value="{{$coupon->code}}"
                            class="btn bg-white w-100 text-start text-dark rounded-0 border-0">
                                <div class="row px-0 align-items-center g-0">
                                    <!--image-->
                                    <div class="col-4 col-md-3">
                                        <div class="position-relative">
                                            <div class="ratio {{config('app.gacha_card_ratio')}} bg-body rounded border
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
                                    <div class="col p-3" style="font-size:11px;">
                                        <div class="form-text">
                                            {{$coupon->published_at_format}}
                                            @if ($coupon->is_new)
                                                <span class="text-danger">NEW</span>
                                            @endif
                                        </div>
                                        <h6>{{$coupon->title}}</h6>

                                        <div class="">{{$coupon->discription_text}}</div>


                                        <!--利用回数-->
                                        <div class="col-md-6">
                                            @include('coupon.common.user_type')
                                        </div>

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
                                    {{-- <div class="col-0 col-md"></div> --}}
                                    <div class="col-auto fs-3">
                                        <i class="bi bi-chevron-right"></i>
                                    </div>

                                </div>
                            </button>
                        </form>
                    @empty
                        <div class="py-4">*ご利用できるクーポンはありません。</div>
                    @endforelse

                </div>
                <div class="">
                    <!-- ページネーション -->
                    <div class="d-flex justify-content-start  mt-3">
                        {{ $coupons->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
                    </div>
                </div>


            </div>
        </div>

    </div>
@endsection
