@php
$params = ['category_id'=>''];
@endphp
<a  href="{{ route('purchase', $params ) }}"
class="col-md btn btn-light border-0 d-block p-0 position-relative overflow-hidden
"
style="width: 6rem;">
    <div style_class="ratio ratio-3x4 bg-body"></div>

    <div class="d-flex align-items-center justify-content-center
    position-absolute top-0 start-0 w-100 h-100 fw-bold
        @if($category_id=='') bg-primary text-white @else text-white @endif
    "
    style="background:rgba(0, 0, 0, 0.5);"
    >{{'すべて'}}</div>
</a>

@foreach ($categories as $category)
    @php
    $params = ['category_id'=>$category->id,];
    @endphp

    <a  href="{{ route('purchase', $params ) }}"
    id="category-link-{{$category->id}}"
    class="category-link
    col-md btn btn-light border-0 d-block p-0 position-relative overflow-hidden
    "
    style="width: 6rem">
        <ratio-image-component
        url="{{ $category->top_prize_image_path }}" style_class="ratio ratio-3x4 bg-body"
        ></ratio-image-component>

        <div class=" d-flex align-items-center justify-content-center
        position-absolute top-0 start-0 w-100 h-100 fw-bold
        @if($category_id==$category->id) bg-primary text-white @else text-white @endif
        "
        style="background:rgba(0, 0, 0, 0.5);"
        >{{$category->name}}</div>
    </a>
@endforeach


