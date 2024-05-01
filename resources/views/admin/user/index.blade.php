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

        <div class="col-12 col-md-6">
            {{-- <form action="{{route('admin.user.search')}}" method="post"> --}}
            <form action="{{route('admin.user')}}">

                <div class="input-group mb-3">

                    <input type="number" class="form-control col-auto" placeholder="ID" name="search_id" value="{{$search_id}}" style="flex:none; width:4rem;" min="1">

                    <input type="text"   class="form-control" placeholder="アカウント名" name="search_name" value="{{$search_name}}">

                    <input type="text"   class="form-control" placeholder="メールアドレス" name="search_email" value="{{$search_email}}">

                    <input type="text"   class="form-control" placeholder="X(旧Twitter) ID" name="search_twitter_id" value="{{$search_twitter_id}}">

                    <button class="btn btn-outline-secondary" type="submit">検索</button>
                </div>
            </form>
        </div>
        <div class="row g-2">
            <div class="col-auto">
                <a href="{{route('admin.user.canpaing_introductory')}}" class="btn border">紹介登録者一覧</a>
            </div>
            <div class="col-auto">
                <form action="{{route('admin.user.download_csv')}}" class="w-100">

                    <input type="hidden" class="form-control col-auto" placeholder="ID" name="search_id" value="{{$search_id}}" style="flex:none; width:6rem;" min="1">

                    <input type="hidden"   class="form-control" placeholder="アカウント名" name="search_name" value="{{$search_name}}">

                    <input type="hidden"   class="form-control" placeholder="メールアドレス" name="search_email" value="{{$search_email}}">

                    <input type="hidden"   class="form-control" placeholder="X(旧Twitter) ID" name="search_twitter_id" value="{{$search_twitter_id}}">

                    <button class="btn border w-100 py-0" type="submit"><i class="bi bi-filetype-csv fs-4"></i>ダウンロード</button>
                </form>
            </div>
            <div class="col-auto">
                <!--会員ランク更新モーダル-->
                <form action="{{ route('admin.user.user_rank_history.all_update') }}" method="post">
                    @csrf
                    <delete-modal-component
                    index_key="{{'all_update_user_rank'}}"
                    icon="" color="info"
                    func_btn_type="submit"
                    button_text="会員ランク一括更新"
                    button_class="btn border w-100">
                        <div>
                            <span class="fw-bold">全てのユーザー</span>の会員ランクを更新します
                            <div class="">よろしいですか？</div>
                        </div>
                    </delete-modal-component>
                </form>
            </div>
            <div class="col-auto">
                @php
                $sort = 'deleted';
                $params = compact('search_id','search_name','search_email','search_twitter_id','sort');
                @endphp
                <a href="{{route('admin.user',$params)}}" class="btn border"
                >退会済</a>
            </div>
        </div>


        <!-- ページネーション -->
        <div class="d-flex justify-content-start  mt-3">
            {{ $users->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
        </div>

        <section class="card card-body bg-white mb-3 overflow-auto">
            <table class="table bg-white my-3">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th></th>
                        <th></th>
                        <th scope="col">アカウント名</th>
                        <th scope="col">メールアドレス</th>
                        <th scope="col">X(旧Twitter)ID</th>
                        <th scope="col" class="text-center">メール</th>
                        <th scope="col" class="text-center">会員ランク</th>


                        <th class="text-center" scope="col">
                            {{-- {{ '商品' }} --}}
                            <a href="{{route('admin.user.user_prize',0)}}">商品</a>
                        </th>
                        <th class="text-center" scope="col">
                            <a href="{{route('admin.user.point_history',['user_id'=>0,'reason_id'=>21,])}}">ガチャ</a>
                        </th>
                        <th class="text-center" scope="col">
                            <a href="{{route('admin.user.point_history',0)}}">ポイント</a>
                        </th>
                        <th class="text-center" scope="col">
                            <a href="{{route('admin.user.ticket_history',0)}}">チケット</a>
                        </th>

                        <th scope="col">会員登/最終アクセス</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>
                                <div style="width:1.6rem;">
                                    <ratio-image-component
                                    style_class="rounded-circle ratio ratio-1x1 border"
                                    url="{{$user->image_path}}"
                                    ></ratio-image-component>
                                </div>
                            </td>
                            <td>
                                <!-- アカウント名 -->
                                <a href="{{route('admin.user.show',$user)}}" class="d-block">
                                    <div class="">
                                        @if ($user->admin)<span class="text-primary">●</span> @endif

                                        {{ mb_strlen($user->name) <= 14 ? $user->name : mb_substr($user->name,0,14).'...' }}
                                    </div>
                                </a>

                                @if($user->deleted_at)
                                    @php
                                    $sort = 'deleted';
                                    $params = compact('search_id','search_name','search_email','search_twitter_id','sort');
                                    @endphp
                                    <a href="{{route('admin.user',$params)}}" class="d-block text-danger"
                                    >*退会済</a>
                                @endif
                            </td>
                            <!-- メールアドレス -->
                            <td>
                                {{ strlen($user->email) <= 14 ? $user->email : substr($user->email,0,14).'...' }}
                            </td>
                            <!-- X(旧Twitter)ID -->
                            <td>{{ $user->twitter_id ? $user->twitter_id : '---' }}</td>
                            <!-- メール受取 -->
                            <td class="text-center">
                                @if ($user->get_email)
                                    <div class="text-success">ON</div>
                                @else
                                    <div class="text-secondary">--</div>
                                @endif
                            </td>

                            <td class="text-center"><a href="{{route('admin.user.user_rank_history',$user)}}" class="btn btn-link text-decoration-none">
                                @if( $user->now_rank )
                                    <div style="width:50px;">
                                        <ratio-image-component
                                        style_class="ratio ratio-16x9 overflow-hidden position-relative shiny"
                                        url="{{ $user->now_rank->image_path }}"
                                        ></ratio-image-component>
                                    </div>
                                    <div class="form-text" style="font-size:8px;">{{ $user->now_rank->label }}</div>
                                @else
                                    <span class="text-danger">*未更新</span>
                                @endif
                            </a></td>
                            <td class="text-center"><a href="{{route('admin.user.user_prize',$user->id)}}" class="btn btn-link">
                                <number-comma-component number="{{ $user->u_prizes_count }}"></number-comma-component>
                            </a></td>
                            <td class="text-center"><a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>21,])}}"
                            class="btn btn-link">
                                <number-comma-component number="{{ $user->gacha_play_count }}"></number-comma-component>
                            </a></td>

                            <td class="text-center"><a href="{{route('admin.user.point_history',$user->id)}}" class="btn btn-link">
                                @if($user->id!=1)
                                <number-comma-component number="{{ $user->point }}"></number-comma-component>
                                @else
                                <span class="">＊表示できません</span>
                                @endif
                            </a></td>
                            <td class="text-center"><a href="{{route('admin.user.ticket_history',$user->id)}}" class="btn btn-link">
                                <number-comma-component number="{{ $user->ticket }}"></number-comma-component>
                            </a></td>
                            <td>
                                <div class="text-">{{ $user->created_at->format('Y年m月d日 H:i') }}</div>
                                <div class="text-success">{{ $user->last_access_at->format('Y年m月d日 H:i') }}</div>
                            </td>
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


        <!-- ページネーション -->
        <div class="d-flex justify-content-start mt-3">
            {{ $users->links('vendor.pagination.bootstrap-4',['elements' => 8]) }}
        </div>
    </div>
@endsection
