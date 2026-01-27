@extends('admin.layouts.app')


@section('title', 'ガチャ一覧設定' )


@section('meta') @php
$active_key = 'gacha';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.gacha') }}"
                >{{ 'ガチャ管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{'ガチャ一覧設定'}}</li>
            </ol>
        </nav>



        <h2 class="mt-5 py-3 border-bottom">{{'ガチャ一覧設定'}}</h2>

        <a href="{{route('admin.gacha')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>一覧に戻る</a>



        <section>
            <div class="mx-auto my-5" style="max-width:900px;">

                <div class="card">
                    <h5 class="card-header">
                        ガチャ販売機の画像利用設定
                    </h5>

                    <form method="post" action="{{ route('admin.gacha.settings.update_list') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')

                        <div class="card-body row g-2">


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
                                    noimg_path="{{$data['gacha_settings_card_image_head_default']}}"
                                    style_class="ratio ratio-6x1 border rounded-3"
                                    name="image"
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
                                    noimg_path="{{$data['gacha_settings_card_image_body_default']}}"
                                    style_class="ratio ratio-16x9 border rounded-3"
                                    name="image"
                                    ></read-image-file-100k-component>

                                    <!--error message-->
                                    @if ( $errors->has('gacha_settings_card_image_body') )
                                        <div class="text-danger"> {{$errors->first('gacha_settings_card_image_body')}} </div>
                                    @endif
                                </label>

                            </div>


                            <div class="col-12">
                                <button class="btn btn-primary text-white" type="submit">更新</button>
                            </div>


                        </div>
                    </form>

                </div>

            </div>
        </section>



        <section>
            <div class="mx-auto my-5" style="max-width:900px;">

                <div class="card">
                    <h5 class="card-header">
                        ガチャの読み込み中動画の利用設定
                    </h5>

                    <form method="post" action="{{ route('admin.gacha.settings.update_list') }}" class="needs-validation" novalidate>
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
                                    video_path="{{ $data['gacha_settings_loading_movie_path'] }}"
                                    ></read-movie-file-component>
                                </div>


                                <!--error message-->
                                @if ( $errors->has('gacha_settings_loading_movie_path') )
                                    <div class="text-danger"> {{$errors->first('gacha_settings_loading_movie_path')}} </div>
                                @endif
                            </label>



                            <div class="col-12">
                                <button class="btn btn-primary text-white" type="submit">更新</button>
                            </div>


                        </div>
                    </form>

                </div>

            </div>
        </section>



        <section>
            <div class="mx-auto my-5" style="max-width:900px;">

                <div class="card">
                    <h5 class="card-header">
                        その他設定
                    </h5>

                    <form method="post" action="{{ route('admin.gacha.settings.update_list') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')
                        {{-- <input type="hidden" name="admin_id" value="{{$edit_admin->id}}">
                        <input type="hidden" name="form-switch" value="その他設定変更フォーム"> --}}
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
                                            <input name="type" value="{{$value}}"
                                            @if( $value == old('gacha_settings_size', $data['gacha_settings_size'] ) ) checked @endif
                                            class="form-check-input" type="radio">
                                            <div class="form-check-div">{{ $label }}</div>
                                        </label>
                                    @endforeach
                                </div>


                            </div>



                            <div class="col-12">
                                <button class="btn btn-primary text-white" type="submit">更新</button>
                            </div>


                        </div>
                    </form>

                </div>

            </div>
        </section>





    </div>
@endsection
