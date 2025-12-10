{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title',$text_bodys['user_rank_title'])
@section('meta')
    @php
        $meta_title = $text_bodys['user_rank_title'];
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
        <h2 class="d-none d-md-block text-center my-3"
        >{{$text_bodys['user_rank_title']}}</h2>

        <!-- [ 本文 ] -->
        <section id="articleBody" class="my-5 pb-5">


            @if( $text_bodys['user_rank_img01'] )
                <div class="col-md-10 mx-auto mb-5">
                    <img src="{{$text_bodys['user_rank_img01']}}" alt=""
                    class="w-100">
                </div>
            @endif


            @if( $text_bodys['user_rank_body01'] )
                <replace-text-component text="{{ $text_bodys['user_rank_body01'] }}"></replace-text-component>
            @endif


            @if( $text_bodys['user_rank_img02'] )
                <div class="col-md-10 mx-auto my-5">
                    <img src="{{$text_bodys['user_rank_img02']}}" alt=""
                    class="w-100">
                </div>
            @endif


            @if( $text_bodys['user_rank_body02'] )
                <replace-text-component text="{{ $text_bodys['user_rank_body02'] }}"></replace-text-component>
            @endif


        </section>

    </div>

@endsection

