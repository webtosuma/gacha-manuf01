<!--トップー-->
<section class="p- pb-md-5">
    <div class="mx-auto overflow-auto px-0" style="max-width:600px;">


        {{-- <div class="d-none d-md-block overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-16x9 w-100"
            ></ratio-image-component>
        </div>
        <div class="d-md-none  overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-1x1 w-100"
            ></ratio-image-component>
        </div> --}}
        <div class="overflow-hidden" style="border-radius:1rem;">
            <ratio-image-component
            url="{{ $gacha->image_path }}" style_class="ratio ratio-4x3 w-100"
            ></ratio-image-component>
        </div>


    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:600px;">

    @foreach ($gacha->discriptions as $discription)



        @include('gacha.show.section')


    @endforeach
</div>
