@extends('admin.layouts.app')


@section('title','会員ランク履歴')


@section('meta') @php
$active_key = 'user';
@endphp @endsection


@section('style')
    <style>
        .form-check-input:checked {
            background-color: hsl(330, 63%, 59%);
            border-color: hsl(330, 63%, 59%);
        }
    </style>
@endsection



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

                <li class="breadcrumb-item active" aria-current="page">会員ランク履歴</li>
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

                会員ランク履歴
            </h2>

            @if($user)
                @include('admin.user.common.profile')
            @endif
        </section>




        <form action="{{route('admin.user.point_history.destroy_confirm',$user?$user->id:0)}}" method="post">
            @csrf

            <!-- 削除ボタン・ページネーション -->
            @if( $user_rank_histories->count() )
                <div class="d-flex flex-column flex-md-row justify-content-between mt-3">
                    <div class="col-12 col-md">
                        {{ $user_rank_histories->links('vendor.pagination.bootstrap-4') }}
                    </div>

                    {{-- <div class="col-12 col-md-auto">
                        <button type="submit"
                        class="btn btn-light border text-danger "
                        >一括削除</button>
                    </div> --}}
                </div>
            @endif


            <!-- 一覧 -->
            <ul class="list-group list-group-flush">
                @forelse ($user_rank_histories as $user_rank_history)
                    <li class="list-group-item bg-white py-3">
                        <div class="row align-items-center ">
                            <div class="col">

                                <div class="form-text">{{$user_rank_history->created_at->format('Y/m/d H:i')}}</div>
                                <div class="fw-bold">{{$user_rank_history->label}}</div>
                                <div class="form-text">pt消費数:{{ number_format( $user_rank_history->pt_count ) }}pt</div>

                            </div>
                            <div class="col-auto" style="width:100px;">
                                <ratio-image-component
                                style_class="ratio ratio-16x9 rounded-3 overflow-hidden
                                position-relative shiny"
                                url="{{ $user_rank_history->image_path }}"
                                ></ratio-image-component>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item bg-white py-3 fw-bold">履歴はありません。</li>
                @endforelse
            </ul>


            <!-- 削除ボタン・ページネーション -->
            @if( $user_rank_histories->count() )
                <div class="d-flex flex-column flex-md-row justify-content-between mt-3">
                    <div class="col-12 col-md">
                        {{ $user_rank_histories->links('vendor.pagination.bootstrap-4') }}
                    </div>

                    {{-- <div class="col-12 col-md-auto">
                        <button type="submit"
                        class="btn btn-light border text-danger "
                        >一括削除</button>
                    </div> --}}
                </div>
            @endif

        </form>


    </div>
@endsection
