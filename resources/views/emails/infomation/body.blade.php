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
            <small>{{ now()->format('Y/m/d') }} 発行</small>
        </p>
        <p>
            {!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $infomation->body_text) )!!}
        </p>
    </div>
</section>
<section>
    <div class="mail-container mail-border-bottom">
        <p>
            <a href="{{route('login')}}" class="mail-btn mail-btn-primary mail-btn-lg">ログインはこちら</a>

        </p>
        <br>
        <p>
            @include('emails._signature')
        </p>
    </div>
</section>
<section>
    <div class="mail-container mail-bg-white">
        <div class="mail-center-image">
            <a href="{{route('home')}}">
                <img class="mail-img" src="{{asset('storage/site/image/logo.png')}}" alt="サイトロゴ">
            </a>
        </div>

        <div class="mail-copy">&copy; {{config('app.company_name')}}</div>
    </div>
</section>
