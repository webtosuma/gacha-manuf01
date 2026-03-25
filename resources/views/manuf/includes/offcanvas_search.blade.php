<form action="{{route('manuf.search')}}" method="get"
class="offcanvas offcanvas-top" tabindex="-1" id="offcanvaSearch"
aria-labelledby="offcanvaSearchLabel"
style="height: 80vh"
>

    <div class="container offcanvas-header" style="max-width:900px;">
            <h5 class="offcanvas-title" id="offcanvaSearchLabel">商品検索</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="container offcanvas-body" style="max-width:900px;">

        <!--キーワード検索-->
        {{-- <div class="mb-4">
            <u-store-item-search-keyword
            token="{{ csrf_token() }}"
            r_api_list="{{ route('store_item.api.search_history') }}"
            keyword="{{ isset( $search_inputs['keyword'] ) ? $search_inputs['keyword'] : '' }}"
            ></u-store-item-search-keyword>
        </div> --}}


        {{-- <div class="mb-4">

            <h5 class="fs-6 ">カテゴリー</h5>
            @include('store.section.search.category')


        </div> --}}


        {{-- <div class="mb-4">

            <h5 class="fs-6 ">並び替え</h5>

            @php $orders = \App\Models\StoreItem::orders(); @endphp
            <select class="form-select" name="order">
                @foreach ($orders as $order)
                    <option value="{{$order['key']}}"
                    @if (
                        isset( $search_inputs['order'] ) && $search_inputs['order']==$order['key']
                    ) selected @endif
                    >{{ $order['label'] }}</option>
                @endforeach
            </select>


        </div> --}}
    </div>
    <div class="container p-3" style="max-width:900px;">
        <div class="col-md-6 mx-auto">
            <button class="btn btn-info text-white rounded-pill w-100">検索</button>
        </div>
    </div>

</form>


