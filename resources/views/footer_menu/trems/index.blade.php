{{-- @extends('layouts.app') --}}
@extends('layouts.sub')


<!----- title ----->
@section('title','利用規約')
@section('meta')
    @php
        $meta_title = '利用規約';
    @endphp
@endsection



<!----- contents ----->
@section('content')

    <div class="container my-md-5">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            利用規約
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('footer_menu.trems.'.$revision_date)

            {{-- <div class="mb-3">
                <div class="float-end text-end">
                    <p>{{\Carbon\Carbon::parse($revision_date)->format('制定日Y年m月d日')}}</p>
                </div>
            </div> --}}

            <div class="mt-5">
                <a href="{{route('trems','2023-12-01')}}"
                >2023年012月1日制定</a><br>
                <a href="{{route('trems','2024-02-16')}}"
                >2024年02月16日改訂</a><br>
            </div>
        </section>


    </div>

@endsection
