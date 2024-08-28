@extends('admin.layouts.app')


@section('title','CSVインポート')


@section('meta') @php
$active_key = 'prize';
$active_submenu = true;
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.prize') }}"
                >{{ '商品管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">CSVインポート</li>
            </ol>
        </nav>


        <h2 class="mb-5 py-3 border-bottom">CSVインポート</h2>


        <a href="{{route('admin.prize.import_csv_download')}}"
        onclick="stopOnbeforeunload()"
        class="btn btn-light border py-0 mb-3">
            <i data-v-3e26587a="" class="bi bi-filetype-csv fs-4"></i>
            インポート用ファイルのダウンロード
        </a>


        <form action="{{route('admin.prize.import_csv_post')}}"
        class="card card-body gap-3 col-md-6 mb-3"
        method="POST" novalidate
        enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
            @csrf
            <input name="csv_file" type="file" class="form-control">

            <!--error message-->
            @if ( $errors->has('csv_file') )
                <div class="text-danger"> {{$errors->first('csv_file')}} </div>
            @endif

            <div class="col-auto">
                <button class="btn btn-primary text-white" type="submit">読み込む</button>
            </div>
        </form>

        <div class="form-text">
            <h6>以下の注意点をご確認ください。</h6>
            <div>＊指定した商品コードが未入力の場合、登録されません。</div>
            <div>＊指定した商品コードがすでに登録されている場合、上書き保存されます。</div>
            <div>＊指定した交換ポイントが正の整数以外場合、正の整数に自動変換されます。</div>
            <div>＊登録されていないカテゴリーを指定して商品を登録することはできません。</div>
            <div>＊以下以外のランクを指定して商品を登録することはできません。</div>
            <div class="ms-3 d-flex gap-3 flex-wrap">
                @foreach ($ranks as $rank)
                <div class="">{{ $rank->name }}</div>
                @endforeach
            </div>

        </div>


    </div>
@endsection
