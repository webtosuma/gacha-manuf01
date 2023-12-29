{{-- @if ( !config('app.debug') ) --}}


    @php

        $meta_title = isset( $meta_title ) ? $meta_title.'-'.config('app.name') :
        "cardFesta（カードフェスタ）|オンラインオリパ・ネットオリパを24時間365日楽しめる！国内送料は無料！ ";
        $meta_description = isset( $meta_description ) ? $meta_description :
        "オンラインオリパ引くならcardFesta（カードフェスタ）! 高確率、爆アドガチャを多数ご用意しています。ポケカ・ワンピースなど人気オリパを24時間365日楽しめます。国内送料無料で、低コストガチャからハイリスクハイリターンなガチャなど楽しみ方は自由自在！ ";
        $meta_image = isset( $meta_image ) ? $meta_image :
        asset('storage/site/image/logo00.png');

    @endphp



    <meta  name="description" content="{{ $meta_description }}">
    <meta  name="keywords" content="オンラインオリパ,ネットオリパ,オリパ,ポケカ,ワンピース,カード,ガチャ,トレカ, ">

    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
    <meta property="og:url"         content="https://cardfesta.jp/" />
    <meta property="og:site_name"   content="cardFesta" />
    <meta property="og:image"       content="{{ $meta_image }}" />
    <meta property="og:locale"      content="ja_JP"  />
    <meta property="og:type"        content="website">

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@CardFesta7627" />
    <meta name="twitter:title" content="{{ $meta_title }}" />
    <meta name="twitter:description" content="{{ $meta_description }}" />
    <meta name="twitter:image" content="{{ $meta_image }}" />



{{-- @endif --}}
