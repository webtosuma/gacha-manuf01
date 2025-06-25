<div class="my- d-flex justify-content-center mb-5">
    @if( config('sns.twitter') )
        <a href="https://twitter.com/{{config('sns.twitter')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/x-black.png')}}"
            alt="xロゴ" class="d-block p-2" style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.note') )
        <a href="https://note.com"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/note-black.png')}}"
            alt="noteロゴ" class="d-block p-2" style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.instagram') )
        <a href="https://www.instagram.com"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/instagram-black.png')}}"
            alt="インスタグラムロゴ" class="d-block p-1"  style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.tiktok') )
        <a href="https://www.tiktok.com"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/tiktok.png')}}"
            alt="tiktokロゴ" class="d-block p-1"  style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
</div>
