@extends('admin.layouts.app')


@section('title','買取表カテゴリー')


@section('meta') @php
$active_key = 'purchase';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.purchase') }}"
                >{{ '買取表' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">カテゴリー</li>
            </ol>
        </nav>


        <h2 class="mb- py-3 border-bottom">買取表カテゴリー</h2>


        <a href="{{route('admin.purchase')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <div class="d-flex gap-3">

            <a href="{{ route('admin.purchase.category.create') }}"
            class="btn btn-primary text-white shadow">
            <i class="bi bi-plus-lg"></i>
            {{'新規登録'}}
            </a>

            {{-- <a href="{{ route('admin.purchase.category.change_order') }}"
            class="btn btn-light border">
            <i class="bi bi-arrow-clockwise"></i>
            {{'並び替え'}}
            </a> --}}

        </div>


        <section class="card card-body bg-white my-3 overflow-auto">
            <table class="table mb-0">
                <tbody>
                    <tr class="bg-light">
                        <th scope="col">公開</th>
                        <th scope="col">カテゴリー名</th>
                        <th scope="col">公開中商品</th>
                        <th scope="col"></th>
                    </tr>

                    <!--カスタマイズ-->
                    @foreach ($purchase_categories as $category)
                    <tr>
                        <th scope="row">
                            @if( $category->is_published )
                                <!--公開-->
                                <span class="badge rounded-pill bg-success">{{ '公開中' }}</span>
                            @else
                                <!--非公開-->
                                <span class="badge rounded-pill bg-danger">{{ '非公開' }}</span>
                            @endif
                        </th>

                        <!-- カテゴリー名 -->
                        {{-- <td><a href=""
                        >{{ $category->name }}</a></td> --}}
                        <td>{{ $category->name }}</td>


                        <!-- 公開中商品 -->
                        <td>{{ $category->published_item_count }}</td>

                        <td style="width:8rem;"><div class="row justify-content-start g-2">
                            <div class="col-auto">
                                <!--編集ボタン-->
                                <a href="{{ route('admin.purchase.category.edit',$category) }}"
                                class="btn btn-sm btn-light border "
                                ><i class="bi bi-pencil-fill"></i></a>
                            </div>
                            <div class="col-auto">
                                <!--削除モーダル-->
                                <form action="{{ route('admin.purchase.category.destroy', $category) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <delete-modal-component
                                    index_key="{{'delete'.$category->id}}"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-sm btn-light border ">
                                        <div>
                                            <span class="fw-bold">『{{$category->name}}』</span>を削除します。<br>
                                            関連する買取商品も削除されます。<br>
                                            よろしいですか？
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
