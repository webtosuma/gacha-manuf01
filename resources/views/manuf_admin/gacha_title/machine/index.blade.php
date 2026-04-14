@extends('manuf_admin.layouts.gacha_title')


@section('title',$gacha_title->name.' 筐体一覧')


@section('meta') @php
$active_key = 'gacha_title.machine';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection



@section('content')


    {{-- パンくずリスト --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
            >{{ 'Top' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title') }}"
            >{{ 'ガチャタイトル一覧' }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gacha_title.show',$gacha_title) }}"
            >{{$gacha_title->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'筐体一覧'}}</li>
        </ol>
    </nav>


    <div class="mb-3">
        <a href="{{ route('admin.gacha_title.machine.create',$gacha_title) }}"
        class="btn btn-primary text-white  rounded-pill shadow"
        ><i class="bi bi-plus-lg me-2"></i>新規登録</a>
    </div>


    @if( $machines->count() )
        <div class="border rounded overflow-hidden mb-4">
            <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
                <tbody>
                    @foreach ($machines as $machine)
                    <tr>


                        <!--公開-->
                        <td style="width:3.2rem;" class="text-center">
                            @if( $machine->is_published )
                                <span class="form-text text-success">公開</span>
                            @else
                                <span class="form-text">非公開</span>
                            @endif
                        </td>

                        <!-- 商品情報 -->
                        <td>
                            <a href="{{ $machine->r_admin_show }}"
                            class="d-block">
                                <div class="fw-bold">
                                    {{ $machine->name }}
                                </div>

                                <div class="text-muted small">
                                    {{ $machine->key }}
                                </div>

                                <div class="text-dark mt-2">
                                    残りxxx/待機中xxx
                                </div>
                            </a>
                        </td>

                        <td style="width:6rem;">
                            <div class="d-flex gap-1">

                                <!--編集ボタン-->
                                <a href="{{ $machine->r_admin_edit }}"
                                class="btn btn-light border"
                                ><i class="bi bi-pencil-fill"></i></a>

                                <!--コピーボタン-->
                                <form
                                action="{{ $machine->r_admin_copy }}"
                                method="post"
                                class="d-inline-block"
                                >
                                    @csrf

                                    <delete-modal-component
                                    index_key="{{'copy-'.$machine->id}}"
                                    icon="bi-files"
                                    func_btn_type="submit"
                                    color="warning"
                                    button_class="btn btn-light border">
                                        <div>
                                            <span class="fw-bold">『{{$machine->name}}』をコピーします。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>

                                </form>



                                <form
                                action="{{ $machine->r_admin_destroy }}"
                                method="post"
                                class="d-inline-block"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <delete-modal-component
                                    index_key="{{'delete-'.$machine->id}}"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-light border">
                                        <div>
                                            <span class="fw-bold">『{{$machine->name}}』を削除します。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>

                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection
