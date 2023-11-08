@extends('layouts.app')

<!----- title ----->
@section('title','ログイン')

<!----- meta ----->
@section('meta') @endsection

<!----- style ----->
@section('style') @endsection

<!----- script ----->
@section('script') @endsection


@section('content')

<div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
style="min-height: 80vh; max-width:600px;">

    <form method="POST" action="{{ route('login') }}" class="w-100 text-center">
        @csrf
        <h2 class="h3 mb-3 fw-bold">ログイン</h2>

        @if (session('login_error'))
            <div class="text-danger mb-3 text-center">※{{ session('login_error') }}</div>
        @endif

        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="floatingInput" autofocus
          name="email"
          value="{{ session('email') ? session('email') : '' }}">
          <label for="floatingInput">メールアドレス</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="floatingPassword"
          name="password"
          value="{{ session('password') ? session('password') : '' }}">
          <label for="floatingPassword">パスワード</label>
        </div>

        <div class="col-md- mx-auto mt-5 my-3">
            <button class="w-100 btn btn-lg btn-primary" type="submit">ログイン</button>
        </div>
        <a href="{{route('password.request')}}" class="text-decoration-none"
        >パスワードをお忘れの方はこちら</a>
    </form>


    <hr class="my-4 w-100">
    <div class="text-center w-100">
        <small class="text-body-secondary">新規登録はこちら</small>
        <a href="{{ route('register') }}"
        class="w-100 py-2 mb-2 btn btn-outline-primary rounded-3"
        >会員登録</a>
    </div>
</div>
@endsection
