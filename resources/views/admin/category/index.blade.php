@extends('admin.layouts.app')


@section('title','カテゴリー')


@section('meta') @php
$active_key = 'category';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">カテゴリー</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">カテゴリー</h2>

        <a href="{{ route('admin.category.create') }}"
        class="btn btn-primary text-white shadow">
        <i class="bi bi-plus-lg"></i>
        {{'新規登録'}}
        </a>

        <section class="card card-body bg-white my-3 overflow-auto">
            <table class="table mb-0">
                <tbody>
                    <tr class="bg-light">
                        <th scope="col">公開</th>
                        <th scope="col">カテゴリー名</th>
                        <th scope="col">コード</th>
                        <th scope="col">公開中ガチャ</th>
                        <th scope="col"></th>
                    </tr>

                    <!--すべて-->
                    <tr>
                        <th scope="row"> </th>

                        <!-- カテゴリー名 -->
                        <td>{{ 'すべて' }}</td>

                        <!-- コード -->
                        <td>{{ 'all' }}</td>

                        <!-- 公開中ガチャ -->
                        <td></td>

                        <td style="width:8rem;"><div class="row justify-content-start g-2">
                            <div class="col-auto">
                                <!--編集ボタン-->
                                <a href="{{ route('admin.category.all_edit') }}"
                                class="btn btn-sm btn-light border "
                                ><i class="bi bi-pencil-fill"></i></a>
                            </div>
                        </div></td>

                    </tr>

                    <!--カスタマイズ-->
                    @foreach ($gacha_categories as $gacha_category)
                    <tr>
                        <th scope="row">
                            @if( $gacha_category->is_published )
                                <!--公開-->
                                <span class="badge rounded-pill bg-primary">{{ '公開中' }}</span>
                            @else
                                <!--非公開-->
                                <span class="badge rounded-pill bg-danger">{{ '非公開' }}</span>
                            @endif
                        </th>

                        <!-- カテゴリー名 -->
                        {{-- <td><a href=""
                        >{{ $gacha_category->name }}</a></td> --}}
                        <td>{{ $gacha_category->name }}</td>

                        <!-- コード -->
                        <td>{{ $gacha_category->code_name }}</td>

                        <!-- 公開中ガチャ -->
                        <td>{{ $gacha_category->published_gachas->count() }}</td>

                        <td style="width:8rem;"><div class="row justify-content-start g-2">
                            <div class="col-auto">
                                <!--編集ボタン-->
                                <a href="{{ route('admin.category.edit',$gacha_category) }}"
                                class="btn btn-sm btn-light border "
                                ><i class="bi bi-pencil-fill"></i></a>
                            </div>
                            <div class="col-auto">
                                <!--削除モーダル-->
                                <form action="{{ route('admin.category.destroy', $gacha_category) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <delete-modal-component
                                    index_key="{{'delete'.$gacha_category->id}}"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-sm btn-light border ">
                                        <div>
                                            <span class="fw-bold">『{{$gacha_category->name}}』</span>を削除します。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>
                                </form>
                            </div>
                        </div></td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

        </section>
    </div>
@endsection
