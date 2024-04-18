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
        padding: '10vw',
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


} );
