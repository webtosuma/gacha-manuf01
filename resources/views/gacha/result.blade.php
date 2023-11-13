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


                @for ($i = 0; $i < 10; $i++)
                    <div class="col-3 col-md-3">
                        <div class="d-flex align-items-center justify-content-center h-100">


                            <label class="w-100">
                                <!--カード名-->
                                {{-- <div class="text-center text-white fw-bold" style="font-size:11px;"
                                >すごいルフィー...</div> --}}

                                <div class="position-relative">
                                    <!--チェックボックス-->
                                    <div class="position-absolute top-0 start-0" style="z-index:100">
                                        <input class="form-check-input float-xl-none m-0"
                                        type="checkbox" name="" value="">
                                    </div>

                                    <!--カード画像-->
                                    <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded-3"
                                    url="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSZiXl24gnOwOvXztlKMck7B5PMSPBdsRhvzg&usqp=CAU"
                                    ></ratio-image-component>
                                </div>

                                <!--ポイント表示-->
                                <div >
                                    <div class="bg-dark text-warning fw-bold text-center mt-1 px-1 rounded">
                                        100pt
                                    </div>
                                </div>
                            </label>


                        </div>
                    </div>
                @endfor

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
            <div class="row g-3">
                <div class="col-md">
                    <button class="btn btn-light border-dark rounded-pill w-100">同じガチャでガチャる</button>
                </div>
                <div class="col-md">
                    <button class="btn btn-light border-dark rounded-pill w-100">別のガチャでガチャる</button>
                </div>
                <div class="col-md">
                    <button class="btn btn-light border-dark rounded-pill w-100">取得カード一覧を見る</button>
                </div>
            </div>
        </div>
    </section>
@endsection
