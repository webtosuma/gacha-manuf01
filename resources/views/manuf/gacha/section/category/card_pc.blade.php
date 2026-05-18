@php
$params = ['category_code'=>'all'];
@endphp
<div class="col-auto">
    <a  href="{{ route('manuf', $params ) }}"
    class="category-link
    btn d-block p-0">
        <div class="position-relative overflow-hidden h-100 mx-auto rounded-pill" style="width:8rem;">
            <ratio-image-component
            url="{{asset('storage/site/image/logo.png')}}" style_class="ratio ratio-1x1 w-100 bg-white"
            ></ratio-image-component>

            @if($category_code=='all')
                <div class=" d-flex align-items-center justify-content-center
                position-absolute top-0 start-0 w-100 h-100 bg-primary"
                style="opacity:.8;"
                ></div>
            @endif
        </div>


        <div class="text-center text-white fw-bold mt-2 fs-5"
        >{{'すべて'}}</div>
    </a>
</div>


@foreach ($categories as $category)
    @php
    $params = ['category_code'=>$category->code_name,];
    @endphp

    <div class="col-6 col-md-auto">
        <a  href="{{ route('manuf', $params ) }}"
        id="category-link-{{$category->code_name}}"
        class="category-link
        btn d-block p-0 m-0
        ">
            <div class="position-relative overflow-hidden h-100 mx-auto rounded-pill" style="width:8rem;">
                <ratio-image-component
                url="{{ $category->manuf_gacha_title_image_path }}" style_class="ratio ratio-1x1 w-100 bg-white"
                ></ratio-image-component>

                @if( $category_code==$category->code_name )
                    <div class=" d-flex align-items-center justify-content-center
                    position-absolute top-0 start-0 w-100 h-100 bg-primary"
                    style="opacity:.8;"
                    ></div>
                @endif
            </div>

            <div class="text-center text-white fw-bold mt-2 fs-5"
            >{{$category->name}}</div>
        </a>
    </div>

@endforeach
