@php
$categories = \App\Models\GachaCategory::ecUserList()->get();
@endphp
@if($categories->count()>1)
    <div class="col-auto">
        <h5 class="fs-6 fw-bold">{{ __('カテゴリーから探す') }}</h5>
        <ul class="list-unstyled fs-5">
            @foreach ($categories as $category)
                <li><a class="link-secondary text-decoration-none"
                href="{{route('store.search',['category_code'=>$category->code_name])}}">
                    <div class="d-flex gap-3 align-items-center mb-2">

                        <div style="width:2.6rem;">
                            <ratio-image-component
                            url="{{ $category->top_store_item_image_path }}"
                            style_class="ratio ratio-1x1 bg-body border rounded-pill"
                            ></ratio-image-component>
                        </div>


                        <span>{{ $category->name }}</span>


                    </div>
                </a></li>
            @endforeach
        </ul>
    </div>
@endif



<div class="col-auto">
    <ul class="list-unstyled m-0">
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('store.infomation') }}">お知らせ</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('contact') }}">お問い合わせ</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none" target="_blank"
        href="{{ route('operating_company') }}">運営会社</a></li>
    </ul>
</div>
<div class="col-auto">
    <ul class="list-unstyled m-0 gap-3">
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('guide') }}">利用ガイド</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none" target="_blank"
        href="{{ route('trems') }}">利用規約</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none" target="_blank"
        href="{{ route('privacy_policy') }}">プライバシーポリシー</a></li>
        <li class="mb-2"><a class="link-secondary text-decoration-none" target="_blank"
        href="{{ route('tradelaw') }}">特定商取引法に基づく表記</a></li>
    </ul>
</div>
<div class="col-auto">
    {{-- <div class="form-text">
        古物商営業許可<br>
        第 000010000000 号<br>
        ○○県公安委員会<br>
        (株)会社名<br>
    </div> --}}
</div>
