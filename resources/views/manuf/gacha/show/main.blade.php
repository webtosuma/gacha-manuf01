<div class="mx-auto" >
    <div class="row justify-content-center g-3 mx-0">



        <div class="col-12 col-lg-5">


            <u-store-item-image
            ration        ="{{config('app.gacha_card_ratio')}}"
            image_path    ="{{$gacha->image_path}}"
            new_label_path=""
            is_sold_out   =""
            is_prize      =""
            ></u-store-item-image>


            <div class="row g-2 my-3">
                @php $examples= [
                    $gacha->image_path,
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095777/melotabi_mejirushi_1.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095784/melotabi_mejirushi_2.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095791/melotabi_mejirushi_3.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095807/melotabi_mejirushi_4.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095814/melotabi_mejirushi_5.jpg',
                ]; @endphp
                @foreach ( $examples as $url)
                    <div class="col-2">
                        <div class="ratio ratio-1x1 border rounded bg-white"
                        style="
                         background: no-repeat center center / contain;
                         background-image: url({{$url}});
                        " ></div>
                    </div>
                @endforeach
            </div>



            <div class="d-lg-none">
                <!--タイトル・説明-->
                @include('manuf.gacha.show.title_discription')
            </div>


            <!--prize table-->
            <div id="priz-table" class="border rounded oberflow-hidden mb-3">
                @php $examples= [
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095777/melotabi_mejirushi_1.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095784/melotabi_mejirushi_2.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095791/melotabi_mejirushi_3.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095807/melotabi_mejirushi_4.jpg',
                    'https://parks2.bandainamco-am.co.jp/client_info/BNAM_LBC_EC/itemimage/4582770095814/melotabi_mejirushi_5.jpg',
                ]; @endphp
                <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
                    <tbody>
                        @foreach ($examples as $url)
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
                            <td style="width:5rem;">
                                <div class="text-center" style="font-size:11px;">当選率</div>
                                <div class="fs-4 text-end">25.0%</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!--discription table-->
            <div id="discription-table" class="border rounded oberflow-hidden mb-3">

                <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
                    <tbody>
                        <tr>
                            <th class="bg-body text- p-" style="width:9rem;">お届け時期</th>
                            <td class="p-">xxxxxx</td>
                        </tr>
                        <tr>
                            <th class="bg-body text- p-">販売終了</th>
                            <td class="p-">xxxxxx</td>
                        </tr>
                        <tr>
                            <th class="bg-body text- p-">セット内容</th>
                            <td class="p-">xxxxxx</td>
                        </tr>
                        <tr>
                            <th class="bg-body text- p-">商品サイズ</th>
                            <td class="p-">xxxxxx</td>
                        </tr>
                        <tr>
                            <th class="bg-body text- p-">商品素材</th>
                            <td class="p-">xxxxxx</td>
                        </tr>
                        <tr>
                            <th class="bg-body text- p-">種類数</th>
                            <td class="p-">xxxxxx</td>
                        </tr>
                        <tr>
                            <th class="bg-body text- p-">対手年齢</th>
                            <td class="p-">xxxxxx</td>
                        </tr>
                        <tr>
                            <th class="bg-body text- p-">コピーライト</th>
                            <td class="p-">xxxxxx</td>
                        </tr>

                    </tbody>
                </table>
            </div>


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
        </div>


        <div class="d-none d-lg-block col-12 col-lg-5 px-">
            <div class="position-sticky" style="top: 0rem; ">


                <!--タイトル・説明-->
                @include('manuf.gacha.show.title_discription')



            </div>
        </div>


    </div>
</div>
