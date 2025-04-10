<div class="form-text mb-5">
    <span class="text-danger">＊</span>入力必須
</div>


<div class="row">
    <div class="col-md-6">
        <!--演出動画名(name)-->
        <label class="d-block mb-5">
            <div class="form-label">
                演出動画名
                <span class="text-danger">＊</span>
            </div>

            <input value="{{old('name', $movie->name )}}"
            name="name"
            type="text" class="form-control">

            <!--error message-->
            @if ( $errors->has('name') )
                <div class="text-danger"> {{$errors->first('name')}} </div>
            @endif
        </label>



        <!--Youtube動画URL(youtube_url)-->
        <label class="d-block mb-5">
            <div class="form-label">
                Youtube動画URL
                {{-- <span class="text-danger">＊</span> --}}
            </div>
            <div class="form-text">
                ＊Youtube動画を指定した場合、登録中の動画は削除されます。
            </div>

            <input value="{{old('youtube_url', $movie->youtube_url )}}"
            name="youtube_url"
            placeholder="https://www.youtube.com/shorts?v=XXXXXX"
            type="text" class="form-control">

            <!--error message-->
            @if ( $errors->has('youtube_url') )
                <div class="text-danger"> {{$errors->first('youtube_url')}} </div>
            @endif
        </label>


        <!--モバイル用動画(mobile_storage)-->
        <label class="d-block mb-5">
            <div class="form-label">
                モバイル用動画
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <div class="col-md-6">
                <read-movie-file-component
                name="mobile_storage"
                video_path="{{ $movie->mobile }}"
                ></read-movie-file-component>
            </div>
            <!--error message-->
            @if ( $errors->has('mobile_storage') )
                <div class="text-danger"> {{$errors->first('mobile_storage')}} </div>
            @endif
        </label>
    </div>
    <div class="col">
        <div class="alert alert-warning border-0 shadow-sm">
            「Youtube動画URL」を選択している場合、モバイル動画を挿入することはできません。
            「モバイル動画」を挿入する場合は、「Youtube動画URL」の入力を消し忘れないようお気をつけください。
        </div>
        <div class="col-md-6 mx-auto">
            @if (!$movie->id)
                <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
                <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>
    </div>

</div>
