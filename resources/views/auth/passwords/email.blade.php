{{-- @extends('layouts.small') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','パスワード変更')

@section('script')
<script src="{{ asset('js/app.js') }}" defer></script>
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection

@section('content')
<div class="mx-auto" style="max-width:600px;">
    <div class="d-flex flex-column align-items-center justify-content-center mx-auto p-3 my-5">



        <u-reset-password-form
        token         = "{{ csrf_token() }}"
        step01_route  = "{{ route('reset_pass_step01') }}"
        step02_route  = "{{ route('reset_pass_step02') }}"
        login_route   = "{{ route('login') }}"
        ></u-reset-password-form >



    </div>
</div>
@endsection
