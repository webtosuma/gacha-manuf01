<section class="bg- py-3 mb-4"
data-aos="fade-in"
>

    <div class="container px-0 px-lg-3 mx-auto overflow-auto">
        <nav class="nav gap-1 flex-nowrap" style="min-width:{{$categories->count()*6 + 10}}rem;">
            @php
            $sc = "col-auto col-lggg fs- py-2 fw-bold btn btn-sm btn-light border-0 rounded-pill";

            $style_class = $category_code=='all' ? $sc.' disabled bg-primary text-white' : $sc;
            $params = ['category_code'=>'all', 'search_key'=>$search_key, 'card_size'=>$card_size];
            @endphp
            <a  href="{{ route('event.gacha',$params) }}"
            class="{{ $style_class }}">{{ 'すべて' }}</a>


            @foreach ($categories as $category)
                @php
                $style_class = $category_code == $category->code_name ? $sc.' disabled bg-primary text-white' : $sc;
                $params = ['category_code'=>$category->code_name, 'search_key'=>$search_key, 'card_size'=>$card_size];
                @endphp

                <a  href="{{ route('event.gacha', $params ) }}" class="{{ $style_class }}">
                    <span>{{$category->name}}</span>
                </a>
            @endforeach
        </nav>
    </div>


    <!--絞り込みキー-->
    <div class="container px-0 px-lg-3 mx-auto overflow-auto mt-2">
        @php
        $sc = "col- fs- py-1 fw-bold btn btn-sm btn-light border-0 px-3 rounded-pill";
        $search_key = $search_key ? $search_key : 'desc_crated';
        @endphp
        <nav class="nav gap-1 flex-nowrap" style="min-width:{{count($searchs)*6 + 12}}rem;" style="font-size:11px;">

            <!-- カードサイズ変更 -->
            @php
            $params = ['category_code'=>$category_code, 'search_key'=>$search_key, 'card_size'=>$card_size=='sm' ?'':'sm' ];
            @endphp
            <a href="{{route('event.gacha',$params)}}"
            style="font-size:11px;"
            class="{{ $sc }}">{{ $card_size=='sm' ?'大きく表示':'小さく表示'}}</a>


            <!-- 絞り込み -->
            @foreach ($searchs as $search)
                @php
                $style_class = $search_key==$search['key'] ? $sc.' disabled bg-primary text-white border-primary' : $sc;

                $params = ['category_code'=>$category_code, 'search_key'=>$search['key'], 'card_size'=>$card_size];
                @endphp


                <a id="{{$search['key']}}"  href="{{route('event.gacha',$params)}}"
                style="font-size:11px;"
                class="{{ $style_class }}">{{ $search['label'] }}</a>
            @endforeach
        </nav>
    </div>
</section>
