@extends('layouts.sub')


<!----- title ----->
@section('title','タイムライン')
@section('meta')
    @php
        $meta_title = 'タイムライン';
    @endphp
@endsection


@section('script')
    <!--X timeline-->
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
@endsection



@section('content')

    <div class="container my-md-5">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            タイムライン
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5 mx-auto" style="max-width:900px;">
            <div class="col-md-8 mx-auto text-secondary text-center rounded-4 overflow-auto">
                <a class="twitter-timeline" href="https://twitter.com/CardFesta7627?ref_src=twsrc%5Etfw">...読み込み中</a>
            </div>
        </section>

    </div>

@endsection

