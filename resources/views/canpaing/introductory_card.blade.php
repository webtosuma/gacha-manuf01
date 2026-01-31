<div class="card border-0 rounded-4  bg-white overflow-hidden shadow">


    <img src="{{ $image_path }}"
    class="card-img-top" alt="ご友人紹介キャンペーン">



    {{-- <div class="card-body">
        <h2 class="text-center fs-3 mb-3 fw-bold text-warning">ご友人紹介キャンペーン</h2>
        <p class="text-secondary text-center mb-0 form-text">
            ご友人の「会員登録」と「初回ポイント購入完了」後、紹介ユーザー様とご友人お二人にそれぞれ
            <strong class="fw-bold fs-5 text-warning">{{number_format($point)}}pt</strong>プレゼントいたします。
        </p>
    </div> --}}


    @if (Auth::check())
        <section class="card-body border-top py-4">


            <h5 class="text-center fs-6 fw-bold mb-3">{{Auth::user()->name.'様専用、'}}ご友人紹介登録URL</h5>

            <div class="col-md- mx-auto">
                <coppy-button-component copy_word="{{$url}}"></coppy-button-component>
            </div>


        </section>
    @else
        <section class="card-body border-top">

            <h5 class="text-center fs-3 mb-3 text-primary">ログインはお済みですか？</h5>

            <h5 class="text-center fs-5 mb-3">今すぐログインして、<br>ご友人紹介登録URLをゲットしよう！</h5>

            <div class="col-md-8 mx-auto">
                <a href="{{route('login')}}"
                class="btn btn-lg btn-primary text-white fs-3 w-100 rounded-pill"
                >ログイン/無料会員登録</a>
            </div>


        </section>
    @endif

</div>
