@extends('layouts.app')

<!--title-->
@section('title','401 error')


@section('content')
    <section class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
        <div class="text-center my-5">
            <h3 class="fs-1 textsecondary mb-5">401 Unauthorized</h3>

            <h5>アクセスしようとしたページは<br>表示できませんでした。</h5>
            <div>認証の為のユーザー名やパスワードなどが間違っている可能性があります。もう一度ご確認下さい。</div>

            <div class="mt-3">
                <a href="#" onClick="history.back(); return false;">>戻る</a>
            </div>
        </div>
    </section>
@endsection

