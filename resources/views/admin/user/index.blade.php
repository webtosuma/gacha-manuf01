@extends('admin.layouts.app')


@section('title','登録ユーザー')


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
                <li class="breadcrumb-item active" aria-current="page">登録ユーザー</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">登録ユーザー</h2>


        <section class="card card-body bg-white my-3 overflow-auto" style="height: 60vh;">
            <table class="table bg-white my-3">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col">アカウント名</th>
                        <th scope="col">メールアドレス</th>
                        <th scope="col">X(旧twitter)ID</th>

                        <th class="text-center" scope="col"
                        >保有商品数</th>
                        <th class="text-center" scope="col"
                        >ガチャPLAY数</th>
                        <th class="text-center" scope="col"
                        >保有ポイント</th>
                        <th></th>

                        <th scope="col">会員登録日</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                @if ($user->admin)
                                    <span class="text-primary">●</span>
                                @endif
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->twitter_id ? $user->twitter_id : '---' }}</td>

                            <td class="text-center"><a href="" class="btn btn-link">
                                <number-comma-component number="{{ $user->u_prizes->count() }}"></number-comma-component>
                            </a></td>
                            <td class="text-center"><a href="" class="btn btn-link">
                                <number-comma-component number="{{ $user->gacha_histories->count() }}"></number-comma-component>
                            </a></td>
                            <td class="text-center"><a href="" class="btn btn-link">
                                <number-comma-component number="{{ $user->point }}"></number-comma-component>
                            </a></td>
                            <td>
                                {{-- <a href="" class="btn btn-warning btn-sm rounded-pill form-text">+PT付与</a> --}}
                                <!--ポイント付与モーダル-->
                                <form action="{{ route('admin.user.add_point', $user) }}" method="post">
                                    @csrf
                                    @method('PATCH')

                                    <delete-modal-component
                                    index_key="{{'delete'.$user->id}}"
                                    icon="bi-coin" color="warning"
                                    func_btn_type="submit"
                                    button_text="+PT付与"
                                    button_class="btn btn-warning btn-sm text-white rounded-pill form-text">
                                        <div>
                                            <span class="fw-bold">『{{$user->name}}』さんに</span>ポイントを付与します。
                                            <div class="col-8 mx-auto">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon3">付与ポイント</span>
                                                    <input class="form-control text-end"  type="number" name="value" value="1000" min="100">
                                                    <span class="input-group-text" id="basic-addon3">pt</span>
                                                </div>
                                            </div>
                                            よろしいですか？
                                        </div>
                                    </delete-modal-component>
                                </form>


                            </td>
                            <td>{{ $user->created_at->format('Y年m月d日') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary border-0 py-5">
                                *登録ユーザーはいません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
