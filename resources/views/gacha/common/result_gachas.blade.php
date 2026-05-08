<h5 class="fw-bold text-center mb-3">おすすめガチャ</h5>

<div class="my-3">

    <u-gacha-recommend-list
    token=        "{{ csrf_token() }}"
    category_code="{{ $category_code }}"
    r_api_gacha_list="{{ route('gacha.api.list') }}"
    ></u-gacha-recommend-list>

</div>


<div class="text-center">
    <a href="{{route('gacha_category',$category_code)}}" class="btn btn-light shadow rounded-pill fw- col-6"
    >もっと見る</a>
</div>
