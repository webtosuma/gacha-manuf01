@extends('admin.layouts.app')


@section('title', $text_type['label'] )


@section('meta') @php
$active_key = 'text';
$active_submenu = true;
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.text') }}"
                >{{ '文書設定' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$text_type['label']}}</li>
            </ol>
        </nav>



        <h2 class="mt-5 py-3 border-bottom">{{$text_type['label']}}</h2>

        <div class="d-flex gap-3 align-items-center my-3">
            <a href="{{route('admin.text')}}"
            class="btn border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>戻る</a>
        </div>

        <section>
            @switch($text_type['type'])
                @case('meta')
                    <!--メタ情報(meta)-->
                    <div class="form-text mb-3">
                        <span class="text-danger">＊</span>入力必須
                    </div>

                    @include('admin.text.forms.meta')
                    @break


                @case('sbg_license')
                    <!--古物営業許可(sbg_license)-->
                    <div class="form-text mb-3">
                        <span class="text-danger">＊</span>入力必須
                    </div>

                    @include('admin.text.forms.sbg_license')
                    @break


                @case('rainbow')
                    <!--レインボー(rainbow)-->
                    @include('admin.text.forms.rainbow')
                    @break


                @case('user_rank')
                    <!--ユーザーランク(user_rank)-->
                    <div class="form-text mb-3">
                        <span class="text-danger">＊</span>入力必須
                    </div>

                    @include('admin.text.forms.user_rank')
                    @break


                @default
                    <!--通常-->

                    @include('admin.text.forms.index')

                <!---->
            @endswitch
        </section>

    </div>
@endsection
