<!--マシーン詳細　offcanvace-->
@foreach ($machines as $machine)

    <div class="offcanvas offcanvas-start  "
    tabindex="-1" id="oc_prizes{{ $machine->id }}" aria-labelledby="oc_prizes{{ $machine->id }}Label"
    style="max-width:90vw;"
    >
        <div class="offcanvas-header">

            <h5 class="
            offcanvas-title
            border-start border-info border-5 ps-1 fs-4 fw-bold
            "
            id="oc_prizes{{ $machine->id }}Label"
            >ガチャマシン09{{ $machine->id }}</h5>


            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">

            <div class="row g-1 pt-2 text-center mb-3" style="font-size:14px;">

                <!--在庫-->
                <div class="col">
                    <div class=" bg-dark border text-white px-2 rounded w-100 ">
                        <span class="">残り</span>
                        {{number_format($machine->remaining_count)}}
                    </div>
                </div>

                <!--待機中-->
                <div class="col">
                    <div class=" bg-warning px-2 rounded w-100 ">
                        <span class="">待機中</span>
                        {{number_format($machine->waiting_count)}}
                    </div>
                </div>
            </div>


            <!-- prize -->
            <div  class="border rounded oberflow-hidden mb-3">
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
                            <td style="width:5rem;">
                                <div class="text-center" style="font-size:11px;">当選率</div>
                                <div class="fs-5 text-center">25.0%</div>
                            </td>
                            <td style="width:5rem;">
                                <div class="text-center" style="font-size:11px;">残数</div>
                                <div class="fs-5 text-center">3個</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="mt-3">
                <button class="btn btn-sm btn-light border rounded-pill w-100"
                data-bs-dismiss="offcanvas" aria-label="Close"
                >閉じる</button>
            </div>
        </div>
    </div>

@endforeach
