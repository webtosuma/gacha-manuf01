@php
$gacha_categories = \App\Models\GachaCategory::userList()->get();
@endphp
@if($gacha_categories->count()>1)


    <div class="col-auto text- ">
        <!-- カテゴリー -->
        <button  class="list-group-item border p-2 px-3 mb-3
        fs-6 fw-bold w-100 text-start
        dropdown-toggle"
        data-bs-toggle="collapse" href="#collapseGachaCategories"
        role="button" aria-expanded="false" aria-controls="collapseGachaCategories"
        type="button" >
            {{ __('カテゴリー') }}
        </button>
        <div class="collapse ps-3 "
        id="collapseGachaCategories">

            <ul class="list-unstyled fs-5 d-inline-block">
                @foreach ($gacha_categories as $gacha_category)
                    <li><a class="text-white text-decoration-none"
                    href="{{ route('gacha_category',$gacha_category->code_name) }}">
                        <div class="d-flex align-items-center gap-2 mb-2">


                            @if( config('gacha.category_image.footer', true ) )
                                <!--カテゴリーimg-->
                                <div class="position-relative overflow-hidden h-100 rounded-pill" style="width:3rem;">
                                    <ratio-image-component
                                    url="{{ $gacha_category->top_prize_image_path }}" style_class="ratio ratio-1x1 bg-body w-100"
                                    ></ratio-image-component>

                                    <div class=" d-flex align-items-center justify-content-center
                                    position-absolute top-0 start-0 w-100 h-100 fw-bold
                                    @if(isset($category_code) && $category_code==$gacha_category->code_name) bg-primary text-white @else text-white @endif"
                                    style="background:rgba(0, 0, 0, .8); opacity:.7;"
                                    ></div>
                                </div>
                            @endif


                            <!--カテゴリー name-->
                            <span>{{ $gacha_category->name }}</span>


                            @if( config('gacha.category_image.footer', true ) )
                                <!--カテゴリーimg-->
                                <div class="me-3"></div>
                            @endif

                        </div>
                    </a></li>
                @endforeach
            </ul>

        </div>

    </div>

@endif


<div class="col-auto">
    <!-- カテゴリー -->
    <button  class="list-group-item border p-2 px-3 mb-3
    fs-6 fw-bold w-100 text-start
    dropdown-toggle"
    data-bs-toggle="collapse" href="#collapseFooterMenu"
    role="button" aria-expanded="false" aria-controls="collapseFooterMenu"
    type="button" >
        {{ config('app.name').__('について')}}
    </button>
    <div class="collapse ps-3 "
    id="collapseFooterMenu">

        <ul class="list-unstyled m-0 gap-3">


            @if( \App\Models\Text::getGuide() )
                <li class="mb-2"><a class="text-white text-decoration-none"
                href="{{ route('guide') }}">利用ガイド</a></li>
            @endif


            <li class="mb-2"><a class="text-white text-decoration-none"
            href="{{ route('trems') }}">利用規約</a></li>

            <li class="mb-2"><a class="text-white text-decoration-none"
            href="{{ route('privacy_policy') }}">プライバシーポリシー</a></li>

            <li class="mb-2"><a class="text-white text-decoration-none"
            href="{{ route('about_pwa') }}">PWAについて</a></li>

            @php
            $infomations_count =
            \App\Http\Controllers\InfomationController::GetInfomationsQuery()
            ->whereNotIn( 'type', ['ec'] )
            ->limit(3)->count();
            @endphp
            @if( $infomations_count>0 )
                <li class="mb-2"><a class="text-white text-decoration-none"
                href="{{ route('infomation') }}">お知らせ</a></li>
            @endif

            <li class="mb-2"><a class="text-white text-decoration-none"
            href="{{ route('contact') }}">お問い合わせ</a></li>

            @if( config('app.company_url') )
                <li class="mb-2"><a class="text-white text-decoration-none"
                href="{{ config('app.company_url') }}">運営会社</a></li>
            @endif

        </ul>

    </div>

</div>

