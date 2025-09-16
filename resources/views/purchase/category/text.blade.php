    @php
    $sc = "col py-2 fw-bold btn btn-sm btn-light border-0 rounded-pill";
    $style_class = $category_id=='' ? $sc.' disabled bg-primary text-white' : $sc;
    $params = ['category_id'=>''];
    @endphp

    <a  href="{{ route('purchase',$params) }}"
    class="{{ $style_class }} d-flex align-items-center justify-content-center">
        <span>{{ 'すべて' }}</span>
    </a>

    @foreach ($categories as $category)
        @php
        $style_class = $category_id == $category->code_name ? $sc.' disabled bg-primary text-white' : $sc;
        $params = ['category_id'=>$category->code_id,];
        @endphp

        <a  href="{{ route('purchase', $params ) }}" class="{{ $style_class }}
        d-flex align-items-center justify-content-center">
            <span>{{$category->name}}</span>
        </a>
    @endforeach


