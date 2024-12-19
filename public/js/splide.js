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
        padding: '5vw',
        focus  : 'center',
        perPage : 3,
        autoplay: true,

    } );
    splidePc.mount();

    /* mobile */
    var splideMobile = new Splide( '#splide_mobile', {

        type     : 'loop',
        padding: '10vw',
        focus  : 'center',
        perPage : 1,
        autoplay: true,

    } );
    splideMobile.mount();



    /* gacha */
    // 共通のクラス名で全スライダーを取得
    const sliders = document.querySelectorAll('.splide_gacha');

    // 各スライダーに対してSplideインスタンスを作成
    sliders.forEach((slider) => {

        new Splide( '#'+slider.id , {

            type     : 'slide',
            focus  : 'center',
            perPage : 6,
            autoplay: true,
            pagination: false,


        }).mount();

    });


} );
