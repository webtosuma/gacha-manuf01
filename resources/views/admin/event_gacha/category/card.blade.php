{{-- <div class="px-3 mx-auto overflow-auto">
    <nav id="catecory-nav-container"
    class="nav gap-1 flex-nowrap w- mx-auto" style="width:{{($categories->count()+1)*4.8}}rem;"> --}}


        @php
        // $params = ['category_code'=>'all', 'search_key'=>$search_key, 'card_size'=>$card_size];
        $params = ['category_code'=>'all'];
        @endphp
        <a  href="{{ route('event.gacha', $params ) }}"
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
            $params = ['category_code'=>$category->code_name,];
            @endphp

            <a  href="{{ route('event.gacha', $params ) }}"
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


    {{-- </nav>
</div> --}}
