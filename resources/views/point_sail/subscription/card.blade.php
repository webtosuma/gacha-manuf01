<div class="position-relative h-100">


    <div
    class="card border-secondary border-0 shadow bg-transparent
    text-dark text-center overflow-hidden text-decoration-none
    h-100
    "
    style="border-radius:1rem;">


        <div class="d-flex flex-column h-100">

            <!--image-->
            <div class="col-auto">
                @if( $subscription->sub_image_path )
                    <ratio-image-component
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $subscription->sub_label }}"
                    url="{{$subscription->sub_image_path}}"
                    style_class="ratio bg-body {{config('app.gacha_card_ratio')}}"
                    ></ratio-image-component>
                @else
                    <div class="ratio bg-body {{config('app.gacha_card_ratio')}}">
                        <div class="d-flex flex-column align-items-centerr justify-content-center h-100">
                            <div class="fs-4 fw-bold">{{ $subscription->sub_label }}</div>
                        </div>
                    </div>
                @endif
            </div>


            <div class="card-body bg-white pb-0">
                <div class="d-flex align-items-center justify-content-center gap-2 mb- fw-bold">

                    <!--販売価格-->
                    <span>{{ $subscription->sub_billing_cycle }}</span>
                    <h3 class="m-0 fw-bold fs-">{{ number_format( $subscription->price ) }}</h3>
                    <span>円(税込)</span>

                </div>
                <div class="d-flex align-items-end justify-content-center gap-1 mb-3 fw-bold">

                    <!--付与ポイント-->
                    <span>毎回更新時に</span>
                    <h5 class="m-0 fw-bold fs-5 text-info">{{ number_format( $subscription->value ) }}</h5>
                    <span>ptを付与</span>

                </div>
                <!--説明文-->
                <div class="d-flex justify-content-center">
                    <p class="text-start fw-bold m-0">
                        {!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $subscription->sub_description_text) )!!}
                    </p>
                </div>
            </div>

            <!--契約ボタン-->
            <div class="col-auto">
                @if( ! $subscription->is_published )
                    <!--管理者ページ・未公開-->
                    <div class="card-body bg-secondary">
                        <div
                        class="btn btn-light bg-gradient fw-bold
                        rounded-pill border-0 shadow-sm w-100
                        disabled
                        ">非公開</div>
                    </div>

                @elseif(! isset($subscription->r_checkout))
                    <!--管理者ページ・公開-->
                    <div class="card-body bg-white">
                        <a href="{{ $subscription->r_checkout }}"
                        class="btn btn-info bg-gradient text-white fw-bold
                        rounded-pill border-0 shadow-sm w-100
                        disabled
                        ">このプランを申し込む</a>
                    </div>

                @elseif( $subscription->auth_user_sub )
                    <!--利用中-->
                    <div class="card-body bg-white">
                        <a href="{{ route('point_sail.customer_portal') }}"
                        class="btn btn-light border-info text-info fw-bold
                        rounded-pill shadow-sm w-100
                        ">利用中</a>
                    </div>

                @else
                    <!--公開中-->
                    <div class="card-body bg-white">
                        <a href="{{ $subscription->r_checkout }}"
                        class="btn btn-info bg-gradient text-white fw-bold
                        rounded-pill border-0 shadow-sm w-100
                        @if( ! isset($subscription->r_checkout) ) disabled @endif
                        ">このプランを申し込む</a>
                    </div>

                @endif
            </div>
        </div>


    </div>



    <!--admin menu-->
    @isset($admin_menu)
        <div class="dropdown position-absolute top-0 start-100 translate-middle" style="z-index:100;">
            <button
            class="btn btn-light border border-dark text-dark rounded-circle" type="button"
            id="{{'dropdownMenuButton'.$subscription->id}}"
            data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical fs-5"></i>
            </button>

            <ul class="dropdown-menu" aria-labelledby="{{'dropdownMenuButton'.$subscription->id}}"  style="z-index:100;">

                <li><a class="dropdown-item"
                href="{{route('admin.subscription.current_user',$subscription)}}"
                >契約中ユーザー一覧</a></li>

                <li><a class="dropdown-item"
                href="{{route('admin.subscription.edit',$subscription)}}"
                >編集する</a></li>


                <li><button type="button" data-bs-toggle="modal"
                data-bs-target="{{ '#deleteModal'.'delete'.$subscription->id }}"
                class="dropdown-item"
                >削除する</button></li>
            </ul>
        </div>
    @endisset

</div>



<!--admin 削除モーダル-->
@isset($admin_menu)
    <div class="overflow-hidden" style="height: 0;">
        <form action="{{route('admin.subscription.destroy',$subscription)}}" method="post">
            @csrf
            @method('DELETE')

            <delete-modal-component
            index_key="{{'delete'.$subscription->id}}"
            icon="bi-trash"
            func_btn_type="submit"
            button_class="invisible">
                <div class="fs-6">
                    <span class="fw-bold">『{{$subscription->sub_label}}』</span>を削除します。
                    <br>関連するガチャは全て非公開となり、
                    <br>サブスクガチャ設定は解除されます。
                    <br>よろしいですか？
                </div>
            </delete-modal-component>
        </form>
    </div>
@endisset
