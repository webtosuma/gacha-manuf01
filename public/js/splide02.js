"use strict";
/**
 * ==========================================
 *  スライダー(splide)　JS
 * ==========================================
*/
document.addEventListener( 'DOMContentLoaded', function() {


    /* スライダーPC */
    var splidePc = new Splide( '#splide_pc', {

        type     : 'loop',
        padding: '1vw',
        focus  : 'center',
        perPage : 4,
        autoplay: true,

    } );
    splidePc.mount();

    /* スライダーmobile */
    var splideMobile = new Splide( '#splide_mobile', {

        type     : 'loop',
        padding: '1vw',
        focus  : 'center',
        perPage : 2,
        autoplay: true,

    } );
    splideMobile.mount();



    /* メディアPC */
    var splidePc = new Splide( '#splide_media_pc', {

        type     : 'loop',
        padding: '10vw',
        focus  : 'center',
        perPage : 2,
        autoplay: true,

    } );
    splidePc.mount();

    /* メディアmobile */
    var splideMobile = new Splide( '#splide_media_mobile', {

        type     : 'loop',
        padding: '10vw',
        focus  : 'center',
        perPage : 1,
        autoplay: true,

    } );
    splideMobile.mount();


    /* gacha */
    // 共通のクラス名で全スライダーを取得
    // const sliders = document.querySelectorAll('.splide_gacha');

    // 各スライダーに対してSplideインスタンスを作成
    // sliders.forEach((slider) => {

    //     // new Splide( '#'+slider.id , {

    //     //     type   : 'splide',//ループしない
    //     //     rewind : false,   // 最後のスライドの後に最初に戻らないようにする
    //     //     focus  : 'start',
    //     //     perPage : 6,
    //     //     autoplay: false,
    //     //     pagination: false,

    //     // }).mount();

    // });




} );
