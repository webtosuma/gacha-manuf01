<section>
    <div class="mx-auto my-5" style="max-width:900px;">

        <div class="card">
            <h5 class="card-header">
                レインボー設定
            </h5>

            <form action="{{ route('admin.text.update_rainbow') }}" method="POST" novalidate
            enctype="multipart/form-data" onsubmit="stopOnbeforeunload()">
                @csrf
                @method('PATCH')

                <div class="card-body">


                    <div class="mb-3">

                        @switch($rainbows['rainbow_published_status'])
                            @case(1)
                                <span class="badge rounded-pill bg-success fs-6">レインボー実行中</span>
                                @break
                            @case(2)
                                <span class="badge rounded-pill bg-warning fs-6">レインボー予約中</span>
                                @break
                            @default
                                <span class="badge rounded-pill bg-secondary fs-6">レインボー停止</span>
                                @break

                        @endswitch
                    </div>


                    <!--開始日時(rainbow_start_at)-->
                    <label class="d-block mb-4">

                        <div class="input-group">
                            <span class="input-group-text" >開始日時</span>
                            <input
                            name="rainbow_start_at"
                            type="datetime-local" class="form-control"
                            value="{{old( 'rainbow_start_at', $rainbows['rainbow_start_at'] )}}"
                            min="{{ now()->format('Y-m-d\T00:00') }}">
                        </div>

                        <!--error message-->
                        @if ( $errors->has('rainbow_start_at') )
                            <div class="text-danger"> {{$errors->first('rainbow_start_at')}} </div>
                        @endif

                    </label>



                    <!--終了日時(rainbow_end_at)-->
                    <label class="d-block mb-4">

                        <div class="input-group">
                            <span class="input-group-text" >終了日時</span>
                            <input
                            name="rainbow_end_at"
                            type="datetime-local" class="form-control"
                            value="{{old( 'rainbow_end_at', $rainbows['rainbow_end_at'] )}}"
                            min="{{ now()->format('Y-m-d\T00:00') }}">
                        </div>

                        <!--error message-->
                        @if ( $errors->has('rainbow_end_at') )
                            <div class="text-danger"> {{$errors->first('rainbow_end_at')}} </div>
                        @endif

                    </label>



                    <div class="row g-3 align-items-center mt-5">

                        <div class="col-12 col-md-6">
                            <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow"
                            btn_text="予約日時を更新"></disabled-button>
                        </div>

                        <div class="col-6 col-md-auto">
                            <disabled-button style_class="btn btn-light border w-100
                            @if($rainbows['rainbow_published_status']==1) disabled @endif
                            "
                            name="type"
                            value="start_now"
                            btn_text="今すぐ開始"></disabled-button>
                        </div>

                        <div class="col-6 col-md-auto">
                            <disabled-button style_class="btn btn-dark text-white w-100
                            @if($rainbows['rainbow_published_status']==0) disabled @endif
                            "
                            name="type"
                            value="end_now"
                            btn_text="今すぐ終了"></disabled-button>
                        </div>


                    </div>





                </div>



            </form>
        </div>

    </div>
</section>

