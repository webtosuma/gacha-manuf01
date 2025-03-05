@forelse ($point_histories as $point_history)
    <li class="list-group-item bg-white py-3">
        <div class="row align-items-center ">
            <div class="col">


                @php
                $user_id    = $point_history->user ? $point_history->user->id  : '';
                $user_name  = $point_history->user ? $point_history->user->name  : '退会済み';
                $user_email = $point_history->user ? $point_history->user->email : '---';
                @endphp


                @switch( $point_history->reason_id)
                    @case(11)
                        {{-- ポイント購入 --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="">
                                <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i').'履歴ID:'.$point_history->id}}</div>
                                @if(!$user)
                                    <a href="{{route('admin.user.point_history',$user_id)}}"
                                    >{{ 'ID:'.$user_id.' '.$user_name.' '.$user_email}}</a>
                                @endif
                                <div class="fw-bold"><span class="text-primary">●</span>ポイント購入</div>
                                <div class="">購入金額：¥
                                    <number-comma-component number="{{ $point_history->price }}"></number-comma-component>
                                </div>
                            </div>

                            <div class="col-auto">
                                {{'+'}}
                                <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                                {{'pt'}}
                            </div>
                        </div>
                        @break

                    @case(12)
                        {{-- 商品のポイント交換 --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="">
                                <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i').'履歴ID:'.$point_history->id}}</div>
                                @if(!$user)
                                    <a href="{{route('admin.user.point_history',$user_id)}}"
                                    >{{ 'ID:'.$user_id.' '.$user_name.' '.$user_email}}</a>
                                @endif
                                <div class="fw-bold"><span class="text-primary">●</span>商品のポイント交換</div>
                            </div>

                            <div class="col-auto">
                                {{'+'}}
                                <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                                {{'pt'}}
                            </div>
                        </div>

                        @if(isset($confirm))
                            <div class="alert alert-warning border-0">
                                ＊履歴削除後に、「ポイントと交換した商品」はユーザーの取得商品として返却されます。
                            </div>
                        @endif
                        @break
                    @case(21)
                        {{-- ガチャる --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="">
                                <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i').'履歴ID:'.$point_history->id}}</div>
                                @if(!$user)
                                    <a href="{{route('admin.user.point_history',$user_id)}}"
                                    >{{ 'ID:'.$user_id.' '.$user_name.' '.$user_email}}</a>
                                @endif
                                <div class="fw-bold">
                                    <span class="text-danger">●</span>ガチャ
                                </div>
                                <div class="">
                                    {{-- <a href="{{route('gacha.result_history',$point_history->user_gacha_history->key)}}"  target="_blank"> --}}
                                    @php
                                    $params=[
                                        'category_code'     => $point_history->user_gacha_history->gacha->category->code_name ,
                                        'user_gacha_history'=> $point_history->user_gacha_history->id
                                    ];
                                    @endphp
                                    <a href="{{route('admin.gacha.result', $params)}}">

                                        <span>{{ '['.$point_history->user_gacha_history->gacha->name.']' }}</span>
                                        <span>{{ '（'.$point_history->user_gacha_history->play_count.'回）' }}</span>

                                    </a>
                                </div>
                            </div>

                            <div class="col-auto text-danger">
                                <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                                {{'pt'}}
                            </div>
                        </div>

                        @if(isset($confirm))
                            <div class="alert alert-warning border-0">
                                ＊このガチャで取得した「商品」は、履歴削除後にユーザーの取得商品から削除されます。<br>
                                ＊このガチャで取得した商品の「ポイント交換記録」は、この履歴削除後と共に削除されます。<br>
                                ＊このガチャで取得した商品の「発送受付(発送待ち)」は、この履歴削除後と共に削除されます。<br>
                                ＊このガチャで取得した商品の「発送受付(発送済み)」は、削除されません。<br>
                            </div>
                        @endif

                        @break

                    @case(22)
                        {{-- 商品発送 --}}
                        <div class="d-flex align-items-center justify-content-between">
                            @php
                            $text_color = $point_history->value >= 0 ? 'text-secondary' : 'text-danger';
                            $sine = $point_history->value > 0 ? '+' : ( $point_history->value < 0 ? '-' : '' );

                            $href = !$point_history->user_shipped->shipment_at
                            ? route('admin.shipped.waiting.show',$point_history->user_shipped)
                            : route('admin.shipped.send.show'   ,$point_history->user_shipped);
                            @endphp
                            <div class="">
                                <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i').'履歴ID:'.$point_history->id}}</div>
                                @if(!$user)
                                    <a href="{{route('admin.user.point_history',$user_id)}}"
                                    >{{ 'ID:'.$user_id.' '.$user_name.' '.$user_email}}</a>
                                @endif
                                <div class="fw-bold">
                                    <span class="{{$text_color}}">●</span>


                                    <a href="{{$href}}">
                                        <span>商品発送</span>
                                    </a>

                                    @if ($point_history->user_shipped->shipment_at)
                                        <span class="badge bg-success">発送済み</span>
                                    @else
                                        <span class="badge bg-danger">未発送</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-auto {{$text_color}}">
                                <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                                {{'pt'}}
                            </div>

                        </div>

                        @if(isset($confirm))
                            <div class="alert alert-warning border-0">
                                @if (!$point_history->user_shipped->shipment_at)
                                    ＊履歴削除後に、「発送申請した商品」はユーザーの取得商品として返却されます。
                                @else
                                    ＊発送済みの履歴については、削除することができません。
                                @endif
                            </div>
                        @endif

                        @break

                    @default
                        {{-- その他 --}}
                        <div class="d-flex align-items-center justify-content-between">
                            @php
                            $text_color = in_array( $point_history->reason_id, [13,14, 31,32,33] ) ? 'text-warning' : 'text-primary' ;
                            $text_color = $point_history->value >= 0 ? $text_color : 'text-danger';
                            $sine = $point_history->value > 0 ? '+' : ( $point_history->value < 0 ? '-' : '' );
                            @endphp
                            <div class="">
                                <div class="form-text">{{$point_history->created_at->format('Y/m/d H:i').'履歴ID:'.$point_history->id}}</div>
                                @if(!$user)
                                    <a href="{{route('admin.user.point_history',$user_id)}}"
                                    >{{ 'ID:'.$user_id.' '.$user_name.' '.$user_email}}</a>
                                @endif
                                <div class="fw-bold"><span class="{{$text_color}}">●</span>{{ $point_history->reason }}</div>
                            </div>

                            <div class="col-auto {{$point_history->value >= 0 ?'':'text-danger'}}">
                                {{$point_history->value >= 0 ?'+':''}}
                                <number-comma-component number="{{ $point_history->value }}"></number-comma-component>
                                {{'pt'}}
                            </div>
                        </div>
                @endswitch


            </div>
            <div class="col-auto {{isset($confirm)?'d-none':''}}">


                <input
                name="point_history_ids[]"
                value="{{$point_history->id}}"
                class="form-check-input" type="checkbox">



            </div>
        </div>
    </li>
@empty
    <li class="list-group-item bg-white py-3 fw-bold">ご利用はありません。</li>
@endforelse
