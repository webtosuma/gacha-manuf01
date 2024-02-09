@extends('admin.layouts.app')


@section('title',$infomation->title)


@section('meta') @php
$active_key = 'infomation';
$active_submenu = true;
@endphp @endsection



@section('style')
    @include('emails._html_css')
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.infomation') }}"
                >{{ 'お知らせ' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.infomation.show',$infomation) }}"
                >{{ $infomation->title }}</a></li>

                <li class="breadcrumb-item active" aria-current="page"
                >{{ 'メール送信' }}</li>
            </ol>
        </nav>

        <a href="{{route('admin.infomation.edit',$infomation)}}"
        class="btn btn-warning text-white my-3"
        >編集する</a>


        <div class="row mx-0 mt-3 g-3">
            <!--flex-c2-->
            <div class="col bg-white bg_gacha rounded-3">

                <!--プレビュー-->
                <div class="mx-auto" style="max-width:600px;">
                    <h5 class="text-center">プレビュー</h5>

                    <div class="card p-3">
                        @include('emails.infomation.body')
                    </div>
                </div>

            </div>
            <!--flex-c1-->
            <aside class="d-none d-lg-block col-4 ">
                <div class="position-sticky" style="top: 2rem; ">

                    <div class="mb-3">
                        <!--メール送信日-->
                        <i class="bi bi-envelope"></i>
                        {{ $infomation->send_email_at? $infomation->send_email_at->format('Y.m.d') : '----.--.--' }}
                        {{ $infomation->send_email_at? '送信済み' : '未送信' }}

                    </div>


                    <form action="{{route('admin.infomation.email.post',$infomation)}}" method="POST">
                        @csrf

                        <div class="mb-5">
                            <h3>この内容で一括送信します。</h3>
                            <h3>よろしいですか？</h3>
                        </div>

                        <disabled-button style_class="btn btn-success text-white w-100 shadow"
                        btn_text="メールを送信する"></bdisabled-button>

                    </form>


                </div>
            </aside>
        </div>


    </div>
@endsection
