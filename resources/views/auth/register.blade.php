@extends('layouts.small')

<!----- title ----->
@section('title','会員登録')

@section('script')
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/app.register.js') }}" defer></script> --}}

    <!-- フォームのページ離脱防止アラート -->
    <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection

@section('content')

    {{-- @include('auth.register_inputs') --}}

    <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
    style="min-height: 80vh; max-width:600px;">


        <h2 class="h3 mb-3 fw-bold">会員登録</h2>


        {{-- @if ( !$ip_check ) --}}

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


            <section class="alert p-2 border-0 text-secondary mb-5 w-100" style="margin-top:3rem;">

                <a href="{{route('not_receiving_email')}}" target="_blank">メールが届かない場合</a>はこちら

            </section>

        {{-- @else

            <div class="card shadow border-0 w-100 p-3 mb-3 bg-white">
                <div class="card-body">

                    <div class="card-body">
                        <h5 class="card-title fw-bold  text-center mb-3">警告</h5>
                        <p class="">
                            一人のユーザーが、アカウントを複数作成することはできません。
                        </p>
                    </div>
                </div>
            </div>

        @endif --}}

    </div>
@endsection
