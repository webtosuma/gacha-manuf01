<!DOCTYPE html>
    <html lang="ja">
    <head>
        @include('emails._html_css')
        @php $route = route('scout_selection.preview',$scout_selection); @endphp
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
        <div class="mail-container border-bottom">
            <!--Title-->
            <h3 class="">選考結果が届きました。</h3>
        </div>
        <div class="mail-container border-bottom">
            {{$company_user->company->name}}から選考結果が届きました。<br>
            <br>
            <a href="{{$route}}">選考ページ</a>から詳細をご確認ください。
            <br><br>
        </div>


        <!--求人情報-->
        <div class="mail-container border-bottom">
            @php $recruit = $scout_message->recruit; @endphp
            <strong>応募求人</strong>
            <div class="center-image">
                <img src="{{$recruit->image_path}}" alt="スカウト画像">
            </div>

            <!--スカウトタイトル-->
            <h3 class="mb-0">
                {{ $scout_message->header }}
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
                <a class="btn" href="{{$route}}" >選考ページはこちら</a>
            </p>
        </div>
        <br>
        <!-- 共通署名 -->
        @include('emails._html_footer')

    </body>
