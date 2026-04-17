<div class="">



    <div class="p-3 bg-body rounded-4">


        <a href="{{route('admin.gacha_title.show',$gacha_title)}}"
        class="btn btn-light border">< 基本情報へ 戻る</a>


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
        <disabled-button style_class="btn btn-warning w-100 shadow" btn_text="更新する"></bdisabled-button>
    </div>



</div>
