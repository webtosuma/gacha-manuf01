@foreach ($gacha->discriptions as $num => $discription)
    <div class="mx-3 py-2 border-bottom">

        <button class="btn w-100 text-start" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapse{{$discription->id}}" aria-expanded="false" aria-controls="collapse{{$discription->id}}">
            <div class="row">
                <div class="col dropdown-toggle">{{ $discription->rank_label }}</div>


                <div class="col-auto">
                    @if ( $discription->total_count_format )
                        <span class="badge rounded-pill bg-success">商品登録あり</span>
                    @else
                        <span class="badge rounded-pill bg-secondary">商品登録なし</span>
                    @endif

                </div>
            </div>
        </button>


        <div class="collapse mb-3
        @if( $discription->image || $discription->sorce) show @endif"
        id="collapse{{$discription->id}}">
            <div class="row px-3">

                <div class="col-4">
                    <label class="d-block">
                        <div class="form-text">商品画像</div>

                        <read-image-file-component
                        img_path="{{ $discription->image_path }}"
                        noimg_path="{{asset('storage/site/image/no_image.jpg')}}"
                        style_class="ratio ratio-3x4 rounded-3"
                        name="{{'gri'.$discription->gacha_rank_id.'-image'}}"
                        ></read-image-file-component>
                    </label>
                </div>
                <div class="col">
                    <label class="d-block h-100">
                        <div class="form-text">商品説明</div>

                        {{-- <textarea name="sorces[]"
                        class="form-control bg-white h-75"
                        placeholder="＊商品の補足説明などがあれば、入力してください。"
                        >{{ $discription->sorce_text }}</textarea> --}}

                        <encodedーtextarea-component
                        name="sorces[]" id="sorces"
                        style_class="form-control" rows="10"
                        placeholder="＊商品の補足説明などがあれば、入力してください。"
                        default_body="{{ $discription->sorce_text }}"
                        ></encodedーtextarea-component>

                    </label>
                </div>

            </div>
        </div>
    </div>
@endforeach

