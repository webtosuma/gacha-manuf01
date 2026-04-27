<!--table-->
<div id="discription-table" class="border rounded oberflow-hidden mb-4">
    <table class="table border-white text-dark m-0 rounded overflow-hidden" style="font-size:12px">
        <tbody>
            <tr>
                <th class="bg-body text- p-" style="width:7rem;">お届け時期</th>
                <td class="p-">{{ $gacha_title['estimated_shipping_label'] }}</td>
            </tr>
            <tr>
                <th class="bg-body text- p-">販売終了</th>
                <td class="p-">{{ $gacha_title->sales_end_at
                ?  $gacha_title->estimated_shipping_at_text
                : '未定' }}</td>
            </tr>

            @if( $gacha_title->set_contents_text )
                <tr>
                    <th class="bg-body text- p-">セット内容</th>
                    <td class="p-">

                        <replace-text-component text="{{ $gacha_title->set_contents_text }}" ></replace-text-component>

                    </td>
                </tr>
            @endif
            @if( $gacha_title->prize_size )
                <tr>
                    <th class="bg-body text- p-">商品サイズ</th>
                    <td class="p-">{{ $gacha_title->prize_size }}</td>
                </tr>
            @endif
            @if( $gacha_title->prize_materials )
                <tr>
                    <th class="bg-body text- p-">商品素材</th>
                    <td class="p-">{{ $gacha_title->prize_materials }}</td>
                </tr>
            @endif
            @if( $gacha_title->age_range )
                <tr>
                    <th class="bg-body text- p-">対象年齢</th>
                    <td class="p-">{{ $gacha_title->age_range }}</td>
                </tr>
            @endif
            @if( $gacha_title->copy_right )
                <tr>
                    <th class="bg-body text- p-">コピーライト</th>
                    <td class="p-">{{ $gacha_title->copy_right }}</td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
