<div class="">



    <div class="p-3 bg-body rounded-4">


        <a href="{{route('admin.gacha_title.machine',$gacha_title),}}"
        class="btn btn-light border">< 戻る</a>


    </div>



    <section class="mt-4 mb-5 p-3 border rounded-4">

        <h5 class="fw-bold mb-4">筐体基本情報</h5>

        @include('manuf_admin.gacha_title.machine._machine_inputs')

    </section>



    <div class="my-3">
    @if (!$machine->id)
        <button class="btn btn-success text-white w-100 shadow" 
        data-bs-toggle="modal" data-bs-target="#deleteModalupdate" 
        type="button"
        >登録する</bbutton>
    @else
        <button class="btn btn-warning w-100 shadow" 
        data-bs-toggle="modal" data-bs-target="#deleteModalupdate" 
        type="button"
        >更新する</bbutton>
    @endif

    </div>



</div>
