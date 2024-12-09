<div class="modal fade" id="gachaModal" tabindex="-1" aria-labelledby="gachaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
                <form action="{{ route('admin.gacha.play', $params) }}" method="post">
                    @csrf

                    <h5 class="modal-title text-center" id="gachaModalLabel">
                        <p>回数を指定して、<br>「ガチャる」ボタンを押してください。</p>

                        @if ($gacha->type=='max_custom')
                            <!--上限ありのとき-->
                            <div class="badge fs-6 bg-danger">最大上限{{number_format( $gacha->max_custom_count() )}}回まで一括で回すことができます。</div>
                        @endif
                    </h5>
                    <select name="play_count"
                    class="form-select mb-3"  aria-label="Default select example">


                        @php
                        /* カスタムに上限があるとき */
                        $max_count = $gacha->type=='max_custom' ? $gacha->max_custom_count() : $gacha->remaining_count;
                        @endphp

                        @for ($num = 1; $num <= $max_count; $num++)
                            <option value="{{ $num }}">{{ $num.'回ガチャる' }}</option>
                        @endfor


                    </select>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button"
                            class="btn btn-light border rounded-pill w-100"
                            data-bs-dismiss="modal"
                            >キャンセル</button>
                        </div>
                        <div class="col-6">
                            {{-- <button type="submit"
                            class="btn btn-info text-white rounded-pill w-100"
                            >ガチャる</button> --}}

                            <disabled-cover-button
                            btn_text="ガチャる"
                            style_class="btn btn-info text-white rounded-pill w-100"
                            ></disabled-cover-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
