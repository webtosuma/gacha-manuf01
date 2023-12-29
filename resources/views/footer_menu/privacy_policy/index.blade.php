@extends('layouts.app')


<!----- title ----->
@section('title','プライバシーポリシー')
@section('meta')
    @php
        $meta_title = 'プライバシーポリシー';
    @endphp
@endsection


<!----- contents ----->
@section('content')

    <div class="container my-5">

        <!-- [ 見出し ] -->
        <h2 class="text-center my-3">
            プライバシーポリシー
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('footer_menu.privacy_policy.'.$revision_date)

            <div class="mb-3">
                <div class="float-end text-end">
                    <p>{{\Carbon\Carbon::parse($revision_date)->format('制定日Y年m月d日')}}</p>
                </div>
            </div>
        </section>

    </div>

@endsection
