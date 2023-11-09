<!DOCTYPE html>
    <html lang="ja">
    <head>
        @include('emails._html_css')
    </head>
    <body>
        <header>
            <div class="mail-container">
                <div class="top-logo">
                    <a href="{{route('home')}}">
                        <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ env('APP_NAME') }}">
                    </a>
                </div>
            </div>
        </header>
        <div class="mail-container  border-bottom">
            <!--Title-->
            <h3 class="">スカウトが届きました。</h3>
        </div>
        <div class="mail-container">
            {{$worker->name}} さんに興味をお持ちの求人企業からスカウトが届きました。<br>
            <br>


            有効期限内にご連絡をお願いします。
            <br><br>
        </div>
        <!--スカウトメッセージ-->
        <div class="mail-container border-bottom">
            <strong>スカウトメッセージ</strong>
            <div class="bg-light-primary">{!! nl2br( e( $scout_message->messageReplaceText( $worker->name ) ) ) !!}</div>
            <br><br>
        </div>
        <!--求人情報-->
        <div class="mail-container border-bottom">
            @php $recruit = $scout_message->recruit; @endphp
            <strong>求人票</strong>

            <div class="center-image">
                <img src="{{$recruit->image_path}}" alt="スカウト画像">
            </div>
            <!--スカウトタイトル-->
            <h3 class="mb-0">
                @if($scout_send_worker->id)
                    <a href="{{ route('scout_message.preview',$scout_send_worker) }}">{{ $scout_message->header }}</a>
                @else
                    {{ $scout_message->header }}
                @endif
            </h3>
            <div class="">
                <span class="me-2"><!--職種-->
                    <i class="bi bi-briefcase"></i>
                    <span>{{$recruit->occupation}}</span>
                </span>
                <span class="me-2"><!--想定年収-->
                    <i class="bi bi-currency-yen"></i>
                    <span>{{$recruit->annual_income}}万</span>
                </span>
                <span class="me-2"><!--勤務地-->
                    <i class="bi bi-geo-alt"></i>
                    <span>{{$recruit->work_location}}</span>
                </span>
            </div>
            <br><br>
        </div>
        <div class="mail-container">
            <p class="text-center">
                <a class="btn"
                href="{{route('scout_message.scout_list')}}"
                >全てのスカウトの確認は、こちら</a>
            </p>
        </div>
        <br>
        <!-- 共通署名 -->
        @include('emails._html_footer')

    </body>
</html>

