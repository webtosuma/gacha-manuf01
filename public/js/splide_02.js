"use strict";
/**
 * ==========================================
 *  スライダー(splide)　JS
 * ==========================================
*/
document.addEventListener( 'DOMContentLoaded', function() {

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






} );
