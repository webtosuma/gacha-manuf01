@extends('layouts.small')

<!----- title ----->
@section('title','会員登録')

@section('script')
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script src="{{ asset('js/app.register.js') }}" defer></script>

    <!-- フォームのページ離脱防止アラート -->
    <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection

@section('content')

    {{-- @include('auth.register_inputs') --}}

    <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
    style="min-height: 80vh; max-width:600px;">


        <h2 class="h3 mb-3 fw-bold">会員登録</h2>

        <form action="{{route('register.post')}}" method="POST"
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

        </form>


    </div>
@endsection
