@extends('layouts.app')

<!----- title ----->
@section('title','メンテナンス中')
@section('meta')
    @php
        $meta_title = 'メンテナンス中';
    @endphp
@endsection


@section('content')

    <div class="container my-md-5">

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('maintenance.body')
        </section>

    </div>

@endsection

