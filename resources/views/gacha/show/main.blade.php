<!--トップー-->
<section class="p- pb-md-5">
    <div class="container mx-auto overflow-auto px-3" style="max-widthh:1200px;">

        <div class="overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3 w-100"
            ></ratio-image-component>
        </div>

    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:900px;">

    @foreach ($gacha->discriptions as $discription)



        @include('gacha.show.section')


    @endforeach
</div>
