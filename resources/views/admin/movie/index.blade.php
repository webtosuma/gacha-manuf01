@extends('admin.layouts.app')


@section('title','演出動画')


@section('meta') @php
$active_key = 'movie';
$active_submenu    = ! config('store.admin');//ガチャ用Adminのとき
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('script')
<script>
    /** 全てのビデオを停止する関数 */
    'use strict';
    const videos = document.querySelectorAll('video');

    const videoPause = function() {
        console.log('videoPause');

        videos.forEach(video => {
            console.log(video);

            // メディアの再生を停止
            video.pause();
        });
    }
</script>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">演出動画</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">演出動画</h2>

        <a href="{{ route('admin.movie.create') }}"
        class="btn btn-primary text-white shadow">
        <i class="bi bi-plus-lg"></i>
        {{'新規登録'}}
        </a>

        <section class="card card-body bg-white my-3 overflow-auto">
            <table class="table mb-0">
                <tbody>
                    <tr class="bg-light">
                        <th scope="col">演出動画名</th>
                        {{-- <th scope="col">PC用動画再生</th> --}}
                        <th scope="col">モバイル用動画再生</th>
                        <th scope="col"></th>
                    </tr>

                    @foreach ($movies as $movie)
                    <tr>
                        <!-- 演出動画名 -->
                        <td>{{ $movie->name }}</td>


                        {{-- <td>
                            <!-- PC用 -->
                            @if ($movie->pc)
                                <movie-modal-component
                                id   ="{{$movie->id.'-pc'}}"
                                title="{{ $movie->name.'（PC）' }}"
                                src  ="{{ $movie->pc }}"
                                btn_label="PC用動画再生"
                                max_width="800px"
                                ></movie-modal-component>
                            @else
                                <span>未登録</span>
                            @endif
                        </td> --}}
                        <td>
                            <!-- モバイル用 -->
                            @if ($movie->mobile or $movie->type=='youtube')
                                <movie-modal-component
                                id   ="{{$movie->id.'-mobile'}}"
                                title="{{ $movie->name.( $movie->mobile ?'（モバイル）':'(Youtube)') }}"
                                src  ="{{ $movie->mobile ?? $movie->youtube_url }}"
                                btn_label="モバイル用動画再生"
                                max_width="400px"
                                ></movie-modal-component>
                            @else
                                <span>未登録</span>
                            @endif
                        </td>

                        <td style="width:8rem;"><div class="row justify-content-end g-2">
                            <div class="col-auto">
                                <!--編集ボタン-->
                                <a href="{{ route('admin.movie.edit',$movie) }}"
                                class="btn btn-sm btn-light border "
                                ><i class="bi bi-pencil-fill"></i></a>
                            </div>
                            <div class="col-auto">
                                <!--削除モーダル-->
                                <form action="{{ route('admin.movie.destroy', $movie) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <delete-modal-component
                                    index_key="{{'delete'.$movie->id}}"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-sm btn-light border ">
                                        <div>
                                            <span class="fw-bold">『{{$movie->name}}』</span>を削除します。
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
