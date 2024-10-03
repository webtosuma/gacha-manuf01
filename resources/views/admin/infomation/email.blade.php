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


        <div class="row mx-0 mt-3 g-3 gy-5">
            <!--flex-c2-->
            <div class="col rounded-3">

                <!--プレビュー-->
                <div class="mx-auto">
                    <h5 class="text-center">プレビュー</h5>

                    <div class="card p-3 bg-white">
                        @include('emails.infomation.body')
                    </div>
                </div>

            </div>
            <!--flex-c1-->
            <aside class="col-12 col-lg-6 ">
                <div class="position-sticky" style="top: 2rem; ">

                    <div class="mb-3">
                        <!--メール送信日-->
                        <i class="bi bi-envelope"></i>
                        {{ $infomation->send_email_at? $infomation->send_email_at->format('Y.m.d') : '----.--.--' }}
                        {{ $infomation->send_email_at? '送信完了' : '未完了' }}

                    </div>


                    <div class="mb-5">
                        <h3>この内容で一括送信します。</h3>
                        <h3>よろしいですか？</h3>
                    </div>



                    <div class="col-md-8 mx-auto">
                        @if(!$infomation->send_email_at)
                            <a-infomation-sendemail
                            r_api_email_post="{{route('admin.api.infomation.email_post',$infomation).'?page=1'}}"
                            r_redirect=      "{{route('admin.infomation.comp_email_post',$infomation)}}"
                            ></a-infomation-sendemail>
                        @else
                            <button class="btn btn-secondary text-white w-100 shadow"
                            type="button" disabled
                            >送信済み</button>
                        @endif
                    </div>


                    <div class="card p-2 mt-5 overflow-auto" style="max-height: 50vh;">
                        <h5>送信履歴 {{$sent_emails->total()}}件</h5>
                        @foreach ($sent_emails as $sent_email)
                        <div class="row g-2 mx-0">
                            <div class="col-auto">
                                {{$sent_email->created_at->format('y/m/d H:i:s')}}
                            </div>
                            <div class="col-auto">id: {{$sent_email->user->id}}</div>
                            <div class="col">
                                <div class="d-none d-md-block">{{$sent_email->user->name}}</div>
                                <div class="d-md-none">
                                    {{ mb_strlen($sent_email->user->name) <= 10 ? $sent_email->user->name : mb_substr($sent_email->user->name,0,10).'...' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- ページネーション -->
                    <div class="d-flex justify-content-start mt-3">
                        {{ $sent_emails->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
            </aside>
        </div>


    </div>
@endsection
