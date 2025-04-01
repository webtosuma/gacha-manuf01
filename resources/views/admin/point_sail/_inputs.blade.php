<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row">
    <div class="col-md">


        <!--請求金額(price)-->
        <label class="d-block mb-4">
            <div class="form-label">
                請求金額
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input type="hidden" value="{{ $point_sail->price }}" name="price">

                <input  value="{{old('price', $point_sail->price )}}"
                name="price"
                @if($point_sail->id) disabled @endif
                type="number" min="0" class="form-control text-end">
                <span class="input-group-text">円</span>
            </div>

            <!--error message-->
            @if ( $errors->has('price') )
                <div class="text-danger"> {{$errors->first('price')}} </div>
            @endif
        </label>

        <!--付与ポイント数(value)-->
        <label class="d-block mb-4">
            <div class="form-label">
                付与ポイント数
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input  value="{{old('value', $point_sail->value  )}}"
                name="value"
                type="number" min="0" class="form-control text-end">
                <span class="input-group-text">pt</span>
            </div>

            <!--error message-->
            @if ( $errors->has('value') )
                <div class="text-danger"> {{$errors->first('value')}} </div>
            @endif
        </label>


        <!--Stripeの商品ID(stripe_id)-->
        <label class="d-block mb-4">
            <div class="form-label">
                Stripeの商品ID
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input type="hidden" value="{{$point_sail->stripe_id }}" name="stripe_id">

                <input  value="{{old('stripe_id', $point_sail->stripe_id  )}}"
                name="stripe_id"
                placeholder="price_XXXXXXXXXX"
                @if($point_sail->id) disabled @endif
                type="text" class="form-control">
            </div>

            <!--error message-->
            @if ( $errors->has('stripe_id') )
                <div class="text-danger"> {{$errors->first('stripe_id')}} </div>
            @endif

            <div class="alert alert-warning border-0" role="alert">
                <h6 class="fw-bold text-warning">Stripeの商品IDをご確認ください。</h6>
                Stripeの商品IDは新規登録時のみ入力可能であり、後に<strong class="text-warning">変更することができません</strong> 。<br>
                Stripeの商品IDの異なる商品を登録する場合は、「新規登録」より再度登録をお願いします。
            </div>

        </label>


    </div>
    <div class="col-md-6">


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
                        {{ $point_sail->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開</h6>

                    </div>
                    <ul class="form-text m-0">
                        <li>販売ポイントを公開します。</li>
                    </ul>
                </label>


                <!-- 非公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ !$point_sail->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">非公開</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>販売ポイントを非公開にします。</li>
                        <li>非公開時は販売ポイント一覧に表示されなくなります。</li>
                    </ul>
                </label>
            </div>

            <!--error message-->
            @if ( $errors->has('is_published') )
                <div class="text-danger"> {{$errors->first('is_published')}} </div>
            @endif

        </div>


        <div class="col-md-6 my-5 mx-auto">
            @if (!$point_sail->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>

    </div>
</div>
