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

    muteButton.addEventListener('click', function() {
        videos.forEach(video => {
            // ミュートの状態を切り替える
            video.muted = !video.muted;
        });
    });
</script>
@endsection


@section('content')
    {{-- <div class="bg-white p-3">{{ 'テスト中...'.$movie_path['mobie'] }}</div> --}}

    <div class="mx-auto"style="height:100vh; min-width:100vw;">
        <div class="d-flex align-items-center align-items-center h-100 w-100 bg-">


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



            <!-- 音声切り替えボタン -->
            <div class="position-fixed top-0 start-0 p-3">
                <button id="muteButton" class="btn btn-light btn-sm float-right py-0"
                ><i class="bi bi-volume-up-fill fs-3"></i></button>
            </div>


            <!--スキップボタン-->
            <div class="position-fixed bottom-0 end-0 p-3">
                @php $params = [
                    'category_code'=>$user_gacha_history->gacha->category->code_name,
                    'user_gacha_history'=>$user_gacha_history
                ]; @endphp

                <form action="{{ route('gacha.result', $params ) }}" method="post">
                    @csrf
                    <button type="submit"
                    class="btn btn-light btn-sm flort-right py-0">
                        <div class="d-flex justify-content-center align-items-center">
                            <span>演出をスキップ</span>
                            <i class="bi bi-skip-end-fill fs-3"></i>
                        </div>
                    </button>
                </form>
            </div>


        </div>
    </div>
@endsection
