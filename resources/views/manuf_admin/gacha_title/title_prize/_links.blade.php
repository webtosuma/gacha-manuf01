<div class="">



    <div class="p-3 bg-body rounded-4">


        <a href="{{route('admin.gacha_title.title_prize',$gacha_title),}}"
        class="btn btn-light border">< 戻る</a>


    </div>


    <!--公開設定(is_published)-->
    <div class="bg-white mt-3 mb-5 rounded-4">
        @php
            $isPublished = old('is_published', $title_prize->published_at ? 1 : 0);
        @endphp

        <div class="d-block">
            <div class="form-label fw-bold">
                公開設定
                <span class="text-danger">＊</span>
            </div>

            <div class="px-2">

                <!-- 公開 -->
                <label class="card bg-white p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="1" type="radio" class="form-check-input"
                            {{ $isPublished == 1 ? 'checked' : '' }}>
                        <h6 class="mb-0 mt-1">公開</h6>
                    </div>

                    @if($title_prize->published_at)
                        <div class="form-text">
                            {{ \Carbon\Carbon::parse($title_prize->published_at)->format('公開日：Y年m月d日 H:i') }}
                        </div>
                    @endif
                </label>

                <!-- 下書き -->
                <label class="card bg-white p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0" type="radio" class="form-check-input"
                            {{ $isPublished == 0 ? 'checked' : '' }}>
                        <h6 class="mb-0 mt-1">下書き</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>下書き中はタイトル商品一覧に表示されません。</li>
                    </ul>
                </label>

            </div>
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
        @if (!$title_prize->id)
            <disabled-button style_class="btn btn-success text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
        @else
            <disabled-button style_class="btn btn-warning w-100 shadow" btn_text="更新する"></bdisabled-button>
        @endif
    </div>



</div>
