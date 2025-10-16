<form action="{{ $point_sail->r_payment }}" method="get">
    <div class="modal fade"
    id="pointModal{{$point_sail->id}}"
    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="pointModal{{$point_sail->id}}Label" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- modal-header -->
                <div class="modal-header border-0">
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center gap-2">

                            <!--P icon-->
                            @include('includes.point_icon')

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="m-0 fw-bold fs-">
                                    <number-comma-component number="{{ $point_sail->value + $reduction_point }}"></number-comma-component>
                                </h3>
                                <span>pt購入</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- modal-body -->
                <div class="modal-body d-flex justify-content-center">

                    <div class="">
                        クレジット情報入ページへ進みます。<br>
                        クレジット情報入力後、<span class="text-warning fw-bold"
                        >『お支払いへ進む』</span><br>
                        ボタンを押すと決済が完了いたします。
                    </div>

                </div>
                <!-- modal-footer -->
                <div class="modal-footer border-0">
                    <div class="col">
                        <button type="button" data-bs-dismiss="modal"
                        class="btn border w-100">閉じる</button>
                    </div>
                    <div class="col">
                        <button type="submit"
                        class="btn btn-warning text-white w-100">確認して次へ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
