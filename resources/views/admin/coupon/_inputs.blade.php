<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row justify-content-center px-3 g-5">
    <div class="col-lg-6">

        <!--タイトル(title)-->
        <label class="d-block mb-5">
            <div class="form-label">
                タイトル
                <span class="text-danger">＊</span>
            </div>

            <div class="px-4">
                <encodedーinputtext-component
                id="title" name="title"
                style_class="form-control form-control-lg"
                placeholder="クーポンのタイトルを入力"
                default_body="{{ $errors->all() ? urldecode( old('title') ) : $coupon->title }}"
                ></encodedーinputtext-component>

                <!--error message-->
                @if ( $errors->has('title') )
                    <div class="text-danger"> {{$errors->first('title')}} </div>
                @endif
            </div>
        </label>



        <!--クーポンコード(is_use_code)-->
        <label class="d-block mb-4">
            <div class="form-label">
                クーポンコード
                <span class="text-danger">＊</span>
            </div>

            <div class="row px-4">
                <div class="col-6">
                    <!-- 配布クーポン -->
                    <label class="card px-3 py-4 mb-3">
                        <div class="form-check">
                            <input name="is_use_code" value="0"
                            type="radio" class="form-check-input"
                            {{ old('is_use_code',$coupon->is_use_code)==0 ? 'checked' : ''}}
                            >
                            <h6 class="mb-0 mt-1">なし</h6>
                        </div>
                    </label>
                </div>
                <div class="col-6">
                    <!-- クーポンコード入力 -->
                    <label class="card px-3 py-4 mb-3">
                        <div class="form-check">
                            <input name="is_use_code" value="1"
                            type="radio" class="form-check-input"
                            {{ old('is_use_code',$coupon->is_use_code)==1 ? 'checked' : ''}}
                            >
                            <h6 class="mb-0 mt-1">あり(クーポンコード入力)</h6>
                        </div>
                    </label>
                </div>
            </div>

            <!--error message-->
            @if ( $errors->has('is_use_code') )
                <div class="text-danger"> {{$errors->first('is_use_code')}} </div>
            @endif
        </label>



        <!--プレゼント内容(service)-->
        <label class="d-block mb-5">
            <div class="form-label">
                プレゼント内容
                <span class="text-danger">＊</span>
            </div>

            <a-coupon-edit-servise
            service   ="{{old('service',$coupon->service)}}"
            point     ="{{old('point'  ,$coupon->point)}}"
            prize_code="{{old('prize_code',$coupon->prize_code)}}"
            ></a-coupon-edit-servise>


            <!--error message-->
            @if ( $errors->has('service') )
                <div class="text-danger"> {{$errors->first('service')}} </div>
            @endif
            <!--error message-->
            @if ( $errors->has('point') )
                <div class="text-danger"> {{$errors->first('point')}} </div>
            @endif
            <!--error message-->
            @if ( $errors->has('prize_code') )
                <div class="text-danger"> {{$errors->first('prize_code')}} </div>
            @endif

        </label>



        <!--利用回数制限(is_count count user_type)-->
        <div class="d-block mb-5">
            <div class="form-label">
                利用回数制限
                <span class="text-danger">＊</span>
            </div>

            <div class="px-4">
                <!-- 設定する -->
                <label class="card p-2 mb-3">
                    <div class="form-check ">
                        <input name="is_count" value="1" type="radio" class="form-check-input"
                        {{ old('is_count',$coupon->is_count)==1 ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">設定する</h6>
                    </div>


                    <div class="input-group mb-3 px-3">
                        <!--利用者の種類-->
                        <select class="form-select" name="user_type">

                            @foreach ($coupon->user_types as $value => $label)
                                <option
                                @if( old('user_type',$coupon->user_type)==$value ) selected @endif
                                value="{{$value}}"
                                >{{$label}}</option>
                            @endforeach
                        </select>


                        <!--利用回数-->
                        <input value="{{old('count', $coupon->count )}}"
                        name="count"
                        type="number"
                        min="0"
                        class="form-control text-end">
                        <span class="input-group-text" >回まで</span>
                    </div>
                </label>


                <!-- 設定しない -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_count" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ old('is_count',$coupon->is_count)==0 ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">設定しない</h6>
                    </div>
                </label>


                <!--error message-->
                @if ( $errors->has('is_count') )
                    <div class="text-danger"> {{$errors->first('is_count')}} </div>
                @endif
                <!--error message-->
                @if ( $errors->has('count') )
                    <div class="text-danger"> {{$errors->first('count')}} </div>
                @endif
                <!--error message-->
                @if ( $errors->has('user_type') )
                    <div class="text-danger"> {{$errors->first('user_type')}} </div>
                @endif
            </div>
        </div>


        <!--有効期限(is_expiration)-->
        <div class="d-block mb-5">
            <div class="form-label">
                有効期限
                <span class="text-danger">＊</span>
            </div>

            <div class="px-4">
                <!-- 設定する -->
                <label class="card p-2 mb-3">
                    <div class="form-check ">
                        <input name="is_expiration" value="1" type="radio" class="form-check-input"
                        {{ old('is_expiration',$coupon->is_expiration)==1 ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">設定する</h6>
                    </div>


                    <ul class="form-text m-0 pe-3">
                        <li>登録クーポンの有効期限を設定します。</li>
                        <li>「有効期限」を過ぎると、指定日移行クーポンの利用ができなくなります。</li>
                    </ul>


                    <div class="input-group mb-3 px-3">
                        <span class="input-group-text" >有効期限</span>
                        @php $expiration_at_value = $coupon->expiration_at ? $coupon->expiration_at->format('Y-m-d') : now()->addDay(14)->format('Y-m-d'); @endphp
                        <input name="expiration_at"
                        type="date" class="form-control"
                        value="{{ old('expiration_at', $expiration_at_value ) }}"
                        >
                    </div>
                </label>


                <!-- 設定しない -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_expiration" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ old('is_expiration',$coupon->is_expiration)==0 ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">設定しない</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>有効期限を設定しません。</li>
                        <li>無期限でクーポンの利用が可能です。</li>
                    </ul>
                </label>
            </div>

            <!--error message-->
            @if ( $errors->has('is_expiration') )
                <div class="text-danger"> {{$errors->first('is_expiration')}} </div>
            @endif
            <!--error message-->
            @if ( $errors->has('expiration_at') )
                <div class="text-danger"> {{$errors->first('expiration_at')}} </div>
            @endif
        </div>


    </div>
    <div class="col-lg">


        <!--公開設定(is_published)-->
        <div class="d-block mb-5">
            <div class="form-label">
                公開設定
                <span class="text-danger">＊</span>
            </div>

            <div class="px-4">
                <!-- 公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="1" type="radio" class="form-check-input"
                        {{ old('is_published', $coupon->published_state)==1 ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開</h6>

                    </div>
                    <ul class="form-text m-0">
                        <li>登録クーポンを公開します。</li>
                    </ul>
                    @if( $coupon->is_published ) <div class="form-text">
                        {{\Carbon\Carbon::parse($coupon->published_at)->format('公開日：Y年m月d日 H:i')}}
                    </div> @endif
                </label>


                <!-- 公開予約 -->
                <label class="card p-2 mb-3">
                    <div class="form-check ">
                        <input name="is_published" value="2" type="radio" class="form-check-input"
                        {{ old('is_published', $coupon->published_state)==2 ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開予約</h6>
                    </div>
                    <ul class="form-text m-0 pe-3">
                        <li>登録クーポンを公開予約します。</li>
                        <li>「公開予約日時」を設定すると、指定日時に登録クーポンを自動公開することができます。</li>
                    </ul>

                    <div class="input-group mb-3 px-3">
                        @php $published_at_value = $coupon->published_at ? $coupon->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\T00:00'); @endphp
                        <span class="input-group-text" >公開予約日時</span>
                        <input name="published_at"
                        type="datetime-local" class="form-control"
                        value="{{ old('published_at',$published_at_value ) }}"
                        min="{{ now()->format('Y-m-d\T00:00') }}">

                    </div>
                </label>


                <!-- 非公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ old('is_published', $coupon->published_state)==0 ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">非公開</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>登録クーポンを非公開します。</li>
                        <li>非公開時は登録クーポン一覧に登録クーポンが表示されなくなります。</li>
                    </ul>
                </label>
            </div>


            <!--error message-->
            @if ( $errors->has('is_published') )
                <div class="text-danger"> {{$errors->first('is_published')}} </div>
            @endif
            <!--error message-->
            @if ( $errors->has('published_at') )
                <div class="text-danger"> {{$errors->first('published_at')}} </div>
            @endif

        </div>





        <div class="col-md-6 mx-auto my-5">
            @if (!$coupon->id)
            <disabled-button style_class="btn btn-lg btn-primary text-white w-100 shadow" btn_text="登録する"></disabled-button>
            @else
            <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow" btn_text="更新する"></disabled-button>
            @endif
        </div>

    </div>
    {{-- <div class="col-12">
        <div class="col-md-6 mx-auto my-5">
            @if (!$coupon->id)
            <disabled-button style_class="btn btn-lg btn-primary text-white w-100 shadow" btn_text="登録する"></disabled-button>
            @else
            <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow" btn_text="更新する"></disabled-button>
            @endif
        </div>
    </div> --}}
</div>
