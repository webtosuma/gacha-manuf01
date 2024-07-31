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


        <section class="bg-whiteee p-4 border-0 shadow text-secondaryyy mb-5 col-md-8 mx-auto" style="margin-top:8rem;">

            <div class="mb-5">
                <h5 class="border-bottom border-secondary mb-3">お問い合わせのご返信について</h5>
                <p>
                    お問い合わせ受付のご返信につきましては、当日または2日〜3日以内にさせていただいております。<br>

                    また、お問い合わせの内容によっては、お返事にお時間をいただく場合やお返事を差し上げられない場合がございます。<br>
                    あらかじめご了承ください。
                </p>
            </div>

            <div class="mb-">
                <h5 class="border-bottom border-secondary mb-3">お問い合わせフォームのメールが届かない場合</h5>
                <p>
                    以下のメールが届かない場合は、
                    <a href="{{route('not_receiving_email')}}" target="_blank">こちら</a>をご確認ください。
                    <ul>
                        <li>お問い合わせフォーム送信確認メールが届かない</li>
                        <li>お問い合わせの返信メールが届かない</li>
                    </ul>
                </p>

                <a href="{{route('not_receiving_email')}}" target="_blank">メールが届かない場合</a>はこちら
            </div>
        </section>
    </div>
@endsection
