@php
$gacha_categories = \App\Models\GachaCategory::userList()->get();
@endphp
@if($gacha_categories->count()>1)
    <div class="col-auto">
        <h5>{{ __('カテゴリー') }}</h5>
        <ul class="list-unstyled fs-5">
            @foreach ($gacha_categories as $gacha_category)
                <li><a class="link-secondary text-decoration-none"
                href="{{ route('gacha_category',$gacha_category->code_name) }}"
                >{{ $gacha_category->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endif


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
    {{-- </ul>
</div>
<div class="col-auto">
    <ul class="list-unstyled m-0"> --}}
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('infomation') }}">お知らせ</a></li>
        {{-- <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('timeline') }}">タイムライン</a></li> --}}
        <li class="mb-2"><a class="link-secondary text-decoration-none"
        href="{{ route('contact') }}">お問い合わせ</a></li>
        {{-- <li class="mb-2"><a class="link-secondary text-decoration-none" target="_blank"
        href="{{ route('operating_company') }}">運営会社</a></li> --}}
    </ul>
</div>
<div class="col-auto">
    <div class="form-text my-3">
        古物商営業許可<br>
        第 000010000000 号<br>
        ○○県公安委員会<br>
        (株)会社名<br>
    </div>
</div>
