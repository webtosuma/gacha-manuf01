<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>

<div class="col-6">
    <!--演出動画名(name)-->
    <label class="d-block mb-4">
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
</div>


<div class="row">
    <div class="col-md-9">
        <!--PC用動画(pc_storage)-->
        <label class="d-block mb-4">
            <div class="form-label">
                PC用動画
                <span class="text-danger">＊</span>
            </div>

            <read-movie-file-component
            name="pc_storage"
            ></read-movie-file-component>

            <!--error message-->
            @if ( $errors->has('pc_storage') )
                <div class="text-danger"> {{$errors->first('pc_storage')}} </div>
            @endif
        </label>
    </div>
    <div class="col-md-3">
        <!--モバイル用動画(mobile_storage)-->
        <label class="d-block mb-4">
            <div class="form-label">
                モバイル用動画
                <span class="text-danger">＊</span>
            </div>

            <read-movie-file-component
            name="mobile_storage"
            ></read-movie-file-component>

            <!--error message-->
            @if ( $errors->has('mobile_storage') )
                <div class="text-danger"> {{$errors->first('mobile_storage')}} </div>
            @endif
        </label>
    </div>
</div>
<div class="col-md-4 my-5">
    @if (!$movie->id)
    <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
    @else
    <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
    @endif
</div>
