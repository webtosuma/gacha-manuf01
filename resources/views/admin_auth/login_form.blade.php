@extends('layouts.small')

<!----- title ----->
@section('title','管理者ログイン')

@section('meta')
@php
$email    = config('app.debug') ? 'na@tosuma.ltd' : '';
$password = config('app.debug') ? 'password' : '';
@endphp
@endsection



@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3"
    style="min-height: 80vh; max-width:600px;">

        <form method="POST" action="{{ route('admin_auth.login') }}" class="w-100 text-center">
            @csrf
            <h2 class="h3 mb-3 fw-normal">サイト管理者ログイン</h2>

            @if (session('login_error'))
                <div class="text-danger mb-3 text-center">※{{ session('login_error') }}</div>
            @endif

            <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" autofocus
            name="email"
            value="{{ session('email') ? session('email') : $email }}">
            <label for="floatingInput">メールアドレス</label>
            </div>
            <div class="form-floating mb-3">
            <input type="password" class="form-control" id="floatingPassword"
            name="password"
            value="{{ session('password') ? session('password') : $password }}">
            <label for="floatingPassword">パスワード</label>
            </div>

            <div class="col-md- mx-auto mb-3">
                <button class="w-100 btn btn-lg btn-dark" type="submit">ログイン</button>
            </div>
            <a href="" class="text-decoration-none"
            >パスワードをお忘れの方はこちら</a>
        </form>

        <hr class="my-4 w-100">
        <div class="text-center w-100">
            <small class="text-body-secondary">ユーザーログインはこちら</small>
            <a href="{{ route('login') }}"
            class="w-100 py-2 mb-2 btn btn-primary text-white rounded-3"
            >ユーザーログイン</a>
        </div>

    </div>
@endsection
