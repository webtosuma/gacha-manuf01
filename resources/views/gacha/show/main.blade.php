<!--トップー-->
<section class="p- pb-md-5">
    <div class="container mx-auto px-3" style="max-widthh:1200px;">

        {{-- <div class="position-relative"> --}}
            {{-- @include('gacha.common.type_lable') --}}

            <div class="overflow-hidden rounded-4">

                @include('gacha.common.top_image')

            </div>


        </div>
    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:900px;">

    @foreach ($gacha->discriptions as $discription)



        @include('gacha.show.section')


    @endforeach
</div>
