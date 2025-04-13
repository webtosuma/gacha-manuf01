@extends('layouts.app')

<!----- title ----->
@section('title','会員登録')

@section('script') @endsection



@section('content')

{{--- 紙吹雪　CDN ---}}
@include('includes.confetti_js')

<div class="container my-md-5">
    <div class="d-flex align-items-center justify-content-center bg-" style="height: 80vh;">


        <div class="text-center">
            <h5 class="fw-bold fs-4 mb-5">会員登録が完了しました。</h5>


            <a href="{{ route('settings.acount') }}" class="d-block mb-5 text-dark">
                <div class="d-flex flex-column align-items-senter gap-3 ">
                    <div class="mx-auto" style="width: 6rem;">
                        <ratio-image-component
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="ユーザーメニュー"
                        style_class="ratio ratio-1x1 rounded-pill border bg-light"
                        url="{{ Auth::user()->image_path }}"
                        ></ratio-image-component>
                    </div>

                    <div class="fs-5">{{ Auth::user()->name }}さん</div>

                    @if( Auth::user()->twitter_id )
                        <div class="form-text">
                            {{-- X(旧twitter)ID： --}}
                            <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
                            alt="xロゴ" class="d-inline-block" style="height:1rem;">


                            {{ Auth::user()->twitter_id }}
                        </div>
                    @endif
                </div>
            </a>


            <a href="{{route('home')}}" class="btn btn-light border rounded-pill">トップページに戻る</a>
        </div>


    </div>
</div>


@endsection
