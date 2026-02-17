@php
$gacha_categories = \App\Models\GachaCategory::userList()->get();
@endphp
@if($gacha_categories->count()>1)
    <div class="col-auto">
        <h5>{{ __('カテゴリー') }}</h5>
        <ul class="list-unstyled fs-5">
            @foreach ($gacha_categories as $gacha_category)
                <li><a class="link-secondary text-decoration-none"
                href="{{ route('gacha_category',$gacha_category->code_name) }}">
                    <div class="d-flex align-items-center gap-2 mb-2">


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


                        <span>{{ $gacha_category->name }}</span>


                    </div>
                </a></li>
            @endforeach
        </ul>
    </div>
@endif

<div class="col-auto">
    <ul class="list-unstyled m-0 gap-3">
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('guide') }}">利用ガイド</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('trems') }}">利用規約</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('privacy_policy') }}">プライバシーポリシー</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('tradelaw') }}">特定商取引法に基づく表記</a></li>
    </ul>
</div>
<div class="col-auto">
    <ul class="list-unstyled m-0">
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('infomation') }}">お知らせ</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('timeline') }}">タイムライン</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('contact') }}">お問い合わせ</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('operating_company') }}">運営会社</a></li>
    </ul>
</div>
