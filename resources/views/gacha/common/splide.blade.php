<div class="splide__track">
    <ul class="splide__list">
        @foreach ($gacha->g_prizes as $si => $gacha_prize)

            <li class="splide__slide">
                <ratio-image-component
                style_class="ratio ratio-3x4 rounded-2"
                url="{{$gacha_prize->prize->image_path}}"
                ></ratio-image-component>
            </li>

        @endforeach
    </ul>
</div>
