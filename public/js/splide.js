"use strict";
/**
 * ==========================================
 *  スライダー(splide)　JS
 * ==========================================
*/
document.addEventListener( 'DOMContentLoaded', function() {


    /* PC */
    var splidePc = new Splide( '#splide_pc', {

        type     : 'loop',
        padding: '1vw',
        focus  : 'center',
        perPage : 4, //3
        autoplay: true,

    } );
    splidePc.mount();

    /* mobile */
    var splideMobile = new Splide( '#splide_mobile', {

        type     : 'loop',
        padding: '1vw',
        focus  : 'center',
        perPage : 2, //1
        autoplay: true,

    } );
    splideMobile.mount();



    /* gacha */
    // 共通のクラス名で全スライダーを取得
    const sliders = document.querySelectorAll('.splide_gachas');

    // 各スライダーに対してSplideインスタンスを作成
    sliders.forEach((slider) => {

        new Splide( '#'+slider.id , {

            type   : 'loop',
            focus  : 'center',
            perPage : 6,
            autoplay: true,
            pagination: false,


        }).mount();

    });


} );
