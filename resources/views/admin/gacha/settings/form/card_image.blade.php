<section>
    <div class="mx-auto my-5" style="max-width:900px;">

        <div class="card">
            <h5 class="card-header">
                ガチャ販売機の画像利用設定
            </h5>

            <form method="post"
            action="{{ route('admin.gacha.settings.update_card_image') }}"
            onsubmit="stopOnbeforeunload()"
            enctype="multipart/form-data"
            class="needs-validation" novalidate>
                @csrf
                @method('PATCH')

                <div class="card-body">


                    <!-- ガチャ販売機の画像を利用する(gacha_settings_card_image) -->
                    <div>
                        <label class="form-check-label fw-bold fs-5" for="gacha_settings_card_image"
                        >ガチャ販売機の画像を利用する</label>

                        <div>ガチャ販売機の画像を利用しますか？</div>
                        <div class="d-flex align-items-end mb-3">
                            <div style="width:7rem;">利用しない</div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input fs-3" type="checkbox"
                                name="gacha_settings_card_image"
                                id="gacha_settings_card_image"
                                {{ old('gacha_settings_card_image',$data['gacha_settings_card_image']) ? 'checked' : ''}}
                                >
                            </div>
                            <div class="">利用する</div>
                        </div>
                    </div>


                    <div class="col-md-4 col-6">

                        <!--ガチャ販売機 頭部画像(gacha_settings_card_image_head)-->
                        <label class="d-block">
                            <div class="form-label">ガチャ販売機 頭部画像</div>

                            <read-image-file-100k-component
                            img_path=  "{{$data['gacha_settings_card_image_head']}}"
                            noimg_path="{{$data['gacha_settings_card_image_head']}}"
                            style_class="ratio ratio-6x1 border rounded-3"
                            name="gacha_settings_card_image_head"
                            ></read-image-file-100k-component>

                            <!--error message-->
                            @if ( $errors->has('gacha_settings_card_image_head') )
                                <div class="text-danger"> {{$errors->first('gacha_settings_card_image_head')}} </div>
                            @endif
                        </label>


                        <div class=" ratio ratio-4x3 border rounded-4 mb-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="fw-bold fs-5">ガチャ画像</span>
                            </div>
                        </div>


                        <!--ガチャ販売機 本体画像(gacha_settings_card_image_body)-->
                        <label class="d-block mb-4">
                            <div class="form-label">ガチャ販売機 本体画像</div>

                            <read-image-file-100k-component
                            img_path  ="{{$data['gacha_settings_card_image_body']}}"
                            noimg_path="{{$data['gacha_settings_card_image_body']}}"
                            style_class="ratio ratio-16x9 border rounded-3"
                            name="gacha_settings_card_image_body"
                            ></read-image-file-100k-component>

                            <!--error message-->
                            @if ( $errors->has('gacha_settings_card_image_body') )
                                <div class="text-danger"> {{$errors->first('gacha_settings_card_image_body')}} </div>
                            @endif
                        </label>

                    </div>


                    <div class="col-md-4 mt-5">
                        <button class="btn btn-warning text-white shadow w-100" type="submit">更新する</button>
                    </div>


                </div>
            </form>

        </div>

    </div>
</section>
