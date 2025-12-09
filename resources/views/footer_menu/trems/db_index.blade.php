@extends('layouts.sub_toggl')


<!----- title ----->
@section('title','利用規約')
@section('meta')
    @php
        $meta_title = '利用規約';
    @endphp
@endsection



<!----- contents ----->
@section('content')

    <div class="container my-md-5">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            利用規約
        </h2>

        <!-- [ 本文 ] -->
        @include('footer_menu.common.db_body')


    </div>

@endsection
