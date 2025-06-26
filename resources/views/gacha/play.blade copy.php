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
        <div id="app" >

            @php
            $params = [
                'category_code'=>$user_gacha_history->gacha->category->code_name,
                'user_gacha_history'=>$user_gacha_history,
            ];
            @endphp


            <u-movie-play
            token="{{ csrf_token() }}"
            movie_path_mobile="{{ $movie_path['mobile'] }}"
            movie_path_pc="{{ $movie_path['pc'] }}"
            r_action="{{ route('gacha.result', $params )}}"
            rank_up="{{ $rank_up ? 1 : 0}}"
            ></u-movie-play>

            {{-- rank_up="{{ isset($rank_up)&&$rank_up ? 1 : 0}}" --}}

        </div>
    </main>
    <!-- bootstrap JavaScript -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>
</html>

