<a
@unless($machine->not_purchase)
    data-bs-toggle="offcanvas"
    href="#oc_prizes{{ $machine->id }}"
    role="button"
    aria-controls="oc_prizes{{ $machine->id }}"
@endunless

class="btn p-0 hover_anime border-0
{{ $machine->not_purchase ? 'disabled pe-none opacity-50' : '' }}"
>

    <div class="d-inline-block rounded px-3
    fw-bold text-center text-info"
    >{{ $machine->name }}</div>

    @include('manuf.gacha.common.machine_icon')

    <button class="btn btn-sm btn-dark fw-bold px-0 rounded-pill w-100"
    style="transform: translateY(-.5rem);"
    type="button"
    >詳細を見る</button>

</a>
