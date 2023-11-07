@extends('layouts.600_simple_base')


<!----- title ----->
@section('title','ログインが必要です')

<!----- meta ----->
@section('meta') @endsection

<!----- style ----->
@section('style') @endsection

<!----- script ----->
@section('script') @endsection


<!----- contents ----->
@section('contents')

    <section class="my-5">
        <div class="card card-body text-secondary mb-5">
            <h5 class="fw-bold">こちらのページはログイン後にご利用いただけます。</h5>
            <p>会員登録がお済みでない方は、
                <strong class="text-primary">会員登録（無料）</strong>
                を行ってください。</p>
        </div>
        <div class="row mb-3">
            <div class="col-sm-8 offset-sm-2">
                <a href="{{ route('register') }}" class="btn btn-lg btn-arrow btn-curve btn-primary text-white w-100">
                    {{ __('会員登録（無料）') }}
                </a>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-8 offset-sm-2">
                <a href="{{ route('login') }}" class="btn btn-lg btn-arrow btn-curve btn-outline-primary w-100">
                    {{ __('ログイン') }}
                </a>
            </div>
        </div>
    </section>


    {{-- <section class="d-flex justify-content-center align-items-center" style="min-height: 90vh">
        <div class="">

        </div>
    </section>
 --}}


@endsection
