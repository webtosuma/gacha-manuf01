@extends('admin.layouts.app')


@section('title','スポンサー一覧')


@section('meta') @php
$active_key = 'sponsor_ad';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.sponsor_ad') }}"
                >{{ '広告管理' }}</a></li>

                <li class="breadcrumb-item active" aria-current="page">スポンサー一覧</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">スポンサー一覧</h2>


        <div class="d-flex gap-3 mb-3">
            <a href="{{ route('admin.sponsor.create') }}"
            class="btn btn-dark text-white shadow">
            <i class="bi bi-plus-lg"></i>
            {{'スポンサー新規登録'}}
            </a>


            <a href="{{ route('admin.sponsor_ad') }}"
            class="btn btn-light border">{{'広告一覧'}}</a>
        </div>


        <section class="card card-body bg-white mb-3 overflow-auto my-3">
            <table class="table bg-white my-3 text-center">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col" class="text-start">スポンサー名</th>
                        <th scope="col">広告数</th>
                        <th scope="col">総動画再生数</th>
                        <th scope="col">総サイトアクセス数</th>
                        <th><!--menu--></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sponsors as $sponsor)
                        <tr>
                            {{-- <th class="text-start"><a href="{{route('admin.sponsor.show', $sponsor)}}"
                            >{{$sponsor->name}}</a></th> --}}
                            <th class="text-start">{{$sponsor->name}}</th>

                            <td>{{number_format(0)}}</td>

                            <td>{{number_format(0)}}</td>

                            <td>{{number_format(0)}}</td>


                            <td style="width:8rem;"><div class="row justify-content-end g-2">
                                <div class="col-auto">
                                    <!--編集ボタン-->
                                    <a href="{{ route('admin.sponsor.edit',$sponsor) }}"
                                    class="btn btn-sm btn-light border "
                                    ><i class="bi bi-pencil-fill"></i></a>
                                </div>
                                <div class="col-auto">
                                    <!--削除モーダル-->
                                    <form action="{{ route('admin.sponsor.destroy', $sponsor) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <delete-modal-component
                                        index_key="{{'delete'.$sponsor->id}}"
                                        icon="bi-trash"
                                        func_btn_type="submit"
                                        button_class="btn btn-sm btn-light border ">
                                            <div>
                                                <span class="fw-bold">『{{$sponsor->name}}』</span>を削除します。
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
