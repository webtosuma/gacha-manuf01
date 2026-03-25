<!--Plice-->
<div class="d-flex justify-content-end">
    <div class="text-center">
        <span style="font-size:16px;">１回/</span>
        <span style="font-size:16px;">税込</span>
        <div class="d-inline-block" style="line-height:18px;">
            <span class="fs-3 text-danger">¥</span>
            <span class="fs-1 text-danger"> {{number_format($gacha_title->price)}}</span>
        </div>
    </div>
</div>



<!--description_text-->
@if($gacha_title->description_text)
    <p id="discription-description_text" class="border-top bg-white p-2 my-2 form-text">

        {!! str_replace(["\r\n","\r","\n"],"<br>", e( $gacha_title->description_text ) )!!}<br>

    </p>
@endif



<!--table-->
<div id="discription-table" class="border rounded oberflow-hidden mb-4">
    <table class="table border-white text-dark m-0 rounded overflow-hidden" style="font-size:12px">
        <tbody>
            <tr>
                <th class="bg-body text- p-" style="width:7rem;">お届け時期</th>
                <td class="p-">xxxxxx</td>
            </tr>
            <tr>
                <th class="bg-body text- p-">販売終了</th>
                <td class="p-">xxxxxx</td>
            </tr>
            <tr>
                <th class="bg-body text- p-">セット内容</th>
                <td class="p-">{{ $gacha_title->set_contents_text }}</td>
            </tr>
            <tr>
                <th class="bg-body text- p-">商品サイズ</th>
                <td class="p-">{{ $gacha_title->prize_size }}</td>
            </tr>
            <tr>
                <th class="bg-body text- p-">商品素材</th>
                <td class="p-">{{ $gacha_title->prize_materials }}</td>
            </tr>
            {{-- <tr>
                <th class="bg-body text- p-">種類数</th>
                <td class="p-">xxxxxx</td>
            </tr> --}}
            <tr>
                <th class="bg-body text- p-">対象年齢</th>
                <td class="p-">{{ $gacha_title->age_range }}</td>
            </tr>
            <tr>
                <th class="bg-body text- p-">コピーライト</th>
                <td class="p-">{{ $gacha_title->copy_right }}</td>
            </tr>

        </tbody>
    </table>
</div>
