<!--トップー-->
<section class="p- pb-md-5">
    <div class="container mx-auto overflow-auto px-3" style="max-widthh:1200px;">

        <div class="overflow-hidden" style="border-radius:1rem;">

            @include('gacha.common.top_image')

        </div>

    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:900px;">

    @foreach ($gacha->discriptions as $discription)



        @include('gacha.show.section')


    @endforeach
</div>
