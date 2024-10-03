<meta name="robots" content="noindex" />

@if ( false )


    @php
        # 最新のガチャ
        $meta_gachas =
        \App\Http\Controllers\GachaController::getPublishedGachas( $category_code='all' );

        # メタのデフォルト画像
        $meta_default_image = $meta_gachas->count() > 0
        ? $meta_gachas[0]->image_path : asset('storage/site/image/logo512.png');

        $meta_title = isset( $meta_title ) ? $meta_title.'-'.config('app.name') :
        config('app.name')."|オンラインオリパ・ネットオリパを24時間365日楽しめる！国内送料は無料！ ";
        $meta_description = isset( $meta_description ) ? $meta_description :
        "オンラインオリパ引くなら".config('app.name')."! 高確率、爆アドガチャを多数ご用意しています。ポケカ・ワンピースなど人気オリパを24時間365日楽しめます。国内送料無料で、低コストガチャからハイリスクハイリターンなガチャなど楽しみ方は自由自在！ ";
        $meta_image = isset( $meta_image ) ? $meta_image :
        $meta_default_image;//メタのデフォルト画像

    @endphp



    <meta  name="description" content="{{ $meta_description }}">
    <meta  name="keywords" content="オンラインオリパ,ネットオリパ,オリパ,ポケカ,ワンピース,ガンダムアーセナルベース,カード,ガチャ,トレカ, ">

    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
    <meta property="og:url"         content="{{ config('app.url') }}" />
    <meta property="og:site_name"   content="{{ config('app.name') }}" />
    <meta property="og:image"       content="{{ $meta_image }}" />
    <meta property="og:locale"      content="ja_JP"  />
    <meta property="og:type"        content="website">

    <meta name="twitter:card" content="summary" />
    {{-- <meta name="twitter:site" content="@CardFesta7627" /> --}}
    <meta name="twitter:title" content="{{ $meta_title }}" />
    <meta name="twitter:description" content="{{ $meta_description }}" />
    <meta name="twitter:image" content="{{ $meta_image }}" />



@endif
