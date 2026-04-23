<form action="" method="POST">
    @csrf

    <div class="row g-2">

        <div class="col-auto">
            <a href="{{ url()->previous() }}" class="btn btn-secondary border rounded-pill">
                <i class="bi bi-chevron-left"></i>
            </a>
        </div> 
        <div class="col">
            <select name="machine_id" class="form-select">
                <option value="">ガチャマシン選択</option>

                @foreach ($machines as $machine)
                    <option value="{{ $machine->id }}"
                    {{ old('machine_id') == $machine->id ? 'selected' : '' }}
                    >{{ $machine->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <button class="btn btn-warning px-4 rounded-pill shadow w-100"
            type="submit">このガチャを購入する</button>
        </div>
    </div>


</form>
