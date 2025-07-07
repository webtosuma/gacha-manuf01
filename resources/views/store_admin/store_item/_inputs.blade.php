<div class="row">
    <div class="col-md-6">


        <div class="px-3">


            <!--商品名(item_name)-->
            <label class="d-block mb-4">
                <div class="form-label">商品名</div>

                <encodedーinputtext-component
                id="item_name" name="item_name"
                style_class="form-control"
                default_body="{{ $errors->all() ? urldecode( old('item_name') ) : $store_item->item_name }}"
                ></encodedーinputtext-component>

                <!--error message-->
                @if ( $errors->has('item_name') )
                    <div class="text-danger"> {{$errors->first('item_name')}} </div>
                @endif
            </label>



            <!--画像・複数(images)-->
            <div class="d-block mb-4">
                <div class="form-label">画像</div>
                <div class="form-text">※ファイルは8Mバイト以内で、jpeg・jpg・pngのいずれかの形式を選択してください。</div>

                <div class="card p-2 bg-white overflow-auto w-100" style="max-width: 600px">
                    @php $img_count = $store_item->images_count;@endphp
                    <div style="width:{{10*$img_count}}rem">
                        <div class="row g-2">
                            @foreach ($store_item->admin_image_paths as $num => $image_path)
                                <div class="col">

                                    @if ($store_item->prize && $num==0 )
                                        <!--ガチャ用商品画像-->
                                        <u-store-item-image
                                        ration        ="{{$store_item->ration}}"
                                        image_path    ="{{$store_item->image_paths[0]}}"
                                        is_prize      ="{{$store_item->prize?1:0}}"
                                        ></u-store-item-image>

                                    @else
                                        <!--EC商品画像-->
                                        <read-image-file-component
                                        img_path="{{isset($image_path) ? $image_path : ''}}"
                                        noimg_path="{{$store_item->noImage()}}"
                                        style_class="ratio {{config('store.item_ratio')}} rounded-3 border"
                                        name="images{{$num}}"
                                        no_text="1"
                                        ></read-image-file-component>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>



            <!--動画(movie)-->
            @if( $store_item->id )
                <div class="d-block mb-4">
                    <div class="form-label">動画</div>

                    <div class="d-flex align-items-center justify-content- mt-2 gap-3 border p-3">
                        @if ($store_item->movie_path or $store_item->youtube_url)
                            <movie-modal-component
                            id   ="{{$store_item->id.'-movie'}}"
                            title="{{ $store_item->name.( $store_item->movie_path ?'（モバイル）':'(Youtube)') }}"
                            src  ="{{ $store_item->movie_path ?? $store_item->youtube_url }}"
                            btn_label="{{ $store_item->name.( $store_item->movie_path ?'':'Youtube') }}動画再生"
                            max_width="400px"
                            ></movie-modal-component>
                        @else
                            <span>未登録</span>
                        @endif

                        <a href="{{route('admin.store_item.movie.edit',$store_item)}}"
                        class="btn btn-light border">編集</a>
                    </div>
                </div>
            @endif



            <!--販売価格(price)-->
            <label class="d-block mb-4">
                <div class="form-label">販売価格</div>

                <div class="row align-items-center g-2">
                    <div class="col-auto" style="width: 12rem;">
                        <input value="{{old('price', $store_item->price ?? 0 )}}"
                        name="price"
                        type="number" class="form-control text-end" min="0">
                    </div>
                    <div class="col-auto">
                        <span>円(税込)</span>
                    </div>
                </div>

                <!--error message-->
                @if ( $errors->has('price') )
                    <div class="text-danger"> {{$errors->first('price')}} </div>
                @endif
            </label>



            <!--在庫数(count)-->
            <label class="d-block mb-4">
                <div class="form-label">在庫数</div>

                <div class="row align-items-center g-2">
                    <div class="col-auto" style="width: 12rem;">
                        <input value="{{old('count', $store_item->count ?? 0 )}}"
                        name="count"
                        type="number" class="form-control text-end" min="0">
                    </div>
                    <div class="col-auto">
                        <span>点</span>
                    </div>
                </div>

                <!--error message-->
                @if ( $errors->has('count') )
                    <div class="text-danger"> {{$errors->first('count')}} </div>
                @endif
            </label>



            <!--還元ポイント(points_redemption)-->
            <label class="d-block mb-4">
                <div class="form-label">還元ポイント</div>
                <div class="form-text">*商品購入時に、ユーザーに還元されるポイントです。</div>

                <div class="row align-items-center g-2">
                    <div class="col-auto" style="width: 12rem;">
                        <input value="{{old('points_redemption', $store_item->points_redemption ?? 0 )}}"
                        name="points_redemption"
                        type="number" class="form-control text-end" min="0">
                    </div>
                    <div class="col-auto">
                        <span>pt</span>
                    </div>
                </div>

                <!--error message-->
                @if ( $errors->has('points_redemption') )
                    <div class="text-danger"> {{$errors->first('points_redemption')}} </div>
                @endif
            </label>


            <!--カテゴリー(category_id)-->
            <label class="d-block mb-4">
                <div class="form-label">カテゴリー</div>
                <select class="form-select" name="category_id">
                    {{-- <option value="">選択してください</option> --}}

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                        @if($store_item->category_id == $category->id) selected @endif
                        @if(old('category_id')  == $category->id) selected @endif
                        >{{ $category->name }}</option>
                    @endforeach

                </select>
                <!--error message-->
                @if ( $errors->has('category_id') )
                    <div class="text-danger"> {{$errors->first('category_id')}} </div>
                @endif
            </label>



            <!--ブランド名(brand_name)-->
            <label class="d-block mb-4">
                <div class="form-label">ブランド名(任意)</div>

                <encodedーinputtext-component
                id="brand_name" name="brand_name"
                style_class="form-control"
                default_body="{{ $errors->all() ? urldecode( old('brand_name') ) : $store_item->brand_name }}"
                ></encodedーinputtext-component>

                <!--error message-->
                @if ( $errors->has('brand_name') )
                    <div class="text-danger"> {{$errors->first('brand_name')}} </div>
                @endif
            </label>


        </div>


    </div>
    <div class="col-md-6">


        <!--商品説明(discription)-->
        <label class="d-block mb-4">
            <div class="form-label">
                商品説明
                {{-- <span class="text-danger">＊</span> --}}
            </div>

            <encodedーtextarea-component
            name="discription" id="discription"
            style_class="form-control" rows="10"
            placeholder="商品説明文を入力してください。"
            default_body="{{ $errors->all() ? urldecode( old('discription') ) : $store_item->discription_text }}"
            ></encodedーtextarea-component>


            <!--error message-->
            @if ( $errors->has('discription') )
                <div class="text-danger"> {{$errors->first('discription')}} </div>
            @endif
        </label>


        <!--スライド設定(is_slide-->
        <div class="d-block mb-4">
            <div class="form-label">スライド設定</div>

            <div class="card p-2">

                <div class="d-flex flex-column gap-3 ps-4">
                    <div class="form-text">トップページのスライドに表示させますか？</div>
                    <label class="form-check">
                        <input name="is_slide" value="1"
                        {{ $store_item->is_slide==true ? 'checked' : '' }}
                        class="form-check-input" type="radio">
                        <div class="form-check-div">表示させる</div>
                    </label>
                    <label class="form-check">
                        <input name="is_slide" value="0"
                        {{ $store_item->is_slide==false ? 'checked' : '' }}
                        class="form-check-input" type="radio">
                        <div class="form-check-div">表示させない</div>
                    </label>
                </div>

                <!--error message-->
                @if ( $errors->has('is_slide') )
                    <div class="text-danger"> {{$errors->first('is_slide')}} </div>
                @endif

            </div>
        </div>


        <!--公開設定(is_published)-->
        <div class="d-block my-5">
            <div class="form-label">
                公開設定
            </div>

            <div class="px-3">
                <!-- 公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="1" type="radio" class="form-check-input"
                        {{ $store_item->is_published ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開</h6>

                    </div>
                    <ul class="form-text m-0">
                        <li>お知らせを公開します。</li>
                    </ul>
                    @if( $store_item->is_published ) <div class="form-text">
                        {{\Carbon\Carbon::parse($store_item->published_at)->format('公開日：Y年m月d日 H:i')}}
                    </div> @endif
                </label>


                <!-- 公開予約 -->
                <label class="card p-2 mb-3">
                    <div class="form-check ">
                        <input name="is_published" value="2" type="radio" class="form-check-input"
                        {{ !$store_item->is_published && $store_item->published_at ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">公開予約</h6>
                    </div>
                    <ul class="form-text m-0 pe-3">
                        <li>お知らせを公開予約します。</li>
                        <li>「公開予約日時」を設定すると、指定日時にお知らせを自動公開することができます。</li>
                    </ul>

                    <div class="input-group mb-3 px-3">
                        <span class="input-group-text" >公開予約日時</span>
                        <input name="published_at"
                        type="datetime-local" class="form-control"
                        value="{{ $store_item->published_at ? $store_item->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\T00:00') }}"
                        min="{{ now()->format('Y-m-d\T00:00') }}">

                    </div>
                </label>


                <!-- 非公開 -->
                <label class="card p-2 mb-3">
                    <div class="form-check">
                        <input name="is_published" value="0"
                        type="radio" id="publishedType3" class="form-check-input"
                        {{ !$store_item->published_at ? 'checked' : ''}}
                        >
                        <h6 class="mb-0 mt-1">非公開</h6>
                    </div>
                    <ul class="form-text m-0">
                        <li>お知らせを非公開します。</li>
                        <li>非公開時はお知らせ一覧にお知らせが表示されなくなります。</li>
                    </ul>
                </label>
            </div>
        </div>



        <div class="col-md-6 my-5 mx-auto">
            @if (!$store_item->id)
            <disabled-button style_class="btn btn-primary text-white w-100 shadow" btn_text="登録する"></bdisabled-button>
            @else
            <disabled-button style_class="btn btn-warning text-white w-100 shadow" btn_text="更新する"></bdisabled-button>
            @endif
        </div>


    </div>
</div>
