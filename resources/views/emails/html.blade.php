<!DOCTYPE html>
<html lang="ja">
<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{--
        /*
         @ padding　使用不可
         @ rem 使用不可
         @ colorは#------
        */

    --}}
    <style>
        .top-logo{
            width: 160px;
            margin: 0 auto;
        }
        img{
            display: block;
            width: 100%;
        }
        .copy{
            margin: 16px 0;
            text-align: center;
            color: #a3b6c2;
        }
        .border-bottom{ border-bottom: 1px solid #a3b6c2; }
        .text-end{ text-align: right; }
        .mail-container{
            width: 600px;
            margin: 32px auto;
        }
        .bg-light-text{
            background-color: #e0e7eb;
            padding: 16px;
        }
        .btn{
            display: inline-block;
            font-weight: 400;
            line-height: 1.6;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            user-select: none;
            border: none;
            padding: 8px 16px;
            font-size: 18px;
            border-radius: 4.8px;
            color: #000000;
            background-color: #00b8e6;
            width: 100%;
            margin: 16px 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="mail-container border-bottom">
            <div class="top-logo">
                <img src="{{asset('storage/site/image/logo.png')}}" alt="...">
            </div>
            <p class="text-end">
                <small>2023/01/15 発行</small>
            </p>
        </div>
    </header>
    <div class="mail-container border-bottom">
        <h3>
            未読のスカウトおまとめ便
        </h3>
        <p>
            hogehoge <br>
            hogehoge <br>
            hogehoge <br>
            hogehoge <br>
        </p>
    </div>
    <div class="mail-container border-bottom">
        <p>
            hogehoge <br>
            hogehoge <br>
            hogehoge <br>
            hogehoge <br>
        </p>
    </div>
    <div class="mail-container border-bottom">
        <p>
            hogehoge <br>
            hogehoge <br>
            hogehoge <br>
            hogehoge <br>

            <a href="" class="btn btn-primary btn-lg">ボタン</a>
        </p>
    </div>

    <footer>
        <div class="mail-container">
            <div class="bg-light-text">
                hogehoge <br>
                hogehoge <br>
                hogehoge <br>
                hogehoge <br>
            </div>
            <div class="copy">&copy; TOSUMA Co.,Ltd.</div>
        </div>
    </footer>
</body>
</html>


{{-- <!DOCTYPE html>
<html lang="ja">
<style>
  .container{
    background-color: pink;

  }
  h1 {
    font-size: 16px;
    color: #ff6666;
  }
  #button {
    width: 200px;
    text-align: center;
  }
  #button a {
    padding: 10px 20px;
    display: block;
    border: 1px solid #2a88bd;
    background-color: #FFFFFF;
    color: #2a88bd;
    text-decoration: none;
    box-shadow: 2px 2px 3px #f5deb3;
  }
  #button a:hover {
    background-color: #2a88bd;
    color: #FFFFFF;
  }
</style>
<body>
    <div class="container">
        <h1>
            Sample Notification!
          </h1>
          <p>
            A sample notification has been sent.
          </p>
          <p>
            hogehoge
          </p>
          <p id="button">
            <a href="https://www.google.co.jp">リンクのテスト</a>
          </p>
    </div>
</body>
</html> --}}
