@extends('layouts.movie')

<!--title-->
@section('title','取得中...')

@section('script')
<script>
    'use strict';
    const videos = document.querySelectorAll('video');
    const form  = document.querySelector('form');

    videos.forEach(video => {
        // 動画が再生された後にフォーム送信
        video.addEventListener('ended', function() {
            form.submit();
        });
        // メディアの再生を開始
        video.play();
    });
</script>
@endsection


@section('content')
    {{-- <div class="bg-white p-3">{{ 'テスト中...'.$movie_path['mobie'] }}</div> --}}

    <div class="mx-auto"style="height:100vh; min-width:100vw;">
        <div class="position-relative
        d-flex align-items-center align-items-center h-100 bg-">


            <div class="section_video w-100">
                <!-- 動画mobile -->
                <div class="video-area d-md-none">
                    <video class="bg_video"
                    playsinline
                    width="100%" height=""
                    poster=""
                    ><source src="{{ $movie_path['mobile'] }}"></source>
                    </video>
                </div>
                <!-- 動画PC -->
                <div class="video-area d-none d-md-block">
                    <video class="bg_video"
                    playsinline
                    width="100%" height=""
                    poster=""
                    ><source src="{{ $movie_path['pc'] }}"></source>
                    </video>
                </div>
            </div>

            <!--スキップボタン-->
            <div class="position-absolute top-0 end-0 p-3">
                @php $params = [
                    'category_code'=>$user_gacha_history->gacha->category->code_name,
                    'user_gacha_history'=>$user_gacha_history
                ]; @endphp

                <form action="{{ route('gacha.result', $params ) }}" method="post">
                    @csrf
                    <button type="submit"
                    class="btn btn-light btn-sm flort-right">動画をスキップ >> </button>
                </form>
            </div>


        </div>
    </div>
@endsection
