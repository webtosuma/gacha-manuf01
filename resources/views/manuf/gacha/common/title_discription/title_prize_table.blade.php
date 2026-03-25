<!--title_prize_table-->
<h5 class="fw-bold ps-1
border-start border-info border-5
" >商品ラインナップ</h5>

<div id="title_prize_table" class="border rounded oberflow-hidden mb-4">
    @php $examples= [
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095777/melotabi_mejirushi_1.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095784/melotabi_mejirushi_2.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095791/melotabi_mejirushi_3.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095807/melotabi_mejirushi_4.jpg',
        'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095814/melotabi_mejirushi_5.jpg',
    ]; @endphp
    <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
        <tbody>
            @foreach ($examples as $key => $url)
            <tr>
                <th class="" style="width:4rem;">
                    <div class="ratio ratio-1x1 border rounded bg-white"
                    style="
                    background: no-repeat center center / contain;
                    background-image: url({{$url}});
                    " ></div>
                </th>
                <td class="">
                    <div class="fw-bold">商品名syouhinmei</div>
                    xxxxxx
                </td>
                {{-- <td style="width:5rem;">
                    <div class="text-center" style="font-size:11px;">当選率</div>
                    <div class="fs-4 text-end">25.0%</div>
                </td> --}}
                <td style="width:3rem;">
                    <u-prize-discription
                    id         ="{{$key}}"
                    name       ="商品名syouhinmei"
                    image_path ="{{$url}}"
                    discription="xxxx xxxx xxxx xxxx"
                    size       ="2.4rem"
                    src_icon   ="{{asset('storage/site/image/prize_discription.png')}}"
                    no_btn     =""
                    bg_dark    =""
                    ></u-prize-discription>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
