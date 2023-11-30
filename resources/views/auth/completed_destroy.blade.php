@extends('layouts.small')

<!----- title ----->
@section('title','パスワード変更')

@section('script')
<script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
>



    <div class="card card-body text-secondary mb-5 w-100">

        <h5 class="fw-bold">退会処理を完了しました</h5>
        <p class="mb-5">
            ご利用いただき、ありがとうございました。
        </p>

        <div class="row mb-3">
            <div class="col-sm-8 offset-sm-2">
                <a href="{{ route('home') }}" class="btn btn-primary text-white rounded-pill w-100" style="text-decoration:none;">
                    {{ __('トップに戻る') }}
                </a>
            </div>
        </div>

    </div>



</div>
@endsection
