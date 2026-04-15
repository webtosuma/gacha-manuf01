<div >
    
 
    @php  $gacha = $machine->gacha ;@endphp
    {{-- <a-gachaprize-edit
    token="{{ csrf_token() }}"
    category_id   ="{{ $gacha->category->id }}"
    r_api_prize   ="{{ route('admin.api.prize') }}" 
    r_api_gacha_ranks ="{{ route('admin.api.gacha.ranks',$gacha) }}"
    r_api_ranks_gacha_prizes ="{{ route('admin.api.gacha.ranks_gacha_prizes') }}"
    ></a-gachaprize-edit> --}}

    <a-machine-prize-edit
    token="{{ csrf_token() }}"
    category_id   ="{{ $gacha?->category->id }}"
    r_api_prize   ="{{ route('admin.api.prize') }}" 
    r_api_gacha_ranks ="{{ route('admin.api.gacha.ranks',$gacha) }}"
    r_api_ranks_gacha_prizes ="{{ route('admin.api.gacha.ranks_gacha_prizes') }}"
    ></a-machine-prize-edit>


</div>
