@extends('admin.layouts.app')


@section('title','広告管理')


@section('meta') @php
$active_key = 'sponsor_ad';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">広告管理</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">広告管理</h2>


        <div class="d-flex gap-3 mb-3">
            <a href="{{ route('admin.sponsor.create') }}"
            class="btn btn-dark text-white shadow">
            <i class="bi bi-plus-lg"></i>
            {{'スポンサー新規登録'}}
            </a>

            <a href="{{ route('admin.sponsor_ad.create') }}"
            class="btn btn-primary text-white shadow">
            <i class="bi bi-plus-lg"></i>
            {{'広告新規登録'}}
            </a>

            <a href="{{ route('admin.sponsor') }}"
            class="btn btn-light border">{{'スポンサー一覧'}}</a>


        </div>


        <section class="card card-body bg-white mb-3 overflow-auto my-3">
            <table class="table bg-white my-3 text-center">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col" class="text-start">広告タイトル</th>
                        <th scope="col">スポンサー名</th>
                        <th scope="col">ガチャ</th>
                        <th><!--ガチャ公開--></th>
                        <th scope="col">動画再生数</th>
                        <th scope="col">サイトアクセス数</th>
                        <th><!--動画再生--></th>
                        <th><!--menu--></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sponsor_ads as $sponsor_ad)
                        <tr>
                            {{-- <td class="text-start fw-bold"><a href="{{route('admin.sponsor_ad.show',$sponsor_ad)}}"
                            >{{$sponsor_ad->title}}</a></td> --}}
                            <th class="text-start">{{$sponsor_ad->title}}</th>


                            <td>{{$sponsor_ad->sponsor->name}}</td>


                            <td>
                                @if($sponsor_ad->gacha)
                                    <a href="{{route('admin.gacha.show',$sponsor_ad->gacha)}}"
                                    >{{$sponsor_ad->gacha->name}}</a>
                                @else
                                    <div class="">{{'未登録'}}</div>
                                @endif
                            </td>

                            <td>
                                @if($sponsor_ad->gacha)

                                    @if ($sponsor_ad->gacha->is_published)
                                        <span class="fw-bold text-success">公開中</span>
                                    @else
                                        <span class="fw-bold text-danger">非公開</span>
                                    @endif

                                @endif
                            </td>

                            <td>{{number_format( $sponsor_ad->movie_play_count )}}</td>

                            <td>{{number_format( $sponsor_ad->access_count )}}</td>


                            <td>
                                <!--動画再生-->
                                @if($sponsor_ad->movie_path)
                                    <movie-modal-component
                                    id   ="{{$sponsor_ad->id.'-movie'}}"
                                    title="{{ $sponsor_ad->title }}"
                                    src  ="{{ $sponsor_ad->movie_path }}"
                                    btn_label="広告動画再生"
                                    max_width="400px"
                                    ></movie-modal-component>
                                @else
                                    <div class="">未登録</div>
                                @endif
                            </td>

                            <td style="width:8rem;"><div class="row justify-content-end g-2">
                                <div class="col-auto">
                                    <!--編集ボタン-->
                                    <a href="{{ route('admin.sponsor_ad.edit',$sponsor_ad) }}"
                                    class="btn btn-sm btn-light border "
                                    ><i class="bi bi-pencil-fill"></i></a>
                                </div>
                                <div class="col-auto">
                                    <!--削除モーダル-->
                                    <form action="{{ route('admin.sponsor_ad.destroy', $sponsor_ad) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <delete-modal-component
                                        index_key="{{'delete'.$sponsor_ad->id}}"
                                        icon="bi-trash"
                                        func_btn_type="submit"
                                        button_class="btn btn-sm btn-light border ">
                                            <div>
                                                <span class="fw-bold">『{{$sponsor_ad->title}}』</span>を削除します。
                                                <br />よろしいですか？
                                            </div>
                                        </delete-modal-component>
                                    </form>
                                </div>
                            </div></td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection
