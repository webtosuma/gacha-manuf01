
{{-- <div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div> --}}
<div class="row gx-5 justify-content-center py-3">

    <div class="col-12 col-md-8">

        <div class="mb-5">
            @if ( $is_under_mainte )
                <span class="badge rounded-pill bg-danger fs-5">メンテナンス実行中</span>
            @else
                <span class="badge rounded-pill bg-secondary fs-5">メンテナンス停止中</span>
            @endif
        </div>


        <!--開始時間(start_at)-->
        <label class="d-block mb-4">
            <div class="form-label">
                開始時間
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <input name="start_at"
            type="datetime-local" class="form-control"
            value="{{ $start_at }}"
            min="">


            <!--error message-->
            @if ( $errors->has('start_at') )
                <div class="text-danger"> {{$errors->first('start_at')}} </div>
            @endif
        </label>



        <!--終了時間(end_at)-->
        <label class="d-block mb-4">
            <div class="form-label">
                終了時間
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <input name="end_at"
            type="datetime-local" class="form-control"
            value="{{ $end_at }}"
            min="{{ now()->format('Y-m-d\T00:00') }}">


            <!--error message-->
            @if ( $errors->has('end_at') )
                <div class="text-danger"> {{$errors->first('end_at')}} </div>
            @endif
        </label>


        <!--メンテナンス時間の表示(show_date)-->
        <label class="d-block mb-4">
            <div class="form-label">
                メンテナンス時間の表示
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <div class="form-check form-switch">
                <input name="show_date"
                @if( $show_date ) checked @endif
                class="form-check-input" type="checkbox" id="show_date">
                <label class="form-check-label" for="show_date">メンテナンス時間を表示する</label>
            </div>
            {{-- <input name="end_at"
            type="datetime-local" class="form-control"
            value="{{ $end_at }}"
            min="{{ now()->format('Y-m-d\T00:00') }}"> --}}


            <!--error message-->
            @if ( $errors->has('end_at') )
                <div class="text-danger"> {{$errors->first('end_at')}} </div>
            @endif
        </label>


        <!--メッセージ(message)-->
        <label class="d-block mb-4">
            <div class="form-label">
                メッセージ
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <textarea name="message"
            class="form-control" style="height:10rem;"
            placeholder="メッセージを入力してください。"
            >{{ $message }}</textarea>

            <!--error message-->
            @if ( $errors->has('message') )
                <div class="text-danger"> {{$errors->first('message')}} </div>
            @endif
        </label>


        <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
    </div>

</div>
