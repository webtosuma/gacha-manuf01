<section>
    <div class="mx-auto my-5" style="max-width:900px;">

        <div class="card">
            <h5 class="card-header">
                その他設定
            </h5>

            <form method="post"
            action="{{ route('admin.gacha.settings.update_other') }}"
            onsubmit="stopOnbeforeunload()"
            class="needs-validation" novalidate>
                @csrf
                @method('PATCH')

                <div class="card-body row g-2">


                    <!-- 限定ガチャのラベル表示ラベル表示(gacha_settings_type_label_image) -->
                    <div class="col-12">
                        <label class="form-check-label fw-bold fs-5" for="gacha_settings_type_label_image"
                        >限定ガチャのラベル表示表示</label>

                        <div>ガチャのサムネ画像に、限定ガチャのラベル表示をしますか？</div>
                        <div class="d-flex align-items-end mb-3">
                            <div style="width:7rem;">表示しない</div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input fs-3" type="checkbox"
                                name="gacha_settings_type_label_image"
                                id="gacha_settings_type_label_image"
                                {{ old('gacha_settings_type_label_image',$data['gacha_settings_type_label_image']) ? 'checked' : ''}}
                                >
                            </div>
                            <div class="">表示する</div>
                        </div>
                    </div>



                    <!-- 限定ガチャのテキスト表示(gacha_settings_type_label_text) -->
                    <div class="col-12">
                        <label class="form-check-label fw-bold fs-5" for="gacha_settings_type_label_text"
                        >限定ガチャのテキスト表示</label>

                        <div>ガチャ一覧の上に、限定ガチャのテキストを表示をしますか？</div>
                        <div class="d-flex align-items-end mb-3">
                            <div style="width:7rem;">表示しない</div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input fs-3" type="checkbox"
                                name="gacha_settings_type_label_text"
                                id="gacha_settings_type_label_text"
                                {{ old('gacha_settings_type_label_text',$data['gacha_settings_type_label_text']) ? 'checked' : ''}}
                                >
                            </div>
                            <div class="">表示する</div>
                        </div>
                    </div>



                    <!-- ガチャの表示サイズ(gacha_settings_size) -->
                    <div class="col-12">
                        <label class="form-check-label fw-bold fs-5" for="gacha_settings_size"
                        >デフォルトの表示サイズ</label>

                        <div>一覧ページに表示するガチャのデフォルトサイズを選択してください。</div>

                        <div class="d-flex gap-4 p-3">
                            @php $selects = [ 'lg'=>'大きく表示','sm'=>'小さく表示',] @endphp
                            @foreach ( $selects as $value => $label)
                                <label class="form-check border p-3 rounded-pill">
                                    <input name="gacha_settings_size" value="{{$value}}"
                                    @if( $value == old('gacha_settings_size', $data['gacha_settings_size'] ) ) checked @endif
                                    class="form-check-input" type="radio">
                                    <div class="form-check-div">{{ $label }}</div>
                                </label>
                            @endforeach
                        </div>


                    </div>



                    <div class="col-md-4 mt-5">
                        <button class="btn btn-warning text-white shadow w-100" type="submit">更新する</button>
                    </div>


                </div>
            </form>

        </div>

    </div>
</section>
