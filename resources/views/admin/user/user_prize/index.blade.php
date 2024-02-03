@extends('admin.layouts.app')


@section('title','ユーザー取得商品一覧')


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

                <li class="breadcrumb-item active" aria-current="page">ユーザー取得商品一覧</li>
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

                ユーザー取得商品一覧
            </h2>

            @if($user)
                @include('admin.user.common.profile')
            @endif
        </section>



        <!-- 削除 -->
        {{-- @if( $user )
        <form action="{{route('admin.user.user_prize.destroy_confirm',$user)}}" class="col-md-4" method="post">
            @csrf

            <div class="input-group mb-3">
                <span class="input-group-text">取得日時</span>
                <input type="datetime-local" class="form-control" name="created_at"
                value="{{ now()->format('Y-m-d\T00:00') }}"
                >
                <button class="btn btn-outline-secondary" type="submit">以降を削除</button>
            </div>
        </form>
        @endif --}}

        <!-- 表示切り替え・ページネーション -->
        @if( $user_prizes->count() )
            <div class="d-flex justify-content-between mt-3">
                <div class="col">
                    {{ $user_prizes->links('vendor.pagination.bootstrap-4') }}
                </div>

                @if($user)
                <div class="col-auto">
                    <a href="{{route('admin.user.user_prize.column',$user->id)}}"
                    class="btn btn-light border"
                    >カラム表示</a>
                </div>
                @endif
            </div>
        @endif


        <section class="card card-body bg-white my-3 overflow-auto">
            <table class="table bg-white my-3">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col"></th>
                        <th scope="col">アカウント名</th>
                        <th scope="col"></th>
                        <th scope="col">商品コード</th>
                        <th scope="col">商品名</th>
                        <th scope="col">ポイント</th>
                        <th scope="col">ガチャ</th>
                        <th scope="col">取得日時</th>
                        <th scope="col">履歴ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user_prizes as $user_prize)


                        @php
                        $user_id    = $user_prize->user ? $user_prize->user->id  : '';
                        $user_name  = $user_prize->user ? $user_prize->user->name  : '退会済み';
                        $gacha_name = isset($user_prize->ug_history) && $user_prize->ug_history->gacha
                        ? $user_prize->ug_history->gacha->name : '*削除済み';
                        @endphp


                        <tr>
                            <td>{{$user_id}}</td>
                            <td>
                                @if(!$user)
                                    <a href="{{route('admin.user.user_prize',$user_id)}}"
                                    >{{ $user_name}}</a>
                                @else{{ $user_name}}@endif
                            </td>
                            <td style="width:4rem;">
                                <ratio-image-component
                                style_class="ratio ratio-3x4 rounded-3"
                                url="{{ $user_prize->prize->image_path }}"></ratio-image-component>
                            </td>
                            <td>{{$user_prize->prize->code}}</td>
                            <td>{{$user_prize->prize->name}}</td>
                            <td>{{$user_prize->point}}pt</td>
                            <td>{{$gacha_name}}</td>

                            <td>{{ $user_prize->created_at->format('Y年m月d日 H:i:s') }}</td>
                            <td>{{$user_prize->id}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary border-0 py-5">
                                *取得商品はありません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>


        <!-- 表示切り替え・ページネーション -->
        @if( $user_prizes->count() )
            <div class="d-flex justify-content-between mt-3">
                <div class="col">
                    {{ $user_prizes->links('vendor.pagination.bootstrap-4') }}
                </div>

                @if($user)
                <div class="col-auto">
                    <a href="{{route('admin.user.user_prize.column',$user->id)}}"
                    class="btn btn-light border"
                    >カラム表示</a>
                </div>
                @endif
            </div>
        @endif

    </div>
@endsection
