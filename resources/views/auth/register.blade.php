@extends('layouts.small')

<!----- title ----->
@section('title','会員登録')

@section('script')
<script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
style="min-height: 80vh; max-width:600px;">



    <u-register-form
    token                = "{{ csrf_token() }}"
    submit_route         = "{{ route('register') }}"

    trems_route          = "{{ route('trems') }}"
    privacy_policy_route = "{{ route('privacy_policy') }}"
    login_route          = "{{ route('login') }}"
    gacha_route          = "{{ route('home') }}"
    point_sail_route     = "{{ route('point_sail') }}"
    ></u-register-form >



</div>
@endsection
