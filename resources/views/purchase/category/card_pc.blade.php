@php
$params = ['category_id'=>''];
@endphp
<div class="col-auto">
    <a  href="{{ route('purchase', $params ) }}"
    class="category-link h-100
    btn d-block p-0">
        <div class="position-relative overflow-hidden h-100 mx-auto rounded-pill" style="width:8rem;">
            <div style_class="ratio ratio-3x4 bg-body"></div>

            <div class="d-flex align-items-center justify-content-center
            position-absolute top-0 start-0 w-100 h-100 fw-bold
                @if(!$category_id) bg-primary text-white @else text-white @endif
            "
            style="background:rgba(0, 0, 0, .8); opacity:.7;"
            >{{'すべて'}}</div>
        </div>
    </a>
</div>



@foreach ($categories as $category)
    @php
    $params = ['category_id'=>$category->id,];
    @endphp

    <div class="col-auto">
        <a  href="{{ route('purchase', $params ) }}"
        id="category-link-{{$category->code_name}}"
        class="category-link
        btn d-block p-0 m-0
        ">
            <div class="position-relative overflow-hidden h-100 mx-auto rounded-pill" style="width:8rem;">
                <ratio-image-component
                url="{{ $category->top_prize_image_path }}" style_class="ratio ratio-1x1 w-100"
                ></ratio-image-component>

                <div class=" d-flex align-items-center justify-content-center
                position-absolute top-0 start-0 w-100 h-100 fw-bold
                @if($category_id==$category->id) bg-primary text-white @else text-white @endif"
                style="background:rgba(0, 0, 0, .8); opacity:.7;"
                >{{$category->name}}</div>
            </div>
        </a>
    </div>
@endforeach
