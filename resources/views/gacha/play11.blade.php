<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>取得中... - {{env('APP_NAME')}}</title>

    <!-- ファビコン画像の読み込み -->
    <link rel="shortcut icon" href="{{asset('storage/site/image/favicon.png')}}">
    <!-- bootstrap アイコン の読み込み-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <!-- bootstrap CSS の読み込み-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animation.css') }}" rel="stylesheet">

</head>
<body style="background: black;">
    <div id="app" class="mx-auto" style="height:100vh; min-width:100vw;">
        <div class="d-flex align-items-center align-items-center h-100 w-100 bg-">

            <div class="section_video w-100">

                <!-- 動画mobile -->
                <div class="d-md-none">
                    <video id="mobileVideo"
                        controls
                        playsinline
                        muted
                        width="100%"
                        poster=""
                    ><source src="{{ $movie_path['mobile'] }}" ></source>
                    </video>
                </div>
                <!-- 動画PC -->
                <div class="d-none d-md-block">
                    <video id="pcVideo"
                        controls
                        playsinline
                        muted
                        width="100%"
                        poster=""
                    ><source src="{{ $movie_path['pc'] }}" ></source></video>
                </div>
            </div>

            <!-- 音声切り替えボタン -->
            <div class="position-fixed top-0 start-0 p-3">
                <button onclick="switchMuted()"
                    id="muteButton" class="btn btn-light btn-sm float-right py-0 fs-3">
                    <i id="volumeIcon" class="bi bi-volume-up-fill"></i>
                </button>
            </div>

            <!--スキップボタン-->
            @php $params = [
                'category_code'=>$user_gacha_history->gacha->category->code_name,
                'user_gacha_history'=>$user_gacha_history
            ]; @endphp
            <div class="position-fixed bottom-0 end-0 p-3">
                <form action="{{ route('gacha.result', $params ) }}"
                id="skipForm" method="post">
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

    <!-- 普通のJavaScript -->
    <script>
        'use strict';

        const videos = document.querySelectorAll('video');
        const form = document.querySelector('form');

        const autoPlay = function() {
            videos.forEach(video => {
                // 動画が再生された後にフォーム送信
                video.addEventListener('ended', function() {
                    // フォーム送信
                    form.submit();
                });

                // メディアの再生を開始
                video.play();
            });
        }

        // 音声再生切り替えボタン
        const switchMuted = function() {
            videos.forEach(video => {
                // 音声再生
                video.muted = !video.muted;
            });
        }

        window.onload = function() {
            // コンファームを表示
            var userAnswer = window.confirm("音声が出ます。よろしいですか？");

            // ユーザーの回答によって処理を分岐
            if (userAnswer) {
                switchMuted(); // ミュート状態を解除しておく
            }
            autoPlay();

        };
    </script>    <!-- bootstrap JavaScript -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
</body>
</html>

