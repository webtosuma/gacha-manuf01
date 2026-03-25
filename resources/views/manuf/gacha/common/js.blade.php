<!-- splide js -->
<script src="
https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
"></script>
<script src="{{ asset('js/splide.js') }}"></script>

<script>
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

    });
</script>
