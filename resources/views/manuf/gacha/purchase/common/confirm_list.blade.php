<ul class="list-group bg-white">

    <!-- お届け先 -->
    <li class="list-group-item p-3">

        <div class="d-flex justify-content-between ">

            <h5 class="fw-bold">お届け先</h5>

            {{-- @if(
                Auth::user()->id === $user_address->user_id
                && isset($user_shipped)
                && $user_shipped->state_id==11//未発送
            )
                <a href="{{ route('settings.user_address.edit',$user_address ) }}"
                class="">お届け先住所の変更</a>
            @endif --}}

        </div>


        <input type="hidden" name="user_address_id" value="{{ $user_address->id }}">

        <div class="p-3 fw-bold">

            <!--発送住所の変更-->
            {{-- @if( isset($user_shipped) && $user_shipped->update_user_address_label  )
                <div class="text-danger mb-3">{{ $user_shipped->update_user_address_label }}</div>
            @endif --}}


            <!--お届け先-->
            @include('shipped.common.user_address')


        </div>
    </li>


    <!-- 購入ガチャタイトル -->
    <li class="list-group-item p-3">
        <h5 class="fw-bold">購入ガチャタイトル</h5>

        <div class="row">
            <div class="col">

                <div class="row g-3">
                    <div class="col-4 col-lg-2 text-center">
                
                        <ratio-image-component
                        url="{{$gacha_title->image_samune_path}}"
                        style_class="{{$gacha_title->ratio.' ratio bg-body'}}"
                        bg_size="contain"
                        ></ratio-image-component>
                
                
                    </div>
                    <div class="col ">
                
                
                        <div class="">
                
                            <!--discription head-->
                            @include('manuf.gacha.common.title_discription.title_name')
                
                
                            <h6 class="fw-bold m-0">ガチャマシーン</h6>
                            <div class="card p-1 mb-4 ">{{$machine->name}}</div>
                                
                
                        </div>
                
                
                
                    </div>
                </div>
                
            </div>
            <div class="col-3 text-end fw-bold">
                <div class="">
                    1回/税込¥{{number_format($gacha_title_price)}}
                </div>
                <div class="">
                    {{number_format($play_count)}}点
                </div>
                <div class="mt-4">
                    商品小計
                    <span class="fs-3">¥{{number_format( $sub_total_fee )}}</span>
                </div>
            </div>
        </div>
    </li>


    <!-- 発送料金 -->
    <li class="list-group-item p-3">
        <h5 class="fw-bold">発送料金</h5>

        <div class="text-end fw-bold">
            <span class="fs-6">¥{{number_format( $shipped_fee )}}</span>
        </div>
    </li>


</ul>

