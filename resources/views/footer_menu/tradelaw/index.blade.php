{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','特定商取引法に基づく表記')
@section('meta')
    @php
        $meta_title = '特定商取引法に基づく表記';
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
            特定商取引法に基づく表記
        </h2>

        <!-- [ 本文 ] -->
        <section id="articleBody" class="my-5">
            @include('footer_menu.tradelaw.'.$revision_date)

            {{-- <div class="mt-5">
                <a href="{{route('tradelaw','2023-12-01')}}"
                >2023年12月1日制定</a><br>
                <a href="{{route('tradelaw','2024-02-14')}}"
                >2024年02月14日改訂</a><br>
            </div> --}}
        </section>

    </div>

@endsection

