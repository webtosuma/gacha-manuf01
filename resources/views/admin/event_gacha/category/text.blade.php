    @php
    $sc = "col py-2 fw-bold btn btn-sm btn-light border-0 rounded-pill";
    $style_class = $category_code=='all' ? $sc.' disabled bg-primary text-white' : $sc;
    // $params = ['category_code'=>'all', 'search_key'=>$search_key, 'card_size'=>$card_size];
    $params = ['category_code'=>'all'];
    @endphp

    <a  href="{{ route('event.gacha',$params) }}"
    class="{{ $style_class }} d-flex align-items-center justify-content-center">
        <span>{{ 'すべて' }}</span>
    </a>

    @foreach ($categories as $category)
        @php
        $style_class = $category_code == $category->code_name ? $sc.' disabled bg-primary text-white' : $sc;
        $params = ['category_code'=>$category->code_name,];
        @endphp

        <a  href="{{ route('event.gacha', $params ) }}" class="{{ $style_class }}
        d-flex align-items-center justify-content-center">
            <span>{{$category->name}}</span>
        </a>
    @endforeach


