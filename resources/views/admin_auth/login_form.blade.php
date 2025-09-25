{{-- @extends('layouts.small') --}}
@extends('layouts.simple')

<!----- title ----->
@section('title','管理者ログイン')


@section('content')
<div class="container">

    <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
    style="min-height: 80vh;">

        <div class="row justify-content-center gy-5 w-100">

            <!-- 通常ログイン -->
            <div class="col-12 col-md-6 text-center">


                {{-- <form method="POST" action="{{ route('admin_auth.login') }}" class="w-100 text-center"> --}}
                    @csrf

                    <h2 class="h3 mb-3 fw-normal">サイト管理者ログイン</h2>

                    @if (session('login_error'))
                        <div class="text-danger mb-3 text-center">※{{ session('login_error') }}</div>
                    @endif

                    <login-form-tfa
                    token     ="{{ csrf_token() }}"
                    email     ="{{ session('email') ? session('email') : $email }}"
                    password  ="{{ session('password') ? session('password') : $password }}"
                    is_admin  ="1"
                    r_api_pass    ="{{ route('auth.api.login.password') }}"
                    r_api_tfa_key ="{{ route('auth.api.login.tfa_key') }}"
                    r_pass_request="{{route('password.request')}}"
                    r_action="{{ route('admin_auth.login') }}"
                    ></login-form-tfa>

                {{-- </form> --}}


                <div class="text-center w-100">

                    <hr class="my-4 w-100">

                    <small class="text-body-secondary">ユーザーログインはこちら</small>
                    <a href="{{ route('login') }}"
                    class="w-100 py-2 mb-2 btn btn-outline-info text- rounded-3"
                    >ユーザーログイン</a>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
