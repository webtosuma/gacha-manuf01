<!-- PCカテゴリー(gacha.category_image.pc) -->
<div class="d-none d-lg-block py-2">

    @if( config('gacha.category_image.pc', true ) )
        <div class="container my-3">
            <h5 class="fw-bold">カテゴリーから選ぶ</h5>
            <div class="row gy-3">
                @include('manuf.gacha.section.category.card_pc')
            </div>
        </div>
    @else
        <div class="container px-0 px-lg-3 overflow-auto">
            <nav class="nav gap-1 flex-nowrap " >

                @include('manuf.gacha.section.category.text')

            </nav>
        </div>
    @endif

</div>
<!-- bottom mobile カテゴリー(gacha.category_image.mobile) -->
<div class="d-lg-none">
    <div class="position-fixed bottom-0 end-0 w-100 py-2 text-dark "
    style="z-index:50; background:rgb(255, 255, 255, 1;">

        <h6 class="fw-bold px-3">カテゴリーから選ぶ</h6>

        @if( config('gacha.category_image.mobile', true ) )
            <div class="px-0 px-lg-3 overflow-auto">
                <nav class="nav gap-1 flex-nowrap mx-auto px-0" style="width:{{($categories->count()+1)*4.8}}rem;">

                    @include('manuf.gacha.section.category.card')

                </nav>
            </div>
        @else
            <div class="px-0 px-lg-3 overflow-auto">
                <nav class="nav gap-1 flex-nowrap mx-auto " style="width:{{($categories->count()+1)*9}}rem;">

                    @include('manuf.gacha.section.category.text')

                </nav>
            </div>
        @endif

    </div>
</div>
