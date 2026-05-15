<form action="{{$gacha_title->r_purchase_appliy}}" method="GET">

    <div class="row g-3">

        <div class="col-12">
            <select name="gacha_key" class="form-select">
                <option value="">ガチャマシン選択</option>

                @foreach ($machines as $machine)
                    <option value="{{ $machine->key }}"
                    {{ old('machine_id') == $machine->key ? 'selected' : '' }}
                    >{{ $machine->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <button class="btn btn-warning px-4 rounded-pill shadow w-100"
            type="submit">このガチャを購入する</button>
        </div>
    </div>


</form>
