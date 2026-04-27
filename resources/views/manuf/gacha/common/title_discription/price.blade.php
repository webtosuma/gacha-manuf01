<!--Price-->
<div class="d-flex justify-content-end">
    <div class="text-center">
        <span style="font-size:16px;">１回/</span>
        <span style="font-size:16px;">税込</span>
        <div class="d-inline-block" style="line-height:18px;">
            <span class="fs-3 text-danger">¥</span>
            <span class="fs-1 text-danger"> {{number_format($gacha_title->price)}}</span>
        </div>
    </div>
</div>



<!--description_text-->
@if($gacha_title->description_text)
    <p id="discription-description_text" class="border-top bg- p-2 my-2 form-text">

        <replace-text-component text="{{ $gacha_title->description_text  }}" ></replace-text-component>

    </p>
@endif



