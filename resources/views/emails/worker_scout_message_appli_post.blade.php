<!DOCTYPE html>
    <html lang="ja">
    <head>
        @include('emails._html_css')
        @php $route = route('company.under_selection.worker_progress',$scout_send_worker); @endphp
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
            <h3 class="">求人応募が届きました。</h3>
        </div>
        <div class="mail-container border-bottom">
            <strong>{{$worker->name}}</strong> さんから求人応募が届きました。<br>
            <br>
            <a href="{{$route}}">選考ページ</a>から詳細をご確認のうえ、日程調整のご連絡をお願いします。
            <br><br>
        </div>
        <!--面接希望日時・応募者からのメッセージ-->
        <div class="mail-container border-bottom">
            {{-- <strong>面接希望日時</strong>
            <div class="bg-light-primary">
                @php
                $labels = [1=>'第一希望',2=>'第二希望',3=>'第三希望'];
                $weeks  = ['（日）','（月）','（火）','（水）','（木）','（金）','（土）'];;
                @endphp
                @for ($num = 1; $num < 4; $num++)
                    @php $carbon =\Carbon\Carbon::parse( $scout_selection['interview_date0'.$num] ); @endphp

                    <strong>{{ $labels[ $num ] }}</strong> <br>
                    <div>
                        <span>{{ $carbon->format('Y年m月d日').$weeks[ $carbon->format('w') ] }}</span>

                        @php $carbon =\Carbon\Carbon::parse( $scout_selection['interview_start_time0'.$num] ); @endphp
                        <span>{{ $carbon->format('H:i') }}</span>

                        <span>～</span>

                        @php $carbon =\Carbon\Carbon::parse( $scout_selection['interview_end_time0'.$num] ); @endphp
                        <span>{{ $carbon->format('H:i') }}</span>
                    </div>
                @endfor
            </div> --}}
            <br><br>


            <strong>応募者からのメッセージ</strong>
            <div class="bg-light-primary">{!! nl2br( e( $scout_selection->worker_message_text ) ) !!}</div>
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
</html>

