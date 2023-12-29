@extends('layouts.app')


<!----- title ----->
@section('title','特定商取引法に基づく表記')
@section('meta')
    @php
        $meta_title = '特定商取引法に基づく表記';
    @endphp
@endsection



@section('content')

    <div class="container my-5">

        <!-- [ 見出し ] -->
        <h2 class="text-center my-3">
            特定商取引法に基づく表記
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5 mx-auto" style="max-width:900px;">
            @include('footer_menu.tradelaw.'.'body')
        </section>

    </div>

@endsection

