<!--title_prize_table-->
<h5 class="fw-bold ps-1
border-start border-info border-5
" >商品ラインナップ</h5>

@if ( $gacha_title->title_prizes->count()>0 )


    <div class="border rounded oberflow-hidden mb-4">
        <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:12px">
            <tbody>
                @foreach ( $gacha_title->published_title_prizes as $title_prize )
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


@elseif( Auth::user()->admin )


    <!--サイト管理者専用-->
    <div class="d-md-flex justify-content-between p-3">
        <div class="fs-5 text-danger mb-2">*登録されていません</div>

        <a href="{{ route('admin.gacha_title.title_prize.create',$gacha_title) }}"
        class="btn btn-primary text-white  rounded-pill shadow"
        ><i class="bi bi-plus-lg me-2"></i>新規登録</a>
    </div>


@endif
