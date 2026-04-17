<div class="">



    <div class="p-3 bg-body rounded-4">


        @if (!$gacha_title->id)
            <a href="{{route('admin.gacha_title')}}"
            class="btn btn-light border">< 戻る</a>
        @else
            <a href="{{route('admin.gacha_title.show',$gacha_title)}}"
            class="btn btn-light border">< 基本情報へ 戻る</a>
        @endif

        <div class="my-3">
            <!--公開ステータス-->
            @include('manuf_admin.gacha_title.common.published_statuse')
        </div>

    </div>


    @if ($errors->any())
        <!--エラーメッセージ-->
        <div class="alert alert-danger border-0 rounded-4 my-3">
            <ul class="mb-0 liststyle-none">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="my-3">
        @if (!$gacha_title->id)
            <disabled-button style_class="btn btn-success text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
        @else
            <disabled-button style_class="btn btn-warning w-100 shadow" btn_text="更新する"></bdisabled-button>
        @endif
    </div>



</div>
