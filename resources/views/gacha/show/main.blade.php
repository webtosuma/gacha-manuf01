<!--トップー-->
<section class="p- pb-md-5">
    <div class="container mx-auto px-3" style="max-width:900px;">

        <div class="overflow-hidden rounded-4">

            @include('gacha.common.top_image')

        </div>


        <div class="d-flex justify-content-end mt-3">
            {{-- <div class="fw-bold text-center mb-2">このガチャをシェアする</div> --}}
            @php
            $sns_url  = request()->url();
            $sns_text = $gacha->name;
            $sns_size = '2rem';
            @endphp
            @include('includes.sns_btn')
        </div>

    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:600px;">

    @foreach ($gacha->discriptions as $discription)


        @include('gacha.show.section')


    @endforeach
</div>
