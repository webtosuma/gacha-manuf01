@extends('layouts.small')

<!----- title ----->
@section('title','パスワード変更')

@section('script')
<script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5"
style="min-height: 80vh; max-width:600px;">



    <u-reset-password-form
    token         = "{{ csrf_token() }}"
    step01_route  = "{{ route('reset_pass_step01') }}"
    step02_route  = "{{ route('reset_pass_step02') }}"
    login_route   = "{{ route('login') }}"
    ></u-reset-password-form >



</div>
@endsection
