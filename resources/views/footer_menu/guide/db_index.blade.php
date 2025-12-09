{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','利用ガイド')
@section('meta')
    @php
        $meta_title = '利用ガイド';
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

    <div class="container my-md-5" style="max-width: 940px">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            利用ガイド
        </h2>

        <!-- [ 本文 ] -->
        <section id="articleBody" class="my-5">

            <replace-text-component text="{{ $body }}"></replace-text-component>

        </section>

    </div>

@endsection

