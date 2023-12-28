@extends('layouts.app')

<!--title-->
@section('title','404 error')


@section('content')
    <section class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
        <div class="text-center my-5">
            <h3 class="fs-1 textsecondary mb-5">404 Not Found</h3>

            <h5>お探しのページが<br>見つかりませんでした。</h5>
            <div>URLが間違っている可能性があります。もう一度ご確認下さい。</div>

            <div class="mt-3">
                <a href="#" onClick="history.back(); return false;">>戻る</a>
            </div>
        </div>
    </section>
@endsection


