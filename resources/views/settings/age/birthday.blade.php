@extends('layouts.sub')

<!----- title ----->
@section('title','誕生日登録')


@section('script')
    <!----- animation ----->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            delay: 100,/*発火までの秒数 (ms)*/
            duration: 1200,/*アニメーション時間 (ms)*/
            once: false,/*発火を1回のみにする*/
            placement:"top-top"/*発火位置:画面中央*/
        });
    </script>
@endsection



@section('content')

<div class="container my-md-5">
    <div class="d-flex align-items-center justify-content-center bg-" style="height: 80vh;">


        <form action="{{route('settings.age.birthday.update')}}" method="POST" class="w-100">
            @csrf
            @method('PATCH')
            <input type="hidden" name="name"  value="{{Auth::user()->name}}">
            <input type="hidden" name="email" value="{{Auth::user()->email}}">



            <div
            data-aos="zoom-inin"
            class="text-center mx-auto" style="max-width:600px;" >

                <div class="mb-3">年齢確認が必要なページです。</div>
                <h5 class="fw-bold fs-4 mb-5">あなたの誕生日を入力してください</h5>


                <div class="mb-5" >

                    @include('settings.age._input_birthday')

                </div>


                <button type="submit" class="btn btn-lg btn-outline-primary rounded-pill w-100">決定</button>

            </div>
        </form>

    </div>
</div>


@endsection
