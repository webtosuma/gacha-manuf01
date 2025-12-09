@extends('layouts.sub_toggl')

<!----- title ----->
@section('title','特定商取引法に基づく表記')
@section('meta')
    @php
        $meta_title = '特定商取引法に基づく表記';
    @endphp
@endsection


@section('style')
<style>
    #articleBody {
        font-size: 1.125rem !important;
        line-height: 2.25rem;
        margin-top: 0;
        margin-bottom: 1rem;
        margin-top: 36px;
        margin-bottom: 36px;
    }

</style>
@endsection


<!----- contents ----->
@section('content')

    <div class="container my-md-5" style="max-width: 900px">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            特定商取引法に基づく表記
        </h2>

        <!-- [ 本文 ] -->
        @include('footer_menu.common.db_body')


    </div>

@endsection

