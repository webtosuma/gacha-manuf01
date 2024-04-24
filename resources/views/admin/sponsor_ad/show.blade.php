@extends('admin.layouts.app')


@section('title',$sponsor_ad->title)


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
                <li class="breadcrumb-item active" aria-current="page">{{$sponsor_ad->title}}</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">{{$sponsor_ad->title}}</h2>


        <div class="d-flex gap-3 my-3">
            <a href="{{route('admin.sponsor_ad')}}"
            class="btn border rounded-pill"
            ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>
        </div>


        <section>
            <div class="row">
                <div class="col-md">


                    <!--広告タイトル(title)-->
                    <label class="d-block mb-4">
                        <div class="form-label">広告タイトル</div>

                        <div class="card p-2">{{$sponsor_ad->title}}</div>
                    </label>


                    <!--スポンサー(sponsor_id)-->
                    <label class="d-block mb-4">
                        <div class="form-label">スポンサー名</div>

                        <div class="card p-2">{{$sponsor_ad->sponsor->name}}</div>
                    </label>


                    <!--ガチャ(gacha_id)-->
                    <label class="d-block mb-4">
                        <div class="form-label">ガチャ</div>

                        <div class="card p-2">{{$sponsor_ad->gacha->name}}</div>
                    </label>


                    <!--サイトURL(url)-->
                    <label class="d-block mb-4">
                        <div class="form-label">サイトURL</div>

                        @php $url = $sponsor_ad->url ? $sponsor_ad->url : '未登録'; @endphp
                        <div class="card p-2">{!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1" target="_blank">$1</a>', $url) )!!}
                        </div>
                    </label>



                </div>
                <div class="col-md">


                    <!--広告動画(movie)-->
                    <label class="d-block mb-4">
                        <div class="form-label">広告動画</div>

                        @if ($sponsor_ad->movie)
                            <movie-modal-component
                            id   ="{{$sponsor_ad->id.'-movie'}}"
                            title="{{ $sponsor_ad->name.'（モバイル）' }}"
                            src  ="{{ $sponsor_ad->movie_path }}"
                            btn_label="広告動画再生"
                            max_width="400px"
                            ></movie-modal-component>
                        @else
                            <span>未登録</span>
                        @endif
                    </label>


                    <div class="d-flex gap-3 my-3">
                        <a href="{{route('admin.sponsor_ad.edit',$sponsor_ad)}}"
                        class="btn btn-warning text-white"
                        >編集する</a>

                        <a href="{{route('admin.sponsor_ad.destroy',$sponsor_ad)}}"
                        class="btn btn-danger text-white"
                        >削除する</a>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
