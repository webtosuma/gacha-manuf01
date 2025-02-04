<div class="splide__track">
    <ul class="splide__list">
        {{-- @foreach ($gacha->g_prizes as $si => $gacha_prize)
            <li class="splide__slide p-1">
                <ratio-image-component
                style_class="ratio ratio-1x1 rounded-2"
                url="{{$gacha_prize->prize->image_path}}"
                ></ratio-image-component>
            </li>
        @endforeach --}}

        @foreach ($gacha->slide_imgs as $image_path)
            <li class="splide__slide p-1">
                <ratio-image-component
                style_class="ratio ratio-1x1 rounded-2"
                url="{{$image_path}}"
                ></ratio-image-component>
            </li>
        @endforeach

    </ul>
</div>
