{{-- @extends('layouts.app') --}}
@extends('layouts.sub')


<!----- title ----->
@section('title','プライバシーポリシー')
@section('meta')
    @php
        $meta_title = 'プライバシーポリシー';
    @endphp
@endsection


<!----- contents ----->
@section('content')

    <div class="container my-md-5">

        <!-- [ 見出し ] -->
        <h2 class="d-none d-md-block text-center my-3">
            プライバシーポリシー
        </h2>

        <!-- [ 本文 ] -->
        <section class="my-5">
            @include('footer_menu.privacy_policy.'.$revision_date)

            {{-- <div class="mb-3">
                <div class="float-end text-end">
                    <p>{{\Carbon\Carbon::parse($revision_date)->format('制定日Y年m月d日')}}</p>
                </div>
            </div> --}}

            <div class="mt-5">
                {{-- <a href="{{route('privacy_policy','2024-01-15')}}"
                >2024年1月15日制定</a><br> --}}
                <a href="{{route('privacy_policy','2024-03-01')}}"
                >2024年3月1日制定</a><br>
            </div>

        </section>

    </div>

@endsection
