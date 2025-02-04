@extends('layouts.sub')


<!----- title ----->
@section('title','メールが届かない場合')
@section('meta')
    @php
        $meta_title = 'メールが届かない場合';
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
            メールが届かない場合
        </h2>

        <!-- [ 本文 ] -->
        <section id="articleBody" class="my-5">
            @include('footer_menu.not_receiving_email.'.$revision_date)
        </section>


    </div>

@endsection
