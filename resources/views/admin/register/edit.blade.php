@extends('admin.layouts.app')


@section('title','管理者編集')


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
                <li class="breadcrumb-item active" aria-current="page">編集</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">管理者編集</h2>


        <section>
            <div class="mx-auto my-5" style="max-width:900px;">

                <div class="card">

                    <h5 class="card-header">
                        管理者情報
                    </h5>
                    <form method="post" action="{{ route('admin.register.update', $edit_admin) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="admin_id" value="{{$edit_admin->id}}">
                        <div class="card-body row g-0">

                            <div class="col-12">
                                <label for="edit_admin_name" class="form-label fw-bold">名前</label>
                                <input type="text" name="name" class="form-control" id="edit_admin_name"
                                    value="{{old('name', $edit_admin->name)}}" placeholder="名前" required>
                                <p class="text-danger">
                                    {{$errors->has('name')? $errors->first('name'): ''}}
                                </p>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label fw-bold">メールアドレス</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{old('email', $edit_admin->email)}}" placeholder="メールアドレス" required>
                                <p class="text-danger">
                                    {{$errors->has('email')? $errors->first('email'): ''}}
                                </p>
                            </div>


                            <div class="col-12">
                                <button class="btn btn-primary text-white" type="submit">更新</button>
                            </div>


                        </div>
                    </form>


                </div>

            </div>
        </section>

        <section>
            <div class="mx-auto my-5" style="max-width:900px;">

                <div class="card">
                    <h5 class="card-header">
                        パスワード変更
                    </h5>

                    <form method="post" action="{{ route('admin.register.update', $edit_admin) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="admin_id" value="{{$edit_admin->id}}">
                        <div class="card-body row g-0">


                            <div class="col-12">
                                <label for="address" class="form-label fw-bold">パスワード</label>
                                <input type="password" name="password" class="form-control" id="address"
                                    value="{{old('password')}}" placeholder="8文字以上、半角英数字記号のみ" required>
                                <p class="text-danger">
                                    {{$errors->has('password')? $errors->first('password'): ''}}
                                </p>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label fw-bold">パスワード(確認用)</label>
                                <input type="password" name="password_confirmation" class="form-control" id="address"
                                    value="" placeholder="8文字以上、半角英数字記号のみ" required>
                                <p class="text-danger">
                                    {{$errors->has('conf_password')? $errors->first('conf_password'): ''}}
                                </p>
                            </div>



                            <div class="col-12">
                                <button class="btn btn-primary text-white" type="submit">更新</button>
                            </div>


                        </div>
                    </form>


                </div>

            </div>
        </section>

        <section>
            <div class="mx-auto my-5" style="max-width:900px;">

                <div class="card">
                    <h5 class="card-header">
                        その他設定変更
                    </h5>

                    <form method="post" action="{{ route('admin.register.update', $edit_admin) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="admin_id" value="{{$edit_admin->id}}">
                        <input type="hidden" name="form-switch" value="その他設定変更フォーム">
                        <div class="card-body row g-2">


                            <!-- メール連絡受取り設定 -->
                            <div class="col-12">
                                <label class="form-check-label fw-bold" for="get_mail">メール連絡受取り設定</label>

                                <div class="d-flex align-items-end mb-3">
                                    <div style="width:7rem;">受け取らない</div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input fs-3" type="checkbox" name="get_mail" id="get_mail"
                                        {{ $edit_admin->get_mail ? 'checked' : ''}}
                                        >
                                    </div>
                                    <div class="">受け取る</div>
                                </div>
                            </div>


                            <!-- 管理者修正権限(※自分以外の管理権限者のみ修正可) -->
                            @if (Auth::user()->admin->master)
                            <div class="col-12">
                                <label class="form-check-label" for="master">
                                    <span class="fw-bold">管理者修正権限</span>
                                    <span class="ms-2">※自分以外の管理権限者のみ修正可</span>
                                </label>

                                <div class="d-flex align-items-end mb-3">
                                    <div style="width:7rem;">なし</div>
                                    <div class="form-check form-switch ms-3">
                                        <input class="form-check-input fs-3" type="checkbox" name="master" id="master"
                                        {{ $edit_admin->master ? 'checked' : '' }}
                                        {{ ( !Auth::user()->admin->master or Auth::user()->admin->id===$edit_admin->id ) ? 'disabled' : '' }}
                                        >
                                    </div>
                                    <div class="">あり</div>
                                </div>

                                <!-- 管理者修正権限が修正可のとき、) -->
                                @if (!Auth::user()->admin->master or Auth::user()->admin->id===$edit_admin->id)
                                    <input type="hidden" name="master" value="on">
                                @endif
                            </div>
                            @endif

                            <div class="col-12">
                                <button class="btn btn-primary text-white" type="submit">更新</button>
                            </div>


                        </div>
                    </form>

                </div>

            </div>
        </section>
    </div>
@endsection
