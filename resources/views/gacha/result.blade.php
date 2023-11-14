@extends('layouts.app')

<!--title-->
@section('title','ガチャ結果')

@section('style')
<style>
    #result {
        background: no-repeat center center / cover;
        background-image: url({{asset('storage/site/image/gacha/bg_result.jpg')}});
    }

    /* トレカの比率 */
    .ratio-3x4 {
        --bs-aspect-ratio: 133.3%;
    }
</style>
@endsection

@section('content')
    <section id="result">
        <div class="container px-3 py-4"  style="max-width:500px;">

            <h2 class="text-secondary fw-bold btn btn-lg w-100 mb-4"
            style="background: rgb(255, 255, 255, .7;"
            >ガチャ結果</h2>


            <div class="row justify-content-center g-3 gy-4" style="min-height: 80vh;" >


                @foreach ($user_prizes as $user_prize)
                    <div class="col-3 col-md-3">
                        <div class="d-flex align-items-center justify-content-center h-100">


                            <label class="w-100">

                                <div class="position-relative">
                                    <!--チェックボックス-->
                                    <div class="position-absolute top-0 start-0" style="z-index:100">
                                        <input class="form-check-input float-xl-none m-0"
                                        type="checkbox" name="user_prize_ids[]" value="{{ $user_prize->id }}">
                                    </div>

                                    <!--カード画像-->
                                    <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded-3"
                                    url="{{ $user_prize->prize->image_path }}"
                                    ></ratio-image-component>
                                </div>

                                <!--ポイント表示-->
                                <div class="bg-white text- fw-bold text-center mt-1 px-1 rounded">
                                    {{ $user_prize->prize->point.'pt' }}
                                </div>
                                <div class="bg-dark text-primary fw-bold text-center mt-1 px-1 rounded">
                                    {{ 'ランク '.$user_prize->prize->rank_id }}
                                </div>

                        </label>


                        </div>
                    </div>
                @endforeach

                <div class="col-12 rounded-3 p-3" style="background: rgb(0, 0, 0, .7;">
                    <div class="col-md-8 mx-auto">
                        <button class="btn btn-warning rounded-pill w-100">選択した商品をポイントに変換</button>
                    </div>
                    <p class="text-white form-text m-0 mt-3">
                        *選択されなかった商品は、「取得カード一覧」に移動されます。
                    </p>
                </div>

            </div>
        </div>
    </section>
    <section class="py-5 bg-dark border-bottom border-right">
        <div class="container">
            @php
            $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key];
            @endphp

            <div class="row g-3">
                <div class="col-md">
                    <a href="{{ route('gacha', $params ) }}"
                    class="btn btn-light border-dark rounded-pill w-100"
                    >{{ 'もう一度ガチャる' }}</a>
                </div>
                <div class="col-md">
                    <a href="{{ route('gacha_category', $gacha->category->code_name ) }}"
                    class="btn btn-light border-dark rounded-pill w-100"
                    >{{ '他のガチャを選ぶ' }}</a>
                </div>
                <div class="col-md">
                    <button class="btn btn-light border-dark rounded-pill w-100">取得カード一覧を見る</button>
                </div>
            </div>
        </div>
    </section>
@endsection
