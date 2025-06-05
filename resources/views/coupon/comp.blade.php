@extends('layouts.app')

<!----- title ----->
@section('title',$coupon->title.' 交換完了')


@section('meta')
    @php
        $meta_title = $coupon->title.' 交換完了';
        $meta_image = $bg_image;
    @endphp
@endsection


@section('style')
    <style>
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
    </style>
@endsection


@section('content')
    <section id="result" style="padding-top:3rem;">
        <div class="container px-3 py-4"  style="max-width:500px;">


            <h2 class="p- mb-3 fs-6">
                <div class="rounded-3 p-3 text-light" style="background: rgb(0, 0, 0, .7);">

                    <div class="mb-2 text-center" style="font-size:10px;">
                        <div class="">{{$coupon_history->created_at->format('Y/m/d H:i')}}</div>
                    </div>


                    <div class="mb-3" style="font-size:.8rem;">
                        <div class="fs-5 text-center">{{$message}}</div>
                    </div>
                </div>
            </h2>



            <u-coupon-comp
            ratio            ="{{ $coupon->prize ? 'ratio-3x4' : 'ratio-1x1' }}"
            coupon_image_path="{{ $coupon->image_path }}"
            box_image_path   ="{{asset( 'storage/site/image/coupon_result/box.png' )}}"
            finger_image_path="{{asset( 'storage/site/image/coupon_result/finger.png' )}}"
            prop_show="{{$is_history?1:0}}"
            ></u-coupon-comp>



            <div class="col-md-8 mx-auto my-4">
                <a href="{{ route('home') }}"
                class="btn btn-lg btn-dark border rounded-pill w-100"
                >TOPに戻る</a>
            </div>

        </div>
    </section>
@endsection
