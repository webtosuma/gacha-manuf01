<div class="position-relative pt-0">
    <!--loading-->
    <div class="ratio ratio-3x4">
        <div class="d-flex align-items-center justify-content-center"
        style="z-index:0;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <!--prize image-->
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:0;">
        <ratio-image-component
        url="{{ $store->prize->image_path }}" style_class="ratio ratio-3x4 rounded-3"
        ></ratio-image-component>
    </div>


    @if ($store->count<1)
    <div class="position-absolute top-0 start-0 w-100 h-100"
    style="z-index:3; background: rgba(0, 0, 0, .8);"
    ><div class="d-flex align-items-center justify-content-center h-100 fs- text-white"
    >SOLD OUT</div></div>
    @endif
</div>
