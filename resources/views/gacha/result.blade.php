@extends('layouts.app')

<!--title-->
@section('title','ガチャ結果')

@section('style')
<style>
    #result {
        background: no-repeat center center / cover;
        background-image: url({{asset('storage/site/image/gacha/bg_result.jpg')}});
    }
</style>
@endsection

@section('content')
    <section id="result" style="min-height: 80vh;">

        <div class="container px-3 py-4"  style="max-width:500px;">


            <h2 class="text-secondary fw-bold btn btn-lg w-100 mb-4"
            style="background: rgb(255, 255, 255, .7);"
            >ガチャ結果</h2>

            <!--ポイント交換フォーム-->
            @php $params = [
                'category_code'=>$gacha->category->code_name,
                'user_gacha_history'=>$user_gacha_history
            ]; @endphp
            <form action="{{ route( 'gacha.exchange_points', $params) }}" method="POST">
                @csrf
                @method('PATCH')

                <!--カード一覧-->
                <u-gacha-result-form
                token="{{ csrf_token() }}"
                r_use_gacha_history_show="{{ route('api.use_gacha_history.show',$user_gacha_history) }}"
                ></u-gacha-result-form>

            </form>

        </div>
    </section>
    <section class="py-5 bg-dark border-bottom border-right">
        <div class="container">
            @php
            $params = [
                'category_code'=>$gacha->category->code_name,
                'gacha'=>$gacha,
                'key'=>$gacha->key
            ];
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
                    <a href="{{ route('user_prize') }}"
                        class="btn btn-light border-dark rounded-pill w-100"
                        >{{ '取得した商品一覧を見る' }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection
