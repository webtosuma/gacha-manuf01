<div class="mb-4 position-relative" style="min-height:300px;">

    <div class="
    position-absolute top-0 start-0
    w-100 rounded-4 p-2 overflow-hidden h-100
    " style="z-index:-1; height:300px;">
        <div class="d-flex gap-2 align-items-center justify-content-center h-100">

            <div class="spinner-grow text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>

        </div>
    </div>



    @php
    $examples= [
        $gacha_title->image_samune_path,
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095777/melotabi_mejirushi_1.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095784/melotabi_mejirushi_2.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095791/melotabi_mejirushi_3.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095807/melotabi_mejirushi_4.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095814/melotabi_mejirushi_5.jpg',
    ];
    @endphp

    <div>

        <!-- メイン -->
        <div id="main-slider" class="splide  mb-3 col-md-8 mx-auto">

            <div class="splide__track">
                <ul class="splide__list">

                    @foreach ($examples as $url)
                    <li class="splide__slide">

                        <div class="ratio ratio-1x1 border rounded bg-white"
                        style="
                        background: no-repeat center center / contain;
                        background-image: url({{ $url }});
                        "></div>

                    </li>
                    @endforeach

                </ul>
            </div>

        </div>


        <!-- サムネ -->
        <div id="thumbnail-slider" class="splide">

            <div class="splide__track">
                <ul class="splide__list">

                    @foreach ($examples as $url)
                    <li class="splide__slide">

                        <div class="ratio ratio-1x1 border rounded bg-white"
                        style="
                        background: no-repeat center center / contain;
                        background-image: url({{ $url }});
                        "></div>

                    </li>
                    @endforeach

                </ul>
            </div>

        </div>
    </div>


</div>
