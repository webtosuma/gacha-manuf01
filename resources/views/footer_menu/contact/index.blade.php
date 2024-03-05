{{-- @extends('layouts.app') --}}
@extends('layouts.sub')


<!----- title ----->
@section('title','お問い合わせ')
@section('meta')
    @php
        $meta_title = 'お問い合わせ';
    @endphp
@endsection

<!----- style ----->
@section('style')
<link href="{{ asset('css/steps.css') }}" rel="stylesheet">
@endsection


<!----- script ----->
@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


<!----- contents ----->
@section('content')
    <div class="container py-md-4 mb-5">
        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            お問い合わせ
        </h2>

        <section class="mb-5 col-md-8 mx-auto">

            <contact-form-component
            token="{{csrf_token()}}"
            r_api_validation="{{route('api.contact.validation')}}"
            r_api_completion="{{route('api.contact.completion')}}"
            r_privacy_policy="{{route('privacy_policy')}}"
            r_top="{{route('home')}}"
            ></contact-form-component>

        </section>


        <section class="alert p-4 border-0 shadow text-secondary mb-5 col-md-8 mx-auto" style="margin-top:8rem;">
            <h5 class="border-bottom border-secondary mb-3">お問い合わせフォームのメールが届かない場合</h5>
            <p>
                以下のメールが届かない場合は、
                <a href="https://note.com/cardfesta/n/ne1fe05bfbe31" target="_blank">こちら</a>をご確認ください。
                <ul>
                    <li>お問い合わせフォーム送信確認メールが届かない</li>
                    <li>お問い合わせの返信メールが届かない</li>
                </ul>
            </p>

            <a href="https://note.com/cardfesta/n/ne1fe05bfbe31" target="_blank">メールが届かない場合</a>はこちら

        </section>
    </div>
@endsection
