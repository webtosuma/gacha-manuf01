@extends('layouts.app')

<!----- title ----->
@section('title','誕生日の登録が完了しました')

@section('script') @endsection



@section('content')

{{--- 紙吹雪　CDN ---}}
@include('includes.confetti_js')

<div class="container my-md-5">
    <div class="d-flex align-items-center justify-content-center bg-" style="height: 80vh;">


        <div class="text-center">
            <h5 class="fw-bold fs-4 mb-5">誕生日の登録が完了しました。</h5>


            <div class="d-block mb-5 text-dark">
                <div class="d-flex flex-column align-items-senter gap-3 ">
                    <a href="{{ route('settings.acount') }}"
                    class="d-block mx-auto" style="width: 6rem;">
                        <ratio-image-component
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                        style_class="ratio ratio-1x1 rounded-pill border bg-light"
                        url="{{ Auth::user()->image_path }}"
                        ></ratio-image-component>
                    </a>

                    <div class="fs-5">{{ Auth::user()->name }}さん</div>

                    <div class="fs-5">{{ Auth::user()->age }}歳</div>


                </div>
            </div>



            <div class="d-flex flex-column align-items-senter gap-3 ">

                <a href="{{route('point_sail')}}" class="btn btn-warning rounded-pill"
                >ポイント購入</a>

                <a href="{{route('home')}}" class="btn btn-light border rounded-pill"
                >トップページに戻る</a>

            </div>




        </div>


    </div>
</div>


@endsection
