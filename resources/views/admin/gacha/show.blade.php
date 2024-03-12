@extends('admin.layouts.app')


@section('title',$gacha->name)


@section('meta') @php
$active_key = 'gacha';
@endphp @endsection


@section('style')
<style>
    /* ガチャの背景画像 */
    .bg_gacha{
        background: no-repeat center center / cover fixed;
        background-image: url({{ $gacha->category->bg_image_path }});
    }
</style>
@endsection



@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                >{{ 'ガチャ管理' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha',$gacha->category->code_name) }}"
                >{{ $gacha->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $gacha->name }}</li>
            </ol>
        </nav>



        <h2 class="mb- py-3 border-bottom">『{{ $gacha->name }}』詳細情報</h2>


        <a href="{{route('admin.gacha',$gacha->category->code_name)}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>



        <!--タブメニュ-->
        @php $tab='admin.gacha.show'; @endphp
        @include('admin.gacha.common.tab')



        <div class="row mx-0 g-3">
            <!--flex-c2-->
            <div class="col bg-white bg_gacha rounded-3">

                <!--プレビュー-->
                <div class="col-10 mx-auto pt-4">
                    @include('gacha.show.main')
                </div>

            </div>
            <!--flex-c1-->
            <aside class="d-none d-lg-block col-4 ">
                <div class="position-sticky" style="top: 2rem; ">

                    @include('admin.gacha.common.data')


                </div>
            </aside>
        </div>
    </div>



    <!--Modal-->
    <div class="modal fade" id="gachaModal" tabindex="-1" aria-labelledby="gachaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
                    <form action="{{ route('admin.gacha.play', $params) }}" method="post">
                        @csrf

                        <h5 class="modal-title text-center" id="gachaModalLabel">
                            <p>回数を指定して、<br>「ガチャる」ボタンを押してください。</p>
                        </h5>
                        <select name="play_count"
                        class="form-select mb-3"  aria-label="Default select example">

                            @for ($num = 1; $num <= $gacha->remaining_count; $num++)
                                <option value="{{ $num }}">{{ $num.'回ガチャる' }}</option>
                            @endfor

                        </select>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button"
                                class="btn btn-light border rounded-pill w-100"
                                data-bs-dismiss="modal"
                                >キャンセル</button>
                            </div>
                            <div class="col-6">
                                <button type="submit"
                                class="btn btn-info text-white rounded-pill w-100"
                                >ガチャる</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
