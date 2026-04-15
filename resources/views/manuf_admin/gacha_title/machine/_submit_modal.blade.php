@php
$btn_color = !$machine->id ? 'success' : 'warning';
$btn_text  = !$machine->id ? '登録する' : '更新する';
$btn_class = (!$machine->id ? 'btn-success text-white' : 'btn-warning').' btn  w-100 shadow d-none'
@endphp
<delete-modal-component
index_key="update"
icon="bi-exclamation-triangle"
func_btn_type="submit"
color        ="{{$btn_color}}"
button_text  ="{{$btn_text}}"
button_class ="{{$btn_class}}">
    <div class="text-danger fs-6">

        <div class="fs-5 mb-4">ご注意ください！</div>
        <br>

        <div class="mt-3">
            ガチャ公開中や、ガチャ商品の残量が減っている状態で登録商品を更新すると、排出商品数がズレる可能性があります。
        </div>


        <div class="fs-5 mt-3 text-dark">更新してもよろしいですか？</div>
    </div>
</delete-modal-component>        
