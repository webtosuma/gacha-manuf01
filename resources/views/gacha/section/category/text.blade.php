{{-- <section class="bg- py-3 mb-4"
data-aos="fade-in"
>

    <div class="px-3 mx-auto overflow-auto">
        <nav id="catecory-nav-container"
        class="nav gap-1 flex-nowrap w- mx-auto" style="width:{{($categories->count()+1)*6}}rem;">
            @php
            $params = ['category_code'=>'all', 'search_key'=>$search_key, 'card_size'=>$card_size];
            @endphp
            <a  href="{{ route('gacha_category', $params ) }}"
            class="col-md btn btn-light border-0 d-block p-0 position-relative overflow-hidden
            "
            style="width: 6rem;">
                <div style_class="ratio ratio-3x4 bg-body"></div>

                <div class="d-flex align-items-center justify-content-center
                position-absolute top-0 start-0 w-100 h-100 fw-bold
                    @if($category_code=='all') bg-primary text-white @else text-white @endif
                "
                style="background:rgba(0, 0, 0, 0.5);"
                >{{'すべて'}}</div>
            </a>

            @foreach ($categories as $category)
                @php
                $params = ['category_code'=>$category->code_name, 'search_key'=>$search_key, 'card_size'=>$card_size];
                @endphp

                <a  href="{{ route('gacha_category', $params ) }}"
                id="category-link-{{$category->code_name}}"
                class="category-link
                col-md btn btn-light border-0 d-block p-0 position-relative overflow-hidden
                "
                style="width: 6rem">
                    <ratio-image-component
                    url="{{ $category->top_prize_image_path }}" style_class="ratio ratio-3x4 bg-body"
                    ></ratio-image-component>

                    <div class=" d-flex align-items-center justify-content-center
                    position-absolute top-0 start-0 w-100 h-100 fw-bold
                    @if($category_code==$category->code_name) bg-primary text-white @else text-white @endif
                    "
                    style="background:rgba(0, 0, 0, 0.5);"
                    >{{$category->name}}</div>
                </a>
            @endforeach
        </nav>
    </div>


    <!--絞り込みキー-->
    <div class="container px-0 px-lg-3 mx-auto overflow-auto mt-2 mb-0">
        @php
        $sc = "col- fs- py-1 fw-bold btn btn-sm btn-light border-0 px-3 rounded-pill";
        $search_key = $search_key ? $search_key : 'desc_crated';
        @endphp
        <nav class="nav gap-1 flex-nowrap" style="min-width:{{count($searchs)*6 + 12}}rem;" style="font-size:11px;">

            <!-- カードサイズ変更 -->
            @php
            $params = ['category_code'=>$category_code, 'search_key'=>$search_key, 'card_size'=>$card_size=='sm' ?'':'sm' ];
            @endphp
            <a href="{{route('gacha_category',$params)}}"
            style="font-size:11px;"
            class="{{ $sc }}">{{ $card_size=='sm' ?'大きく表示':'小さく表示'}}</a>


            <!-- 絞り込み -->
            @foreach ($searchs as $search)
                @php
                $style_class = $search_key==$search['key'] ? $sc.' disabled bg-primary text-white border-primary' : $sc;

                $params = ['category_code'=>$category_code, 'search_key'=>$search['key'], 'card_size'=>$card_size];
                @endphp


                <a id="{{$search['key']}}"  href="{{route('gacha_category',$params)}}"
                style="font-size:11px;"
                class="{{ $style_class }}">{{ $search['label'] }}</a>
            @endforeach
        </nav>
    </div>


</section> --}}








{{-- <div class="container px-0 px-lg-3 mx-auto overflow-auto">
    <nav class="nav gap-1 flex-nowrap " style="width:{{($categories->count()+1)*6.8}}rem;">

        @php
        $sc = "col py-2 fw-bold btn btn-sm btn-light border-0 rounded-pill";

        $style_class = $category_code=='all' ? $sc.' disabled bg-primary text-white' : $sc;
        $params = ['category_code'=>'all', 'search_key'=>$search_key, 'card_size'=>$card_size];
        @endphp

        <a  href="{{ route('gacha_category',$params) }}"
        class="{{ $style_class }} d-flex align-items-center justify-content-center">
            <span>{{ 'すべて' }}</span>
        </a>


        @foreach ($categories as $category)
            @php
            $style_class = $category_code == $category->code_name ? $sc.' disabled bg-primary text-white' : $sc;
            $params = ['category_code'=>$category->code_name, 'search_key'=>$search_key, 'card_size'=>$card_size];
            @endphp

            <a  href="{{ route('gacha_category', $params ) }}" class="{{ $style_class }}
            d-flex align-items-center justify-content-center">
                <span>{{$category->name}}</span>
            </a>
        @endforeach


    </nav>
</div> --}}



    @php
    $sc = "col py-2 fw-bold btn btn-sm btn-light border-0 rounded-pill";
    $style_class = $category_code=='all' ? $sc.' disabled bg-primary text-white' : $sc;
    // $params = ['category_code'=>'all', 'search_key'=>$search_key, 'card_size'=>$card_size];
    $params = ['category_code'=>'all'];
    @endphp

    <a  href="{{ route('gacha_category',$params) }}"
    class="{{ $style_class }} d-flex align-items-center justify-content-center">
        <span>{{ 'すべて' }}</span>
    </a>

    @foreach ($categories as $category)
        @php
        $style_class = $category_code == $category->code_name ? $sc.' disabled bg-primary text-white' : $sc;
        $params = ['category_code'=>$category->code_name,];
        @endphp

        <a  href="{{ route('gacha_category', $params ) }}" class="{{ $style_class }}
        d-flex align-items-center justify-content-center">
            <span>{{$category->name}}</span>
        </a>
    @endforeach


