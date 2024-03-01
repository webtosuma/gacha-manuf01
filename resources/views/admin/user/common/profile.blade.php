<div class="row p-3 border-bottom mb-5 justify-content-center gx-5">
    <div class="col-12 col-md">


        <!-- アカウント画像 -->
        <div class="mx-auto" style="width:8rem;">
            <ratio-image-component
            style_class="rounded-circle ratio ratio-1x1 border"
            url="{{$user->image_path}}"
            ></ratio-image-component>
        </div>
        <div class="my-3">
            ID：{{ $user->id }}
            <a href="{{ route('admin.user.show',$user) }}" class="ms-3">{{ $user->name }}</a>
        </div>
        <!-- 会員ランク -->
        @if( $user->now_rank )
            @php $now_rank = $user->now_rank; @endphp

            <div class="d-flex justify-content-between gap-3 mt-3">
                <div class="col-auto" style="width:160px;">
                    <div style="font-size:14px;" class="mb-2">会員ランク：</div>

                    <ratio-image-component
                    style_class="ratio ratio-16x9 rounded-3 overflow-hidden
                    position-relative shiny"
                    url="{{ $now_rank->image_path }}"
                    ></ratio-image-component>
                </div>
                <div class="col">

                    <h6 class="fw-bold mb-2 mt-4">{{$now_rank->label}}</h6>


                    <div class="progress rounded-0 mb-" style="height: 1.6rem; transform: skew(-15deg);">
                        <div class="progress-bar bg-gradient bg-danger" role="progressbar"
                        style="width: {{$now_rank->meter_warning}}%" aria-valuenow="{{$now_rank->meter_warning}}"
                        aria-valuemin="0" aria-valuemax="100"></div>

                        <div class="progress-bar bg-gradient bg-primary" role="progressbar"
                        style="width: {{$now_rank->meter_success}}%" aria-valuenow="{{$now_rank->meter_success}}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="text-end" style="font-size:14px;">pt消費数</div>
                    <div class="text- mt-2" style="font-size:11px;">『{{$now_rank->next_rank->label}}』まであと、</div>
                    <div class="text-end" style="font-size:14px;"
                    >{{ number_format($now_rank->next_rankup_ptcount-$now_rank->total_play_ptcount) }}pt</div>
                </div>
            </div>
        @endif


    </div>
    <div class="col-12 col-md-6">

        {{-- <div class="my-3">
            ID：{{ $user->id }}
            <a href="{{ route('admin.user.show',$user) }}" class="ms-3">{{ $user->name }}</a>
        </div> --}}
        <div class="my-2">
            <h6>メールアドレス</h6>
            <div class="col-md-">
                <coppy-button-component copy_word="{{$user->email}}"></coppy-button-component>
            </div>
        </div>
        <div class="my-2">
            <h6>X(旧twitter)ID</h6>
            <div class="col-md-">
                <coppy-button-component copy_word="{{$user->twitter_id}}"></coppy-button-component>
            </div>
        </div>
        <div class="my-2">
            <h6>会員登録</h6>
            <div class="border p-2 rounded">{{$user->created_at->format('Y年m月d日 H:i') }}</div>
        </div>
        <div class="my-2">
            <h6>最終アクセス</h6>
            <div class="border p-2 rounded  text-success">{{ $user->last_access_at->format('Y年m月d日 H:i') }}</div>
        </div>

        @if($user->recruiter)
        <div class="my-2">
            <h6>紹介元ユーザー</h6>
            <div class="border p-2 rounded">
                ID：{{ $user->recruiter->id }}
                <a href="{{ route('admin.user.show',$user->recruiter) }}" class="ms-3">{{ $user->recruiter->name }}</a>
            </div>
        </div>
        @endif

        <div class="my-3">
            <div class="d-flex gap-3">
                <!--ポイント付与モーダル-->
                <form action="{{ route('admin.user.add_point', $user) }}" method="post">
                    @csrf
                    @method('PATCH')

                    <delete-modal-component
                    index_key="{{'add_point'.$user->id}}"
                    icon="bi-coin" color="warning"
                    func_btn_type="submit"
                    button_text="+ポイント付与"
                    button_class="btn btn-warning btn- text-white rounded-pill form-text">
                    <div>
                            <span class="fw-bold">『{{$user->name}}』さんに</span>ポイントを付与します。
                            <div class="col-8 mx-auto">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon3">付与ポイント</span>
                                    <input class="form-control text-end"  type="number" name="value" value="1000" min="0">
                                    <span class="input-group-text" id="basic-addon3">pt</span>
                                </div>
                            </div>
                            よろしいですか？
                        </div>
                    </delete-modal-component>
                </form>


                <!--チケット付与モーダル-->
                <form action="{{ route('admin.user.add_ticket', $user) }}" method="post">
                    @csrf
                    @method('PATCH')

                    <delete-modal-component
                    index_key="{{'add_ticket'.$user->id}}"
                    icon="bi-coin" color="success"
                    func_btn_type="submit"
                    button_text="+チケット付与"
                    button_class="btn btn-success btn- text-white rounded-pill form-text">
                    <div>
                            <span class="fw-bold">『{{$user->name}}』さんに</span>チケットを付与します。
                            <div class="col-8 mx-auto">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon3">付与チケット</span>
                                    <input class="form-control text-end"  type="number" name="value" value="100" min="0">
                                    <span class="input-group-text" id="basic-addon3">枚</span>
                                </div>
                            </div>
                            よろしいですか？
                        </div>
                    </delete-modal-component>
                </form>


            </div>
        </div>
    </div>
</div>


@include('admin.user.common.menu')
