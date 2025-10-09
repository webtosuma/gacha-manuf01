<!--トップー-->
<section class="overflow-hidden px-1">
    <div class="containerr mx-auto px-0">

        <div class="overflow-hidden rounded-4"
        data-aos="zoom-out"
        >

            @include('gacha.common.top_image')

        </div>

        @if( env('SHARE_BTNS') )
            <div class="d-flex justify-content-end mt-3 d-lg-none">
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
<section class="overflow-hidden">
    <div class="row justify-content-center mx-auto" style="max-width:600px; margin-top:20vh;">

        @foreach ($gacha->discriptions as $discription)


            @include('admin.event_gacha.show.section')


        @endforeach
    </div>
</section>
