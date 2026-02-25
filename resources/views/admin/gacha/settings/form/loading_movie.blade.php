<section>
    <div class="mx-auto my-5" style="max-width:900px;">

        <div class="card">
            <h5 class="card-header">
                ガチャの読み込み中動画の利用設定
            </h5>

            <form method="post"
            action="{{ route('admin.gacha.settings.update_loading_movie') }}"
            onsubmit="stopOnbeforeunload()"
            enctype="multipart/form-data"
            class="needs-validation" novalidate>
                @csrf
                @method('PATCH')

                <div class="card-body row g-2">


                    <!-- ガチャの読み込み中動画(gacha_settings_loading_movie) -->
                    <div>
                        <label class="form-check-label fw-bold fs-5" for="gacha_settings_loading_movie"
                        >ガチャの読み込み中動画を利用する</label>

                        <div>ガチャ販売機の画像を利用しますか？</div>
                        <div class="d-flex align-items-end mb-3">
                            <div style="width:7rem;">利用しない</div>
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input fs-3" type="checkbox"
                                name="gacha_settings_loading_movie"
                                id="gacha_settings_loading_movie"
                                {{ old('gacha_settings_loading_movie',$data['gacha_settings_loading_movie']) ? 'checked' : ''}}
                                >
                            </div>
                            <div class="">利用する</div>
                        </div>
                    </div>


                    <!--モバイル用動画(gacha_settings_loading_movie_path)-->
                    <label class="d-block mb-5">
                        <div class="col-md-6">
                            <read-movie-file-component
                            name="gacha_settings_loading_movie_path"
                            video_path.  ="{{ $data['gacha_settings_loading_movie_path'] }}"
                            no_video_path="{{ $data['gacha_settings_loading_movie_path'] }}"
                            ></read-movie-file-component>
                        </div>


                        <!--error message-->
                        @if ( $errors->has('gacha_settings_loading_movie_path') )
                            <div class="text-danger"> {{$errors->first('gacha_settings_loading_movie_path')}} </div>
                        @endif
                    </label>



                    <div class="col-md-4 mt-5">
                        <button class="btn btn-warning text-white shadow w-100" type="submit">更新する</button>
                    </div>


                </div>
            </form>

        </div>

    </div>
</section>
