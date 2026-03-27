<!--title_prize_table-->
<h5 class="fw-bold ps-1
border-start border-info border-5
" >商品ラインナップ</h5>

<div id="title_prize_table" class="border rounded oberflow-hidden mb-4">
    <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
        <tbody>
            @foreach ( $gacha_title->title_prizes as $title_prize )
            <tr>
                <th class="" style="width:4rem;">
                    <div class="ratio ratio-1x1 border rounded bg-white"
                    style="
                    background: no-repeat center center / contain;
                    background-image: url({{$title_prize->image_path}});
                    " ></div>
                </th>
                <td class="">
                    <div class="fw-bold">{{$title_prize->name}}</div>
                    {{-- xxxxxx --}}
                </td>
                <td style="width:3rem;">
                    <u-prize-discription
                    id         ="{{ $title_prize->id }}"
                    name       ="{{ $title_prize->name }}"
                    image_path ="{{ $title_prize->image_path }}"
                    discription="{{ $title_prize->discription_text }}"
                    size       ="2.4rem"
                    src_icon   ="{{ $title_prize->discription_icon_path }}"
                    no_btn     =""
                    bg_dark    =""
                    ></u-prize-discription>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
