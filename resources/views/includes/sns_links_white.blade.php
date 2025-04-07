
<div class="my- d-flex justify-content-center mb-5">
    @if( config('sns.twitter') )
        <a href="https://twitter.com/{{config('sns.twitter')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
            alt="xロゴ" class="d-block p-2" style=" width:2rem; height:2rem;">
        </a>
    @endif
    @if( config('sns.note') )
        <a href="https://note.com"
        rel="nofollow" target="_blank" class="col-auto">
        <img src="{{asset('storage/site/image/note-logo/sub/icon.png')}}"
        alt="noteロゴ" class="d-block p-" style=" width:2rem; height:2rem;">
        </a>
    @endif
    @if( config('sns.instagram') )
        <a href="https://www.instagram.com"
        rel="nofollow" target="_blank" class="col-auto">
        <img src="{{asset('storage/site/image/instagram-logo/02/white.png')}}"
        alt="インスタグラムロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
        </a>
    @endif
    @if( config('sns.tiktok') )
        <a href="https://www.tiktok.com"
        rel="nofollow" target="_blank" class="col-auto">
        <img src="{{asset('storage/site/image/tiktok-icons/black_square.png')}}"
        alt="tiktokロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
        </a>
    @endif
</div>
