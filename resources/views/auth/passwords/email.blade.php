@extends('layouts.small')
{{-- @extends('layouts.sub') //利用禁止！！ --}}

<!----- title ----->
@section('title','パスワード変更')


@section('meta')
    @php $header_back_btn = true; @endphp
@endsection


@section('script')
<script src="{{ asset('js/app.js') }}" defer></script>
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')
<div class="mx-auto" style="max-width:600px;">
    <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5">


        <h2 class="h3 mb-3 fw-bold">パスワード変更</h2>


        <u-reset-password-form
        token         = "{{ csrf_token() }}"
        step01_route  = "{{ route('reset_pass_step01') }}"
        step02_route  = "{{ route('reset_pass_step02') }}"
        login_route   = "{{ route('login') }}"
        ></u-reset-password-form >

        {{-- <form action="{{route('register.post')}}" method="POST"
        novalidate class="w-100"
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf

            <u-register-form
            token           ="{{ csrf_token() }}"
            r_api_step01    ="{{ route('api.register.step01') }}"
            r_register_post ="{{ route('register.post') }}"
            r_trems         ="{{ route('trems') }}"
            r_privacy_policy="{{ route('privacy_policy') }}"

            login_route          = "{{ route('login') }}"
            gacha_route          = "{{ route('home') }}"
            point_sail_route     = "{{ route('point_sail') }}"
            ></u-register-form >

        </form> --}}

        <section class="alert  p-2 border-0 shadow text-secondary mb-5 w-100" style="margin-top:3rem;">
            <a href="https://note.com/cardfesta/n/ne1fe05bfbe31" target="_blank">メールが届かない場合</a>はこちら
        </section>

    </div>
</div>
@endsection
