@extends('admin.layouts.app')


@section('title',$text_type['label'])


@section('meta') @php
/* 複数の文書保存　一覧 */
$active_key = 'text';
$active_submenu = true;
@endphp @endsection




@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.text') }}"
                >{{ '文書設定' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$text_type['label']}}</li>
            </ol>
        </nav>



        <h2 class="mt-5 py-3 border-bottom">{{$text_type['label']}}</h2>


        <div class="d-flex gap-3 my-3">

            <a href="{{route('admin.text')}}"
            class="btn border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>戻る</a>


            <a href="{{ route('admin.text.multiple.create', $text_type['type'] ) }}"
            class="btn btn-primary text-white shadow">
            <i class="bi bi-plus-lg"></i>
            {{'新規登録'}}
            </a>

        </div>

        <section class="card card-body bg-white my-3 overflow-auto">
            <table class="table mb-0">
                <tbody>
                    <tr class="bg-light">
                        <th scope="col">制定日・改訂日</th>
                        <th scope="col"></th>
                    </tr>

                    @foreach ($texts as $text)
                    <tr>
                        <!-- 制定日・改訂日 -->
                        <td><a href="">{{ $text->enactmented_at_format }}</a></td>

                        <td style="width:8rem;"><div class="row justify-content-end g-2">
                            @php
                            $params = ['type'=>$text->type, 'text'=>$text->id];
                            @endphp
                            <div class="col-auto">
                                <!--編集ボタン-->
                                <a href="{{route('admin.text.multiple.edit',$params)}}"
                                class="btn btn-sm btn-light border "
                                ><i class="bi bi-pencil-fill"></i></a>
                            </div>
                            <div class="col-auto">
                                <!--削除モーダル-->
                                <form action="{{route('admin.text.multiple.destroy',$params)}}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <delete-modal-component
                                    index_key="{{'delete'.$text->id}}"
                                    icon="bi-trash"
                                    func_btn_type="submit"
                                    button_class="btn btn-sm btn-light border ">
                                        <div>
                                            <span class="fw-bold">『{{$text->enactmented_at_format}}』</span>を削除します。<br>
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
