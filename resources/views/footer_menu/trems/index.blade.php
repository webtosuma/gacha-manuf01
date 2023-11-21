@extends('layouts.app')


<!----- title ----->
@section('title','利用規約')

<!----- contents ----->
@section('content')

    <div class="container my-5">

        <!-- [ 見出し ] -->
        <h2 class="text-center my-3">
            利用規約
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('footer_menu.trems.'.$revision_date)

            <div class="mb-3">
                <div class="float-end text-end">
                    <p>{{\Carbon\Carbon::parse($revision_date)->format('制定日Y年m月d日')}}</p>
                </div>
            </div>
        </section>

    </div>

@endsection
