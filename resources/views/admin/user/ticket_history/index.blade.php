@extends('admin.layouts.app')


@section('title','チケット履歴')


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

                <li class="breadcrumb-item active" aria-current="page">チケット履歴</li>
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

                チケット履歴
            </h2>

            @if($user)
                @include('admin.user.common.profile')
            @endif
        </section>



        <!-- 絞り込み -->
        <form action="#">
            <div class="d-flex justify-content-end mt-3">
                <div class="col-8 col-md-3">
                    <div class="input-group mb-3">
                        <select class="form-select" name="reason_id">
                            <option value="0"
                            @if($reason_id==0) selected @endif
                            >すべて</option>

                            @foreach ($reasons as $value => $label)
                                <option value="{{$value}}"
                                @if($reason_id==$value) selected @endif
                                >{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-light border">絞り込み</button>
                    </div>
                </div>
            </div>
        </form>


        {{-- <form action="{{route('admin.user.ticket_history.destroy_confirm',$user?$user->id:0)}}" method="post">
            @csrf --}}

            <!-- 削除ボタン・ページネーション -->
            @if( $ticket_histories->count() )
                <div class="d-flex flex-column flex-md-row justify-content-between mt-3">
                    <div class="col-12 col-md">
                        {{ $ticket_histories->links('vendor.pagination.bootstrap-4') }}
                    </div>

                    <div class="col-12 col-md-auto">
                        {{-- <button type="submit"
                        class="btn btn-light border text-danger "
                        >一括削除</button> --}}
                    </div>
                </div>
            @endif


            <!-- 一覧 -->
            <ul class="list-group list-group-flush">
                @include('admin.user.ticket_history._types')
            </ul>


            <!-- 削除ボタン・ページネーション -->
            @if( $ticket_histories->count() )
                <div class="d-flex flex-column flex-md-row justify-content-between mt-3">
                    <div class="col-12 col-md">
                        {{ $ticket_histories->links('vendor.pagination.bootstrap-4') }}
                    </div>

                    <div class="col-12 col-md-auto">
                        {{-- <button type="submit"
                        class="btn btn-light border text-danger "
                        >一括削除</button> --}}
                    </div>
                </div>
            @endif

        {{-- </form> --}}


    </div>
@endsection
