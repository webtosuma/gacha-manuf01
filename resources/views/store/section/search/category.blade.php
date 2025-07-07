@php
$categories = \App\Models\GachaCategory::where('is_published',1)
->orderBy('created_at')->get();
@endphp
<ul class="row g-3 px-0" style="list-style:none;">


    <li class="col-6 col-md-3">


        <input id="{{'all'}}"
        name="category_code_name" value="{{'all'}}"
        type="radio" class="btn-check" autocomplete="off"
        @if (
            ( isset( $search_inputs['category_code_name'] )
            && $search_inputs['category_code_name']=='all' )
            || !isset( $search_inputs['category_code_name'] )
        ) checked @endif
        >

        <label for="{{'all'}}"
        class="btn btn-outline-dark rounded- w-100" >{{'すべて'}}</label>


    </li>

    @foreach ($categories as $category)

        <li class="col-6 col-md-3">


            <input id="{{$category->code_name}}"
            name="category_code_name" value="{{$category->code_name}}"
            type="radio" class="btn-check" autocomplete="off"
            @if (
                isset( $search_inputs['category_code_name'] )
                && $search_inputs['category_code_name']==$category->code_name
            ) checked @endif
            >

            <label for="{{$category->code_name}}"
            class="btn btn-outline-dark rounded- w-100" >{{$category->name}}</label>


        </li>

    @endforeach
</ul>
