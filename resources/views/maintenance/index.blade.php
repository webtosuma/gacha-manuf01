@extends('layouts.app')

<!----- title ----->
@section('title','メンテナンス中')
@section('meta')
    @php
        $meta_title = 'メンテナンス中';
    @endphp
@endsection


@section('style')
    @php
        $bg_image = \App\Http\Controllers\AdminBackGroundController::getBgSub();
    @endphp
    <style>
        /* サイトデフォルト背景 */
        #bgWindow{
            background-image: url({{ $bg_image }});
        }
    </style>
@endsection



@section('content')

    <div class="container my-md-5">

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('maintenance.body')
        </section>

    </div>

@endsection

