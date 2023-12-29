@extends('layouts.app')


<!----- title ----->
@section('title','利用ガイド')
@section('meta')
    @php
        $meta_title = '利用ガイド';
    @endphp
@endsection


@section('content')

    <div class="container my-5">

        <!-- [ 見出し ] -->
        <h2 class="text-center my-3">
            利用ガイド
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('footer_menu.guide.'.'body')
        </section>

    </div>

@endsection

