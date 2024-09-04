<!--トップー-->
<section class="p- pb-md-5">
    <div class="container mx-auto px-3" style="max-width:900px;">

        <div class="overflow-hidden rounded-4">

            @include('gacha.common.top_image')

        </div>

        @if( env('SHARE_BTNS') )
            <div class="d-flex justify-content-end mt-3">
                @php
                $sns_url  = request()->url();
                $sns_text = $gacha->name;
                $sns_size = '2rem';
                @endphp
                @include('includes.sns_btn')
            </div>
        @endif

    </div>
</section>
<!--各賞-->
<div class="row justify-content-center mx-auto" style="max-width:600px;">

    @foreach ($gacha->discriptions as $discription)


        @include('gacha.show.section')


    @endforeach
</div>
