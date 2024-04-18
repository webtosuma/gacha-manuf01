<div class="modal fade" id="{{'gachaModal'.$gacha->id}}" tabindex="-1" aria-labelledby="{{'gachaModalLabel'.$gacha->id}}" aria-hidden="true" style="z-index:1050;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                @php $params = ['category_code'=>$gacha->category->code_name, 'gacha'=>$gacha, 'key'=>$gacha->key]; @endphp
                <form action="{{ route('gacha.play', $params) }}" method="post">
                    @csrf

                    <h5 class="modal-title text-center" id="{{'gachaModalLabel'.$gacha->id}}">
                        <p>回数を指定して、<br>「ガチャる」ボタンを押してください。</p>
                    </h5>
                    <select name="play_count"
                    class="form-select mb-3"  aria-label="Default select example">

                        @for ($num = 1; $num <= $gacha->remaining_count; $num++)
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
                            <button type="submit"
                            class="btn btn-info text-white rounded-pill w-100"
                            >ガチャる</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
