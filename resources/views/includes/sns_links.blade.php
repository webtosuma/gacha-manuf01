<div class="my- d-flex justify-content-center">
    @if( config('sns.twitter') )
        <a href="https://twitter.com/{{config('sns.twitter')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
            alt="xロゴ" class="d-block p-2" style=" width:2rem; height:2rem;">
        </a>
    @endif
    @if( config('sns.note') )
        <a href="https://note.com/{{config('sns.note')}}"
        rel="nofollow" target="_blank" class="col-auto">
        <img src="{{asset('storage/site/image/note-logo/main/icon.png')}}"
        alt="noteロゴ" class="d-block p-" style=" width:2rem; height:2rem;">
        </a>
    @endif
    @if( config('sns.instagram') )
        <a href="https://www.instagram.com/{{config('sns.instagram')}}"
        rel="nofollow" target="_blank" class="col-auto">
        <img src="{{asset('storage/site/image/instagram-logo/01/gradient.png')}}"
        alt="インスタグラムロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
        </a>
    @endif
    @if( config('sns.tiktok') )
        <a href="https://www.tiktok.com/{{config('sns.tiktok')}}"
        rel="nofollow" target="_blank" class="col-auto">
        <img src="{{asset('storage/site/image/tiktok-icons/black_circle.png')}}"
        alt="tiktokロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
        </a>
    @endif
</div>
