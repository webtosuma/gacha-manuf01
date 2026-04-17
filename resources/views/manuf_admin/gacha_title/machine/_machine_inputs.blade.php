<div> 


    <div class="form-text mb-4">
        <span class="text-danger">＊</span>入力必須
    </div>
    
    <!--筐体名(name・default_name)-->
    <label class="d-block mb-4">
        <div class="form-label fw-bold">
            筐体名
            <span class="text-danger">＊</span>
        </div>

        <encodedーinputtext-component
        id="name" name="name"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('name') ) : $machine->name }}"
        ></encodedーinputtext-component>


        <!--error message(default_name)-->
        @if ( $errors->has('default_name') )
            <div class="text-danger"> {{$errors->first('default_name')}} </div>
        @endif
    </label>



    <!--ガチャの種類(type)-->
    <label class="d-block mb-4">
        <div class="form-label fw-bold">
            ガチャの種類
            <span class="text-danger">＊</span>
        </div>

        <select class="form-select" name="type">

            @foreach ($machine->types as $value => $lable)
                <option value="{{$value}}"
                @if(  old('type',$machine->type) == $value ) selected @endif
                >{{ $lable }}</option>
            @endforeach

        </select>

        <!--error message-->
        @if ( $errors->has('type') )
            <div class="text-danger"> {{$errors->first('type')}} </div>
        @endif

    </label>



    <!--表示時間帯の指定(min_time max_time)-->
    <div class="d-block mb-4">
        <div class="form-label fw-bold">
            表示時間帯の指定（24時間表記）
            <span class="text-danger">＊</span>
        </div>


        <div class="px-2">
            <div class="input-group mb-3">
                <select class="form-select text-center" name="{{'min_time'}}">
                    @foreach ($machine->times as $time)
                        <option value="{{$time}}"
                        @if( old('min_time',$machine->min_time) == $time  ) selected @endif
                        >{{ $time }}</option>

                    @endforeach
                </select>

                <span class="input-group-text">〜</span>

                <select class="form-select text-center" name="{{'max_time'}}">
                    @foreach ($machine->times as $time)
                        <option value="{{$time}}"
                        @if( old('max_time',$machine->max_time) == $time  ) selected @endif
                        >{{ $time }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <!--error message-->
        @if ( $errors->has('min_time') )
            <div class="text-danger"> {{$errors->first('min_time')}} </div>
        @endif
        @if ( $errors->has('max_time') )
            <div class="text-danger"> {{$errors->first('max_time')}} </div>
        @endif

    </div>


    <!--公開設定(is_published)-->
    <div class="bg-white mt-3 mb-4 rounded-4">
        @php
            $isPublished = old('is_published', $machine->published_at ? 1 : 0);
        @endphp

        <div class="d-block">
            <div class="form-label fw-bold">
                公開設定
                <span class="text-danger">＊</span>
            </div>


            <div class="px-2">

                <!-- 公開 -->
                @php $disablde = $machine?->max_count<1; @endphp
                <label class="card bg-white p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="1" type="radio" class="form-check-input"
                        {{ $disablde ? 'disabled' : '' }}
                        {{ $isPublished == 1 ? 'checked' : '' }}>
                        <h6 class="mb-0 mt-1">公開</h6>
                    </div>

                    @if($machine->published_at)
                        <div class="form-text">
                            {{ \Carbon\Carbon::parse($machine->published_at)->format('公開日：Y年m月d日 H:i') }}
                        </div>
                    @endif

                    @if ($disablde)
                        <div class="form-text text-danger">＊商品が登録されていません。</div>
                    @endif
                </label>

                <!-- 非公開 -->
                <label class="card bg-white p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0" type="radio" class="form-check-input"
                            {{ $isPublished == 0 ? 'checked' : '' }}>
                        <h6 class="mb-0 mt-1">非公開</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>非公開中は筐体一覧に表示されません。</li>
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

</div>


    
