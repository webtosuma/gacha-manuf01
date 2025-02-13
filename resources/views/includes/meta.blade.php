@if ( true )
{{-- @if ( env('APP_DEBUG') === true ) --}}

    <!--検索結果に非表示-->
    <meta name="robots" content="noindex" />

@endif

@if ( false )
{{-- @if ( env('APP_DEBUG') === false  ) --}}


    @php
        # 最新のガチャ
        $meta_gachas =
        \App\Http\Controllers\GachaController::getPublishedGachas( $category_code='all' );

        # メタのデフォルト画像
        $meta_default_image = $meta_gachas->count() > 0
        ? $meta_gachas[0]->image_path : asset('storage/site/image/logo.png');

        # metaタイトル (gacha.index.bladeは手動修正)
        $meta_title = isset( $meta_title ) ? $meta_title.'-'.config('app.name') :
        config('app.name')."|オンラインガチャを24時間365日楽しめる！ ";

        # meta説明文
        $meta_description = isset( $meta_description ) ? $meta_description :
        "オンラインガチャなら".config('app.name')."! 高確率、爆アドガチャを多数ご用意。24時間365日楽しめます。 ";
        $meta_image = isset( $meta_image ) ? $meta_image :
        $meta_default_image;//メタのデフォルト画像

        #metaキーワード
        $meta_keyword = "オンラインガチャ,ガチャ, ";

    @endphp



    <meta  name="description" content="{{ $meta_description }}">
    <meta  name="keywords"    content="{{ $meta_keyword }}">

    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
    <meta property="og:url"         content="{{ config('app.url') }}" />
    <meta property="og:site_name"   content="{{ config('app.name') }}" />
    <meta property="og:image"       content="{{ $meta_image }}" />
    <meta property="og:locale"      content="ja_JP"  />
    <meta property="og:type"        content="website">

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{ $meta_title }}" />
    <meta name="twitter:description" content="{{ $meta_description }}" />
    <meta name="twitter:image" content="{{ $meta_image }}" />



@endif
