<!--トップー-->
<section class="p- pb-md-5">
    <div class="mx-auto overflow-auto px-0" style="max-width:1200px;">


        <div class="d-none d-md-block overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-16x9 w-100"
            ></ratio-image-component>
        </div>
        <div class="d-md-none  overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-1x1 w-100"
            ></ratio-image-component>
        </div>


    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:1200px;">
    @foreach ($gacha->discriptions as $discription)
        <section class="py-5 col-12">

            <div class="container overflow-auto" style="max-width:600px;">

                <!-- 賞ラベル -->
                @switch( $discription->rank_id )
                    @case('XA')
                        <!--01 等賞ー-->
                        <div class="col-8 mx-auto mb-3">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/gacha/01prize.png')}}" alt="1等賞">
                        </div>
                        @break
                    @case('XB')
                        <!--02 等賞ー-->
                        <div class="col-8 mx-auto mb-3">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/gacha/02prize.png')}}" alt="2等賞">
                        </div>
                        @break
                    @case('XC')
                        <!--03 等賞ー-->
                        <div class="col-8 mx-auto mb-3">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/gacha/03prize.png')}}" alt="3等賞">
                        </div>
                        @break
                    @case('XD')
                        <!--04 等賞ー-->
                        <div class="col-8 mx-auto mb-3">
                            <img class="d-block w-100"
                            src="{{asset('storage/site/image/gacha/04prize.png')}}" alt="4等賞">
                        </div>
                        @break
                @endswitch

                <!-- 商品画像 -->
                @if ( $discription->image_path )
                    <img class="d-block w-100 shadow" style="border-radius:1rem;"
                    src="{{$discription->image_path }}" alt="商品画像">
                @endif

                <!-- 商品説明文 -->
                @if ( $discription->sorce )
                <p class="p-3 mt-2 form-text text-secondary" style="border-radius:1rem; background:rgb(255, 255, 255, .9);"
                ><replace-text-component text="{{$discription->sorce_text }}"></replace-text-component></p>
                @endif

            </div>
        </section>
    @endforeach
</div>
