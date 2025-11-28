
<div class="input-group">

    <label class="input-group-text fw-bold">追跡番号</label>

    <input type="text" class="form-control"
    value="{{$user_shipped->tracking_code}}"
    placeholder="例：12345678901"
    name="tracking_code"
    max="14"
    >
    <select class="form-select" name="shipping_company_id">
        <option value="">発送企業</option>

        @foreach ($shipping_companies as $shipping_company)

            <option value="{{$shipping_company['id']}}"
            {{ $shipping_company['id']==$user_shipped->shipping_company_id?'selected':'' }}
            >{{$shipping_company['name']}}</option>

        @endforeach
    </select>
</div>

<div class="form-text text-start">＊追跡番号の入力は任意です。</div>
<div class="form-text text-start">＊追跡番号・発送企業の両方の入力が必要です。どちらか未入力の場合、追跡番号は登録されません。</div>
