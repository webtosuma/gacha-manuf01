@if($infomation->image)
<section>
    <div class="mail-container mail-bg-white">

        <div class="mail-top-logo">
            <img class="mail-img" src="{{ $infomation->image_path }}" alt="お知らせ画像">
        </div>
        <br>
    </div>
</section>
@endif


<section>
    <div class="mail-container mail-bg-white mail-border-bottom">
        <h3>{{ $infomation->title }}</h3>
        <p class="">
            <small>2023/01/15 発行</small>
        </p>
        <p>
            {!! nl2br(e( $infomation->body_text )) !!}
        </p>
    </div>
</section>
<section>
    <div class="mail-container mail-border-bottom">
        <p>
            <a href="{{route('login')}}" class="mail-btn mail-btn-primary mail-btn-lg">ログインはこちら</a>
            <br><br>
        </p>
    </div>
</section>
<section>
    <div class="mail-container mail-bg-white">
        <div class="mail-center-image">
            <a href="{{route('home')}}">
                <img class="mail-img" src="{{asset('storage/site/image/logo192.png')}}" alt="サイトロゴ">
            </a>
        </div>

        <div class="mail-copy">&copy; fobees</div>
    </div>
</section>
