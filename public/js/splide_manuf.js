"use strict";

document.addEventListener('DOMContentLoaded', function () {

    /*.メイン */
    var main = new Splide('#main-slider', {

        type       : 'fade',
        heightRatio: 1,
        pagination : false,
        arrows     : false,
        cover      : true,

    });

    /* メニュー */
    var thumbnails = new Splide('#thumbnail-slider', {

        rewind       : true,
        fixedWidth   : 80,
        fixedHeight  : 80,
        isNavigation : true,
        gap          : 0,//スライダーの間隔
        focus        : 'center',
        pagination   : false,
        cover        : true,

        breakpoints : {//スマホ対応
            640: {
                fixedWidth  : 66,
                fixedHeight : 66,
            },
        },

    });

    main.sync(thumbnails);

    main.mount();
    thumbnails.mount();



    /* ガチャマシーンを選ぶ PC */
    var splidePc = new Splide( '#splide_title_machine', {

        type     : 'loop',
        padding: '30px',
        focus  : 'center',
        perPage : 3, //3
        autoplay: true,
        pagination : false,

    } );
    splidePc.mount();


    /* ガチャマシーンを選ぶ Mobile */
    var splidePc = new Splide( '#splide_title_machine_mobile', {

        type     : 'loop',
        padding: '80px',
        focus  : 'center',
        perPage : 1, //3
        autoplay: true,
        pagination : false,

    } );
    splidePc.mount();

});
