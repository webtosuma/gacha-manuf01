<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>YouTube Player</title>
    <script src="https://www.youtube.com/iframe_api"></script>
    <style>
        #player {
            margin-top: 20px;
        }
        button {
            margin: 5px;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>YouTube Shorts プレイヤー</h1>

    <div id="player"></div>

    <div>
        <button onclick="playVideo()">再生（音なし）</button>
        <button onclick="pauseVideo()">一時停止</button>
        <button onclick="stopVideo()">停止</button>
        <button onclick="playWithSound()">再生（音あり）</button>
        <button onclick="redirectNow()">今すぐリダイレクト</button>
    </div>

    <script>
        let player;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: '360',
                width: '640',
                videoId: '8ukVH36m50E',
                playerVars: {
                    autoplay: 0,
                    controls: 1,
                    modestbranding: 1
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        function onPlayerReady(event) {
            console.log("プレイヤー準備完了");
        }

        function onPlayerStateChange(event) {
            console.log("状態変更:", event.data);

            // 動画終了時（YT.PlayerState.ENDED = 0）にリダイレクト
            if (event.data === YT.PlayerState.ENDED) {
                window.location.href = "https://www.youtube.com/";
            }
        }

        function playVideo() {
            player.mute();       // ミュートしてから再生
            player.playVideo();
        }

        function pauseVideo() {
            player.pauseVideo();
        }

        function stopVideo() {
            player.stopVideo();
        }

        function playWithSound() {
            player.unMute();     // ミュート解除してから再生
            player.playVideo();
        }

        function redirectNow() {
            window.location.href = "https://www.youtube.com/";
        }
    </script>
</body>
</html>
