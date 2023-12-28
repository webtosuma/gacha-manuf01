@extends('layouts.app')

<!--title-->
@section('title','500 error')


@section('content')
    <section class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
        <div class="text-center my-5">
            <h3 class="fs-1 textsecondary mb-5">500 Internal Server Error</h3>

            <h5>技術的な問題が発生し、ページを表示することができません。</h5>
            <div>ページを表示することができません。</div>

            <div class="mt-3">
                <a href="#" onClick="history.back(); return false;">>戻る</a>
            </div>
        </div>
    </section>
@endsection



