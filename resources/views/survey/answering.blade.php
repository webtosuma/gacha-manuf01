@extends('layouts.app')

<!--title-->
@section('title','アンケート：')


<!--meta-->
@section('meta')
    @php
    $meta_title = 'アンケート：';
    $meta_image = $gacha->image_path;
    @endphp
@endsection


@section('style')
<style>
    #article-head {
        margin-bottom: -18px;
        font-weight: bold;
        font-feature-settings: "palt" 1;
        line-height: 2.25rem;
        letter-spacing: .04em;
    }
    .article-body{
        font-size: 1.125rem !important;
        line-height: 2.25rem;
        margin-top: 0;
        margin-bottom: 1rem;
        margin-top: 36px;
        margin-bottom: 36px;
    }
</style>
@endsection


@section('content')
    <div class="container mt-md-3">
        <div style="min-height: 80vh">


            <h5 class="mb-3">
                アンケートへの回答をお願いします。<br>
                回答完了後に「完了」ボタンを押すと、ガチャを開始します。
            </h5>

            <div class="card border-0 bg-info-subtle rounded-4"
            data-aos="zoom-inin"
            >
                <div class="card-body">
                    <h6 class="card-title fw-bold">サンプル質問</h6>
                    <p class="card-text article-body">
                        あなたがよく利用するSNSを選択してください[複数選択]
                    </p>
                    <div class="">
                        @foreach (['X(旧twitter)','TikTok','Instagram','LINE','その他'] as $item)
                        <label class="form-check d-block fs-5 mb-3">
                            <input class="form-check-input"
                            type="checkbox"
                            name=""
                            value="">
                            <div class="form-check-label "
                            >{{$item}}</div>
                        </label>
                        @endforeach
                    </div>


                    @php
                    $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key];
                    @endphp
                    <form action="{{route('gacha.play',$params)}}"
                    method="POST"
                    >
                    @csrf
                        <input type="hidden" name="play_count" value="{{$play_count}}">

                        <div class="col-md-6 mx-auto">
                            <button
                            class="btn btn-primary text-white rounded-pill w-100 my-3"
                            type="submit"
                            >完了</button>
                        </div>
                    </form>

                </div>
            </div>


        </div>
    </div>
@endsection
