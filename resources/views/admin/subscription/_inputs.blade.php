<div class="form-text mb-3">
    <span class="text-danger">＊</span>入力必須
</div>
<div class="row">
    <div class="col-md">

        <!--見出し(sub_label)-->
        <label class="d-block mb-4">
            <div class="form-label">
                サブスク名
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <encodedーinputtext-component
            id="sub_label" name="sub_label"
            style_class="form-control"
            placeholder="月額XXXX円プラン"
            default_body="{{ $errors->all() ? urldecode( old('sub_label') ) : $subscription->sub_label }}"
            ></encodedーinputtext-component>

            <!--error message-->
            @if ( $errors->has('sub_label') )
                <div class="text-danger"> {{$errors->first('sub_label')}} </div>
            @endif
        </label>


        <!--サムネ画像(sub_image)-->
        <label class="d-block col-md-12 mx-auto mb-4">
            <div class="form-label">サムネ画像</div>

            <div class="px-3">
                <read-image-file-component
                img_path="{{ $subscription->sub_image_path }}"
                noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
                style_class="ratio {{config('app.gacha_card_ratio')}} rounded-3"
                name="sub_image"
                ></read-image-file-component>
            </div>

            <!--error message-->
            @if ( $errors->has('sub_image') )
                <div class="text-danger"> {{$errors->first('sub_image')}} </div>
            @endif
        </label>


        <!--サブスクの説明(sub_description)-->
        <label class="d-block mb-4">
            <div class="form-label">
                サブスクの説明
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <encodedーtextarea-component
            name="sub_description" id="sub_description"
            style_class="form-control" rows="6"
            placeholder="サブスクの説明を入力してください。"
            default_body="{{ $errors->all() ? urldecode( old('sub_description') ) : $subscription->sub_description_text }}"
            ></encodedーtextarea-component>


            <!--error message-->
            @if ( $errors->has('sub_description') )
                <div class="text-danger"> {{$errors->first('sub_description')}} </div>
            @endif
        </label>


    </div>
    <div class="col-md-6">

        <!--請求金額(price)-->
        <label class="d-block mb-4">
            <div class="form-label">
                請求金額
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input type="hidden" value="{{ $subscription->price }}" name="price">

                <input  value="{{old('price', $subscription->price )}}"
                name="price"
                @if($subscription->id) disabled @endif
                type="number" min="0" class="form-control text-end">
                <span class="input-group-text">円</span>
            </div>

            <!--error message-->
            @if ( $errors->has('price') )
                <div class="text-danger"> {{$errors->first('price')}} </div>
            @endif
        </label>


        <!--請求期間(sub_billing_cycle)-->
        @if( false )
            <label class="d-block mb-4">
                <div class="form-label">
                    請求期間
                    <span class="text-danger">＊</span>
                </div>

                <div class="col-4">
                    <select class="form-select"
                    name="sub_billing_cycle"
                    >
                        @foreach ($billing_cycles as $billing_cycle)
                            <option
                            value="{{$billing_cycle}}"
                            @if( $billing_cycle == old('sub_billing_cycle', $subscription->sub_billing_cycle ) ) selected @endif
                            >{{$billing_cycle}}</option>
                        @endforeach
                    </select>
                </div>

                <!--error message-->
                @if ( $errors->has('sub_billing_cycle') )
                    <div class="text-danger"> {{$errors->first('sub_billing_cycle')}} </div>
                @endif
            </label>
        @else

            <input type="hidden" name="sub_billing_cycle" value="{{$subscription->sub_billing_cycle}}">

        @endif


        <!--付与ポイント数(value)-->
        <label class="d-block mb-4">
            <div class="form-label">
                付与ポイント数
                <span class="text-danger">＊</span>
            </div>

            <div class="input-group mb-3">
                <input  value="{{old('value', $subscription->value  )}}"
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
                <input type="hidden" value="{{$subscription->stripe_id }}" name="stripe_id">

                <input  value="{{old('stripe_id', $subscription->stripe_id  )}}"
                name="stripe_id"
                placeholder="price_XXXXXXXXXX"
                @if($subscription->id) disabled @endif
                type="text" class="form-control">

                {{-- <input  value="{{old('stripe_id', $subscription->stripe_id  )}}"
                name="stripe_id"
                placeholder="price_XXXXXXXXXX"
                type="text" class="form-control"> --}}

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
                        {{ $subscription->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開</h6>

                    </div>
                    <ul class="form-text m-0">
                        <li>サブスクプランを公開します。</li>
                    </ul>
                </label>


                <!-- 非公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ !$subscription->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">非公開</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>サブスクプランを非公開にします。</li>
                        <li>非公開時はサブスクプラン一覧に表示されなくなります。</li>
                        <li>非公開に変更した際、関連するサブスクガチャも非公開となります。</li>
                    </ul>
                </label>
            </div>

            <!--error message-->
            @if ( $errors->has('is_published') )
                <div class="text-danger"> {{$errors->first('is_published')}} </div>
            @endif

        </div>


        <div class="col-md-6 my-5 mx-auto">
            @if (!$subscription->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>

    </div>
</div>
