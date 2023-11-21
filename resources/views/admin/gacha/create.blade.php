@extends('admin.layouts.app')


@section('title','ガチャ登録')


@section('meta') @php
$active_key = 'gacha';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                    >{{ 'ガチャ管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ガチャ新規登録</li>
                </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">ガチャ新規登録</h2>


        <section class="mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page"
                  >基本情報</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link"
                  href="#">商品紹介ページ</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link"
                  href="#">商品登録</a>
                </li>
              </ul>
        </section>
        <section>
            <div class="row">
                <div class="col-md">


                    <div class="px-3">
                        <label class="d-block mb-4">
                            <div class="form-label">トップ画像</div>

                            <read-image-file-component
                            img_path="{{asset('storage/site/image/bg02.jpg')}}"
                            noimg_path="{{asset('storage/site/image/bg02.jpg')}}"
                            style_class="ratio ratio-16x9 rounded-3"
                            name="image"
                            ></read-image-file-component>
                        </label>
                    </div>


                </div>
                <div class="col-md-6">

                    <label class="d-block mb-4">
                        <div class="form-label">カテゴリー選択</div>
                        <select class="form-select">
                            <option selected>カテゴリー選択</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </label>

                    <label class="d-block mb-4">
                        <div class="form-label">ガチャ名</div>
                        <input type="text" class="form-control">
                        <div class="form-text">ユーザーには表示されない、管理用の名前です。</div>
                    </label>

                    <label class="d-block mb-4 col-4">
                        <div class="form-label">1回のガチャに必要なポイント</div>
                        <input type="number" class="form-control" min="0">
                        <div class="form-text">更新日 0000/00/00</div>
                    </label>

                    <label class="d-block mb-4">
                        <div class="form-label">公開予約設定</div>
                        <input type="date" class="form-control">
                        <div class="form-text">We'll never share your text with anyone else.</div>
                    </label>

                    <div class="col-md-6 my-5">
                        <button class="btn btn-primary text-white w-100">登録する</button>
                    </div>


                </div>
            </div>
        </section>

    </div>
@endsection
