@extends('admin.layouts.app')


@section('title','管理者新規登録')


@section('meta') @php
$active_key = 'register';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.register')}}">
                    管理者
                </a></li>
                <li class="breadcrumb-item active" aria-current="page">新規登録</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">管理者新規登録</h2>


        <section class="mx-auto my-5" style="max-width:900px;">
            <div class="card">


                <h5 class="card-header">
                    登録項目を入力してください
                </h5>

                <form method="post" action="{{ route('admin.register.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="card-body row g-3">

                        <div class="col-12">
                            <label for="username" class="form-label fw-bold">名前</label>
                            <input type="text" name="name" class="form-control" id="username"
                                value="{{old('name')}}" placeholder="名前" required>
                            <p style="height:1em;color:red;">
                                {{$errors->has('name')? $errors->first('name'): ''}}
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label fw-bold">メールアドレス</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{old('email')}}" placeholder="メールアドレス">
                            <p style="height:1em;color:red;">
                                {{$errors->has('email')? $errors->first('email'): ''}}
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-bold">パスワード</label>
                            <input type="password" name="password" class="form-control" id="address"
                                value="{{old('password')}}" placeholder="8文字以上、半角英数字のみ" required>
                            <p style="height:1em;color:red;">
                                {{$errors->has('password')? $errors->first('password'): ''}}
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-bold">パスワード(確認用)</label>
                            <input type="password" name="password_confirmation" class="form-control" id="address"
                                value="" placeholder="8文字以上、半角英数字のみ" required>
                            <p style="height:1em;color:red;">
                                {{$errors->has('conf_password')? $errors->first('conf_password'): ''}}
                            </p>
                        </div>


                        <div class="col-12 col-md-8 mx-auto mt-5">
                            <disabled-button style_class="btn btn-primary text-white w-100"
                            btn_text="登録する"></button>
                        </div>


                    </div>
                </form>


            </div>
        </section>
    </div>
@endsection
