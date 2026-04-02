@extends('manuf_admin.layouts.gacha_title')


@section('title',$gacha_title->name.' タイトル商品一覧')


@section('meta') @php
$active_key = 'gacha_title.title_prize';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection



@section('content')


    {{-- パンくずリスト --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
            >{{ 'Top' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title.show',$gacha_title) }}"
            >{{$gacha_title->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'タイトル商品一覧'}}</li>
        </ol>
    </nav>



    <div class="mb-3">
        <a href="{{ route('admin.gacha_title.title_prize.create',$gacha_title) }}"
        class="btn btn-primary text-white  rounded-pill shadow"
        ><i class="bi bi-plus-lg me-2"></i>新規登録</a>
    </div>


    @if($title_prizes->count() )
        <div class="border rounded overflow-hidden mb-4">
            <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
                <tbody>
                    @foreach ($title_prizes as $title_prize)
                    <tr>

                        <!-- 画像 -->
                        <th style="width:4rem;">
                            <div class="ratio ratio-1x1 border rounded bg-white"
                                style="
                                    background: no-repeat center center / contain;
                                    background-image: url({{ $title_prize->image_path }});
                                ">
                            </div>
                        </th>

                        <!--公開-->
                        <td style="width:3.2rem;" class="text-center">
                            @if( $title_prize->published_at )
                                <span class="form-text text-success">公開</span>
                            @else
                                <span class="form-text">下書き</span>
                            @endif
                        </td>

                        <!-- 商品情報 -->
                        <td>
                            <div class="fw-bold">
                                {{ $title_prize->name }}
                            </div>

                            <div class="text-muted small">
                                {{ $title_prize->code }} / {{ $title_prize->category->name }}
                            </div>
                        </td>

                        <td style="width:6rem;">
                            <div class="d-flex gap-1">

                                <!-- 説明 -->
                                <u-prize-discription
                                    id="{{ $title_prize->id }}"
                                    name="{{ $title_prize->name }}"
                                    image_path="{{ $title_prize->image_path }}"
                                    discription="{{ $title_prize->discription_text }}"
                                    size="2.4rem"
                                    src_icon="{{ $title_prize->discription_icon_path }}"
                                    no_btn=""
                                    bg_dark=""
                                ></u-prize-discription>

                                <!--編集ボタン-->
                                <a href="{{ $title_prize->r_edit }}"
                                class="btn btn-light border"
                                ><i class="bi bi-pencil-fill"></i></a>

                                <!--コピーボタン-->
                                <form
                                action="{{ $title_prize->r_copy }}"
                                method="post"
                                class="d-inline-block"
                                >
                                    @csrf

                                    <delete-modal-component
                                    index_key="{{'copy-'.$title_prize->id}}"
                                    icon="bi-files"
                                    func_btn_type="submit"
                                    color="warning"
                                    button_class="btn btn-light border">
                                        <div>
                                            <span class="fw-bold">『{{$title_prize->name}}』をコピーします。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>

                                </form>



                                <form
                                action="{{ $title_prize->r_destroy }}"
                                method="post"
                                class="d-inline-block"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <delete-modal-component
                                    index_key="{{'delete-'.$title_prize->id}}"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-light border">
                                        <div>
                                            <span class="fw-bold">『{{$title_prize->name}}』を削除します。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>

                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif




@endsection
