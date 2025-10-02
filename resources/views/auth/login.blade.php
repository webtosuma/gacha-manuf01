@extends('layouts.simple')
{{-- @extends('layouts.small') --}}

<!----- title ----->
@section('title','ログイン')

<!----- meta ----->
@section('meta') @endsection

<!----- style ----->
@section('style') @endsection

<!----- script ----->
@section('script') @endsection


@section('content')


<div class="container">

    <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
    style="min-height: 80vh;">

        <div class="row justify-content-center gy-5 w-100">

            <!-- 通常ログイン -->
            <div class="col-12 col-md-6">


                <div class="w-100 text-center">

                    <h2 class="h3 mb-3 fw-bold">ログイン</h2>

                    @if (session('login_error'))
                        <div class="text-danger mb-3 text-center">※{{ session('login_error') }}</div>
                    @endif

                    <login-form-tfa
                    token     ="{{ csrf_token() }}"
                    email     ="{{ session('email') ? session('email') : $email }}"
                    password  ="{{ session('password') ? session('password') : $password }}"
                    r_api_pass   ="{{ route('auth.api.login.password') }}"
                    r_api_tfa_key="{{ route('auth.api.login.tfa_key') }}"
                    r_pass_request="{{route('password.request')}}"
                    r_action="{{ route('login') }}"
                    ></login-form-tfa>



                    <div class="text-center w-100">

                        <hr class="my-4 w-100">

                        <small class="text-body-secondary">新規登録はこちら</small>

                        <a href="{{ route('register') }}"
                        class="w-100 py-2 mb-2 btn btn-outline-secondary rounded-3"
                        >会員登録</a>

                    </div>


                </div>

            </div>
            @if(config('services.google.client_id'))
                <div class="col">

                    <!-- SNSログイン -->
                    @include('auth.login_sns_section')

                </div>
            @endif



        </div>
    </div>


</div>


@endsection
