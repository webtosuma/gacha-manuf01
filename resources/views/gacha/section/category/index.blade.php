
@php $category_card = false ; @endphp


<div class="d-none d-lg-block py-2">


    @if($category_card)
        <div class="px-0 px-lg-3 overflow-auto">
            <nav class="nav gap-1 flex-nowrap mx-auto" style="width:{{($categories->count()+1)*4.8}}rem;">

                @include('gacha.section.category.card')

            </nav>
        </div>
    @else
        <div class="container px-0 px-lg-3 overflow-auto">
            <nav class="nav gap-1 flex-nowrap " >

                @include('gacha.section.category.text')

            </nav>
        </div>
    @endif

</div>
<!-- ボトムメニュー -->
<div class="d-lg-none">
    <div class="position-fixed bottom-0 end-0 w-100 py-2 text-white "
    style="z-index:50; background:rgb(255, 255, 255, 1;">

        @if($category_card)
            <div class="px-0 px-lg-3 overflow-auto">
                <nav class="nav gap-1 flex-nowrap mx-auto px-0" style="width:{{($categories->count()+1)*4.8}}rem;">

                    @include('gacha.section.category.card')

                </nav>
            </div>
        @else
            <div class="px-0 px-lg-3 overflow-auto">
                <nav class="nav gap-1 flex-nowrap mx-auto" style="width:{{($categories->count()+1)*6}}rem;">

                    @include('gacha.section.category.text')

                </nav>
            </div>
        @endif

    </div>
</div>
