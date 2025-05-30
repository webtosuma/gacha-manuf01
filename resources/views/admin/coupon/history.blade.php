@extends('admin.layouts.app')


@section('title','クーポン履歴')


@section('meta') @php
$active_key = 'coupon';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.coupon') }}"
                >{{ 'クーポン管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">クーポン履歴</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">クーポン履歴</h2>


        <ul class="list-group list-group-flush">
            @forelse ($coupon_histories as $coupon_history)
                <li class="list-group-item bg-white py-3"><div class="row align-items-center">
                    <!--image-->
                    <div class="col-auto" style="width: 6rem;">
                        <div class="position-relative">
                            <div class="ratio {{config('app.gacha_card_ratio')}} bg-body rounded border
                            d-flex align-items-center justify-content-center"></div>

                            <div class="w-50 position-absolute top-50 start-50 translate-middle">

                                <ratio-image-component
                                url="{{$coupon_history->image_path}}"
                                style_class="ratio
                                @if( $coupon_history->user_prize ) ratio-3x4 @else ratio-1x1 @endif"
                                ></ratio-image-component>

                            </div>
                        </div>
                    </div>
                    <!--body-->
                    <div class="col">
                        <div class="form-text">{{$coupon_history->created_at_format}}</div>
                        <div class="fw-bold">{{$coupon_history->coupon->title}}</div>
                        <div class="">{{$coupon_history->discription_text}}</div>

                        @php $user = $coupon_history->user; @endphp
                        <a href="{{route('admin.user.show',$user)}}" class="d-block">
                            <div class="d-flex gap-2">
                                <span>ID:{{$user->id}}</span>
                                <span>{{$user->name}}</span>
                            </div>
                        </a>

                    </div>
                </div></li>
            @empty
                <li class="list-group-item bg-white py-3 fw-bold">クーポンの利用はありません。</li>
            @endforelse

        </ul>
        <div class="">
            <!-- ページネーション -->
            <div class="d-flex justify-content-start  mt-3">
                {{ $coupon_histories->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
            </div>
        </div>

    </div>
@endsection
