<h5 class="fw-bold ps-1
border-start border-info border-5
" >ガチャマシンを選ぶ</h5>

<div class="card bg-white mb-4 py-3">


    <div id="splide_mobile"  class="splide w-100" aria-label="Splideの基本的なHTML">

        <div class="splide__track">
            <ul class="splide__list">
                @foreach ($gacha_title->machines as  $machine)
                    <li class="splide__slide px-2 text-center">

                        <a
                        data-bs-toggle="offcanvas" href="#oc_prizes{{ $machine->id }}" role="button"
                        aria-controls="oc_prizes{{ $machine->id }}"
                        class="btn p-0 hover_anime"
                        >

                            <div class="d-inline-block rounded border px-3
                            fw-bold text-center text-info fs-6">マシン09{{ $machine->id }}</div>

                            @include('manuf.gacha.common.machine_icon')

                        </a>


                        <!--gachaCustomModal-->
                        <div class="col-12">
                            <button class="btn btn-warning text-dark fw-bold rounded-pill w-100
                            hover_anime "
                            data-bs-toggle="modal" data-bs-target="#gachaCustomModal{{ $machine->id }}"
                            type="button"
                            >ガチャを回す</button>
                        </div>

                    </li>
                @endforeach
            </ul>
        </div>

    </div>

</div>
