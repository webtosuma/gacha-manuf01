<!--紹介画像-->
<div class="row g-0 my-3">
    @foreach ( $gacha_title->discription_images as $url)
        <div class="col-12">
            <img src="{{$url}}" alt="" class="w-100">
        </div>
    @endforeach
</div>

