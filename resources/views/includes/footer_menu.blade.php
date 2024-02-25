<div class="col">
    <h3>{{ __('カテゴリー') }}</h3>
    @php
    $gacha_categories = \App\Models\GachaCategory::where('is_published',1)
    ->orderBy('created_at')->get();
    @endphp
    <ul class="list-unstyled fs-5">
        @foreach ($gacha_categories as $gacha_category)
            <li><a class="link-secondary text-decoration-none"
            href="{{ route('gacha_category',$gacha_category->code_name) }}"
            >{{ $gacha_category->name }}</a></li>
        @endforeach
    </ul>
</div>
<div class="col">
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
<div class="col">
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
