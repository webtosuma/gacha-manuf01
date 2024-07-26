<section class="card card-body bg-white mb-5 overflow-auto">
    <div class="mb-3">ポイント購入顧客情報</div>
    <table class="table bg-white ">
        <!--ヘッド-->
        <thead>
            <tr class="bg-white">
                <th scope="col">
                    <a href="{{route('admin.point_history',['month_text'=>$this_month->format('Y-m-01'),'table'=>'customer_report'])}}"
                    >アカウント名</a>
                </th>

                @if( env('NEW_TICKET_SISTEM',false) )
                    <th scope="col" class="text-center">会員ランク</th>
                @endif

                <th scope="col">
                    <a href="{{route('admin.point_history',['month_text'=>$this_month->format('Y-m-01'),'table'=>'customer_report','sort_by_key'=>'point_price'])}}"
                    >購入金額</a>
                </th>
                <th scope="col">
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

                    @if( env('NEW_TICKET_SISTEM',false) )
                        <td class="text-center"><a href="{{route('admin.user.user_rank_history',$visiter)}}"
                        class="btn btn-link text-decoration-none">
                            @if( $visiter->now_rank )
                                <div style="width:50px;">
                                    <ratio-image-component
                                    style_class="ratio ratio-16x9 overflow-hidden position-relative shiny"
                                    url="{{ $visiter->now_rank->image_path }}"
                                    ></ratio-image-component>
                                </div>
                                <div class="form-text" style="font-size:8px;">{{ $visiter->now_rank->label }}</div>
                            @else
                                <span class="text-danger">*未更新</span>
                            @endif
                        </a></td>
                    @endif

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
