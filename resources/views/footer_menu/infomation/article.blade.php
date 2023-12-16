<article class="container my-5">
    <div class="mx-auto" style="max-width:900px;">

        <!--タイトル-->
        <h2 class="fs-1 fw-bold">{{ $infomation->title }}</h2>
        <div class="">{{ $infomation->published_at->format('Y.m.d') }}</div>

        <!--画像-->
        @if( $infomation->image_path )
            <div class="mx-auto overflow-auto my-3" style="max-width:400px;">
                <div class="overflow-hidden rounded-4">
                    <ratio-image-component
                    url="{{ $infomation->image_path }}" style_class="ratio ratio-4x3 w-100"
                    ></ratio-image-component>
                </div>
            </div>
        @endif

        <!--本文-->
        <p class="p-3 mt-3"
        style="border-radius:1rem; background:rgb(255, 255, 255, .9);"
        ><replace-text-component text="{{ $infomation->body_text }}" ></replace-text-component></p>

    </div>
</article>
