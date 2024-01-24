<section class="card card-body bg-white mb-5 overflow-auto">
    <div class="mb-3">ポイント購入顧客情報</div>
    <table class="table bg-white ">
        <!--ヘッド-->
        <thead>
            <tr class="bg-white">
                <th scope="col" style="width:4rem;">
                    <a href="{{route('admin.point_history',['month_text'=>$this_month->format('Y-m-01'),'table'=>'customer_report'])}}"
                    >アカウント名</a>
                </th>
                <th scope="col" style="width:4rem;">
                    <a href="{{route('admin.point_history',['month_text'=>$this_month->format('Y-m-01'),'table'=>'customer_report','sort_by_key'=>'point_price'])}}"
                    >購入金額</a>
                </th>
                <th scope="col" style="width:4rem;">
                    <a href="{{route('admin.point_history',['month_text'=>$this_month->format('Y-m-01'),'table'=>'customer_report','sort_by_key'=>'count'])}}"
                    >購入回数</a>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($visiters as $visiter)
                <tr>
                    <td>
                        <a href="{{route('admin.user.show',$visiter->id)}}">{{$visiter->name}}</a>
                    </td>
                    <td>
                        <number-comma-component number="{{ $visiter->point_price }}"></number-comma-component>
                    </td>
                    <td>
                        <number-comma-component number="{{ $visiter->count }}"></number-comma-component>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="3" class="text-center text-secondary border-0 py-5">
                        *ポイント購入した顧客情報はありません
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>
