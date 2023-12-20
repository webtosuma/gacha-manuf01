<article class="container my-5">
    <div class="mx-auto" style="max-width:900px;">

        <!--タイトル-->
        <h2 class="fs-1 fw-bold">{{ $infomation->title }}</h2>
        @if( $infomation->is_published )
            <!--公開-->
            <div class="">{{ $infomation->published_at->format('Y.m.d') }}</div>
        @elseif( $infomation->published_at > now() )
            <!--公開予約-->
            <div class="text-warning">{{ '公開予約：'.$infomation->published_at->format('Y.m.d') }}</div>
        @else
            <!--非公開-->
            <div class="text-danger">{{ '非公開' }}</div>
        @endif

        <!--画像-->
        @if( $infomation->image_path )
            <div class="mx-auto overflow-auto my-3" style="max-width:4000px;">
                <div class="overflow-hidden rounded-4 border">
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


        <div class="my-5">
            <div class="col-md-6 mx-auto my-3">
                <a href="#" onClick="history.back(); return false;"
                class="btn btn-lg btn-light border rounded-pill w-100"
                >戻る</a>
            </div>
        </div>
    </div>
</article>
