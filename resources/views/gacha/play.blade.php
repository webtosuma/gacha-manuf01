@extends('layouts.movie')

<!--title-->
@section('title','取得中...')

@section('script')
<script>
    'use strict';
    const video = document.querySelector('video');
    const form  = document.querySelector('form');

    // 動画が再生された後にフォーム送信
    video.addEventListener('ended', function() {
        form.submit();
    });
    // メディアの再生を開始
    video.play();
</script>
@endsection


@section('content')
    {{-- <div class="bg-white p-3">{{ 'テスト中...'.$movie_path }}</div> --}}

    <div class="mx-auto p-3" style="max-width:600px; height:100vh;">
        <div class="position-relative
        d-flex align-items-center align-items-center h-100 bg-">


            <!-- 動画 -->
            <div class="section_video">
                <div class="video-area">
                    <video class="bg_video"
                    playsinline
                    muted width="100%" height=""
                    poster="{{asset('storage/site/image/video.png')}}"
                    >
                        <source src="{{ $movie_path }}"></source>
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
