{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','利用ガイド')
@section('meta')
    @php
        $meta_title = '利用ガイド';
    @endphp
@endsection


@section('content')

    <div class="container my-md-5">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            利用ガイド
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('footer_menu.guide.'.'body')
        </section>

    </div>

@endsection

