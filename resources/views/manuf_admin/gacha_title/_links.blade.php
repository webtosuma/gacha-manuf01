<div class="">



    <div class="p-3 bg-body rounded-4">


        @if (!$gacha_title->id)
            <a href="{{route('admin.gacha_title')}}"
            class="btn btn-light border">< 戻る</a>
        @else
            <a href="{{route('admin.gacha_title.show',$gacha_title)}}"
            class="btn btn-light border">< 戻る</a>
        @endif


        <div class="d-flex flex-column align-items-start my-3">
            <a href="#name"
            class="btn btn-sm fw-bold text-primary ">タイトル名</a>

            <a href="#category_id"
            class="btn btn-sm fw-bold text-primary ">カテゴリー</a>

            <a href="#price"
            class="btn btn-sm fw-bold text-primary ">価格(税込み)</a>

            <a href="#image_samune"
            class="btn btn-sm fw-bold text-primary ">トップ画像</a>

            <a href="#description"
            class="btn btn-sm fw-bold text-primary ">説明文</a>

            <a href="#set_contents"
            class="btn btn-sm fw-bold text-primary ">セット内容</a>

            <a href="#prize_size"
            class="btn btn-sm fw-bold text-primary ">商品サイズ</a>

            <a href="#prize_materials"
            class="btn btn-sm fw-bold text-primary ">商品素材</a>

            <a href="#age_range"
            class="btn btn-sm fw-bold text-primary ">対象年齢</a>

            <a href="#copy_right"
            class="btn btn-sm fw-bold text-primary ">コピーライト</a>

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
