@extends('layouts.app')

<!--title-->
@section('title','403 error')


@section('content')
    <section class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
        <div class="text-center my-5">
            <h3 class="fs-1 textsecondary mb-5">403 Access Not Granted</h3>

            <h5>指定されたページへのアクセスは<br>禁止されています。</h5>
            <div>URLをもう一度ご確認ください。</div>

            <div class="mt-3">
                <a href="#" onClick="history.back(); return false;">>戻る</a>
            </div>
        </div>
    </section>
@endsection
