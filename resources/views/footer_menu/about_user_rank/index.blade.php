{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','会員ランク制度のご案内')
@section('meta')
    @php
        $meta_title = '会員ランク制度のご案内';
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


@section('content')

    <div class="container my-md-5" style="max-width: 900px">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            会員ランク制度のご案内
        </h2>

        <!-- [ 本文 ] -->
        <section id="articleBody" class="my-5">


            @include('footer_menu.about_user_rank.'.'body')


        </section>

    </div>

@endsection

