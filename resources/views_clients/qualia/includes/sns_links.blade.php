<div class="my- d-flex gap-2 justify-content-center mb-">
    @if( config('sns.twitter') )
        <a href="https://twitter.com/{{config('sns.twitter')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/x-white.png')}}"
            alt="xロゴ" 
            class="d-block bg-dark rounded-pill p-2" 
            style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.note') )
        <a href="https://note.com/{{config('sns.note')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/note-white.png')}}"
            alt="noteロゴ" 
            class="d-block bg-dark rounded-pill p-2" 
            style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.instagram') )
        <a href="https://www.instagram.com/{{config('sns.instagram')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/instagram-white.png')}}"
            alt="インスタグラムロゴ" 
            class="d-block bg-dark rounded-pill p-2"  
            style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.tiktok') )
        <a href="https://www.tiktok.com/{{config('sns.tiktok')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/tiktok.png')}}"
            alt="tiktokロゴ" 
            class="d-block bg-dark rounded-pill p-2"  
            style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.line') )
        <a href="https://lin.ee/{{config('sns.line')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/line-logo/success.png')}}"
            alt="lineロゴ" 
            class="d-block bg-dark rounded-pill p-2"  
            style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif
    @if( config('sns.youtube') )
        <a href="https://www.youtube.com/{{config('sns.youtube')}}"
        rel="nofollow" target="_blank" class="col-auto">
            <img src="{{asset('storage/site/image/icon/youtube-white.png')}}"
            alt="youtubeロゴ" 
            class="d-block bg-dark rounded-pill p-2"  
            style=" width:2.6rem; height:2.6rem;">
        </a>
    @endif

</div>
