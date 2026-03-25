<!--紹介画像-->
<div class="row g-0 my-3">
    @php $examples= [
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/PRE_4570118183972/melotabi_mejirushi_page2_900.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095777/melotabi_mejirushi_1.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095784/melotabi_mejirushi_2.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095791/melotabi_mejirushi_3.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095807/melotabi_mejirushi_4.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095814/melotabi_mejirushi_5.jpg',
    ]; @endphp
    @foreach ( $examples as $url)
        <div class="col-12">
            <img src="{{$url}}" alt="" class="w-100">
        </div>
    @endforeach
</div>

