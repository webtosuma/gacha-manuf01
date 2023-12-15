@if ( !config('app.debug') )


    @php

        $title = isset( $title ) ? $title :
        "cardFesta（カードフェスタ）|オンラインオリパ・ネットオリパを24時間365日楽しめる！国内送料は無料！ ";
        $description = isset( $description ) ? $description :
        "オンラインオリパ引くならcardFesta（カードフェスタ）! 高確率、爆アドガチャを多数ご用意しています。ポケカ・ワンピースなど人気オリパを24時間365日楽しめます。国内送料無料で、低コストガチャからハイリスクハイリターンなガチャなど楽しみ方は自由自在！ ";
        $image = isset( $image ) ? $image :
        asset('storage/site/image/logo00.png');

    @endphp



    <meta  name="description" content="{{ $description }}">
    <meta  name="keywords" content="オンラインオリパ,ネットオリパ,オリパ,ポケカ,ワンピース,カード,ガチャ,トレカ, ">

    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}" />
    <meta property="og:url"         content="https://cardfesta.jp/" />
    <meta property="og:site_name"   content="cardFesta" />
    <meta property="og:image"       content="{{ $image }}" />
    <meta property="og:locale"      content="ja_JP"  />
    <meta property="og:type"        content="website">

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@CardFesta7627" />
    <meta name="twitter:title" content="{{ $title }}" />
    <meta name="twitter:description" content="{{ $description }}" />
    <meta name="twitter:image" content="{{ $image }}" />



@endif
