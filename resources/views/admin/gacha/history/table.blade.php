        <!-- ページネーション -->
        @if( $user_gacha_histories->count() )
            <div class="d-flex justify-content-between mt-3">
                <div class="col">
                    {{ $user_gacha_histories->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        @endif

        <!-- 一覧 -->
        <ul class="list-group list-group-flush">
            {{-- @include('admin.user.point_history._types') --}}



            <table class="table bg-white ">
                <!--ヘッド-->
                <thead>
                    <tr class="bg-white">
                        <th scope="col">
                            ガチャ
                        </th>
                        <th scope="col"  class="text-">
                            ユーザー
                        </th>
                        <th scope="col"  class="text-">
                            ポイント
                        </th>
                        <th scope="col"  class="text-">
                            日時
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user_gacha_histories as $user_gacha_history)
                        @php
                        $user_id    = $user_gacha_history->user ? $user_gacha_history->user->id  : 0;
                        $user_name  = $user_gacha_history->user ? $user_gacha_history->user->name  : '退会済み';
                        @endphp


                        <tr>
                            <td>
                                @php
                                  $params=[
                                    'category_code'=>$user_gacha_history->gacha->category->code_name ,
                                    'user_gacha_history'=>$user_gacha_history->id
                                ];
                                @endphp
                                <a href="{{route('admin.gacha.result', $params)}}">

                                    <span>{{ '['.$user_gacha_history->gacha->name.']' }}</span>
                                    <span>{{ '（'.$user_gacha_history->play_count.'回）' }}</span>

                                </a>
                            </td>
                            <td class="">
                                @if(!$user)
                                    <a href="{{route('admin.user.point_history',$user_id)}}">{{ $user_name}}</a>
                                @else
                                    *退会済み
                                @endif
                            </td>
                            <td class="text-danger">
                                <number-comma-component number="{{ $user_gacha_history->point_history->value }}"
                                ></number-comma-component>{{'pt'}}
                            </td>
                            <td>
                                {{$user_gacha_history->created_at->format('Y/m/d H:i').'履歴ID:'.$user_gacha_history->id}}
                            </td>
                        </tr>

                    @empty
                    @endforelse
                </tbody>
            </table>
        </ul>

        <!-- ページネーション -->
        @if( $user_gacha_histories->count() )
            <div class="d-flex justify-content-between mt-3">
                <div class="col">
                    {{ $user_gacha_histories->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        @endif
