<form action="{{$gacha_title->r_purchase_appliy}}" method="GET">

    <div class="row g-3">

        <div class="col-12">
            <select name="gacha_key" class="form-select">
                <option value="">ガチャマシン選択</option>

                @foreach ($machines as $machine)
                    @if($machine->not_purchase)<!--販売不可は表示しない--> @continue  @endif

                    <option value="{{ $machine->key }}"
                    {{ old('machine_id') == $machine->key ? 'selected' : '' }}
                    >{{ $machine->name}}</option>
                @endforeach

            </select>
        </div>

        <div class="col-12">
            @if ($gacha_title->is_sales)
                <button class="btn btn-warning px-4 rounded-pill shadow w-100"
                type="submit">このガチャを購入する</button>
            @else
                <button class="btn btn-secondary px-4 rounded-pill shadow w-100"
                disabled
                type="button">近日販売開始</button>
            @endif
        </div>
    </div>


</form>
