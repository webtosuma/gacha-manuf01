"use strict";
/**
 * ==========================================
 *  ECストアー　スライダー(splide)　JS
 * ==========================================
*/
document.addEventListener( 'DOMContentLoaded', function() {


    /* PC */
    var splidePc = new Splide( '#splide_store_pc', {

        type     : 'loop',
        padding: '3vw',
        focus  : 'center',
        perPage : 4, //3
        autoplay: true,

    } );
    splidePc.mount();

    /* mobile */
    var splideMobile = new Splide( '#splide_store_mobile', {

        type     : 'loop',
        padding: '8vw',
        focus  : 'center',
        perPage : 2, //1
        autoplay: true,

    } );
    splideMobile.mount();



    /* store item */
    // 共通のクラス名で全スライダーを取得
    const sliders = document.querySelectorAll('.splide_store_item');

    // 各スライダーに対してSplideインスタンスを作成
    sliders.forEach((slider) => {

        new Splide( '#'+slider.id , {

            type   : 'splide',//ループしない
            rewind : false,   // 最後のスライドの後に最初に戻らないようにする
            focus  : 'start',
            perPage : 4,
            autoplay: false,
            pagination: false,
            padding: '3rem',


        }).mount();

    });


} );
