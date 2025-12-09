@extends('layouts.sub_toggl')


<!----- title ----->
@section('title','プライバシーポリシー')
@section('meta')
    @php
        $meta_title = 'プライバシーポリシー';
    @endphp
@endsection


<!----- contents ----->
@section('content')

    <div class="container my-md-5">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            プライバシーポリシー
        </h2>

        <!-- [ 本文 ] -->
        @include('footer_menu.common.db_body')


    </div>

@endsection
