@extends('layouts.app')


<!----- title ----->
@section('title','お問い合わせ')

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
    <div class="container py-4 mb-5">
        <h3 class="mb-">お問い合わせ</h3>

        <section class="mb-5">

            <contact-form-component
            token="{{csrf_token()}}"
            r_api_validation="{{route('api.contact.validation')}}"
            r_api_completion="{{route('api.contact.completion')}}"
            r_privacy_policy="{{route('privacy_policy')}}"
            r_top="{{route('home')}}"
            ></contact-form-component>


        </section>
    </div>
@endsection
