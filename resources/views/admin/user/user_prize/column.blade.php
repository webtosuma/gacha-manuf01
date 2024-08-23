@extends('admin.layouts.app')


@section('title','取得商品一覧')


@section('meta') @php
$active_key = 'user';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>

                <li class="breadcrumb-item"><a href="{{ route('admin.user') }}"
                    >{{ '登録ユーザー' }}</a></li>

                @if($user){{--個人--}}
                <li class="breadcrumb-item"><a href="{{ route('admin.user.show', $user) }}"
                    >{{ $user->name }}</a></li>
                @endif

                <li class="breadcrumb-item active" aria-current="page">取得商品一覧</li>
            </ol>
        </nav>


        @if($user)
            <a href="{{route('admin.user.show', $user)}}"
            class="btn my-3 border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>戻る</a>
        @else
            <a href="{{route('admin.user')}}"
            class="btn my-3 border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>
        @endif


        <section class="mb-5">
            <h2 class="mb-3 py-3 border-bottom">
                @if($user){{--個人--}}
                    @if ($user->admin)<span class="text-primary">●</span> @endif
                    {{ $user->name }}様
                @endif

                取得商品一覧
            </h2>

            {{-- @if($user)
                @include('admin.user.common.profile')
            @endif --}}
        </section>



        <!-- 表示切り替え・ページネーション -->
        @if( $user_prizes->count() )
            <div class="d-flex justify-content-between mt-3">
                <div class="col">
                    {{-- {{ $user_prizes->links('vendor.pagination.bootstrap-4') }} --}}
                </div>

                <div class="col-auto">
                    <a href="{{route('admin.user.user_prize',$user->id)}}"
                    class="btn btn-light border"
                    >一覧表示</a>
                </div>
            </div>
        @endif

        <u-user-prize-form
        token=  "{{ csrf_token() }}"
        user_id="{{ $user->id }}"
        bottom_menu="false"
        r_api_user_prize ="{{ route('api_user_prize') }}"
        r_exchange_points="{{ route('user_prize.exchange_points') }}"
        r_shipped_appli  ="{{ route('shipped.appli') }}"
        ></u-user-prize-form>

        {{-- <!--商品一覧-->
        <ul class="row px-3 bg-white rounded-3 mx-2 gy-3 mt-0" style="list-style:none;">

            @forelse ($user_prizes as $user_prize)
            <li  class="col-12 col-sm-6 col-lg-4">
                <div class="row" >
                    <div class="col-4 px-0 pe-3 position-relative">

                        <ratio-image-component
                        style_class="ratio ratio-3x4 rounded-3"
                        url="{{ $user_prize->prize->image_path }}"></ratio-image-component>

                    </div>
                    <div class="col-8 p-0">
                        <div class="form-text">{{ $user_prize->created_at->format('Y/m/d/ H:i:s') }}</div>
                        <h6 classs="fw-bold">{{ $user_prize->prize->name }}</h6>

                        <div class="mt- px-3 text-center border rounded-pill d-inline-block">
                            <number-comma-component number="{{$user_prize->point}}"></number-comma-component>
                            {{ 'pt' }}
                        </div>

                    </div>
                </div>
            </li>
            @empty
                <li class="text-center text-secondary border-0 py-5">
                    *取得商品はありません
                </li>
            @endforelse

        </ul> --}}



        <!-- 表示切り替え・ページネーション -->
        @if( $user_prizes->count() )
            <div class="d-flex justify-content-between mt-3">
                <div class="col">
                    {{-- {{ $user_prizes->links('vendor.pagination.bootstrap-4') }} --}}
                </div>

                <div class="col-auto">
                    <a href="{{route('admin.user.user_prize',$user->id)}}"
                    class="btn btn-light border"
                    >一覧表示</a>
                </div>
            </div>
        @endif

    </div>
@endsection
