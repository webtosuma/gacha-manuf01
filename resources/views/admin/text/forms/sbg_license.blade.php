<form action="{{ route('admin.text.sbg_license.update') }}" method="POST" novalidate
enctype="multipart/form-data" onsubmit="stopOnbeforeunload()"
class="col-md-6 mx-auto"
>
    @csrf
    @method('PATCH')



    <!--古物商許可番号(license_number)-->
    <label class="d-block mb-4">
        <div class="form-label">
            古物商許可番号
            <span class="text-danger">＊</span>
        </div>
        <div class="form-text">12桁の数字を入力してください。</div>

        <!--error message-->
        @if ( $errors->has('default_license_number') )
            <div class="text-danger"> {{$errors->first('default_license_number')}} </div>
        @endif

        <encodedーinputtext-component
        id="license_number" name="license_number"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('license_number') ) : $text_bodys['license_number'] }}"
        maxlength="12"
        placeholder="000010000000"
        ></encodedーinputtext-component>

    </label>



    <!--公安委員会の名称(license_commission)-->
    <label class="d-block mb-4">
        <div class="form-label">
            公安委員会の名称
            <span class="text-danger">＊</span>
        </div>

        <!--error message-->
        {{-- @if ( $errors->has('default_license_commission') )
            <div class="text-danger"> {{$errors->first('default_license_commission')}} </div>
        @endif --}}
        @if ( $errors->has('license_commission') )
            <div class="text-danger"> {{$errors->first('license_commission')}} </div>
        @endif

        @php
        $prefectures = [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
        ];
        @endphp
        <select class="form-select" name="license_commission">
            <option value="">選択してください</option>
            @foreach ($prefectures as $prefecture)
                <option
                value="{{$prefecture.'公安委員会'}}"
                @if( ( $errors->all() ? urldecode( old('license_commission') ) : $text_bodys['license_commission'] )==$prefecture.'公安委員会' ) selected @endif
                >{{$prefecture.'公安委員会'}}</option>
            @endforeach
        </select>
        {{-- <encodedーinputtext-component
        id="license_commission" name="license_commission"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('license_commission') ) : $text_bodys['license_commission'] }}"
        maxlength="140"
        placeholder="○○県公安委員会"
        ></encodedーinputtext-component> --}}

    </label>



    <!--法人(個人)名称(license_name)-->
    <label class="d-block mb-4">
        <div class="form-label">
            法人(個人)名称
            <span class="text-danger">＊</span>
            <span class="form-text">140文字以内</span>
        </div>

        <!--error message-->
        @if ( $errors->has('default_license_name') )
            <div class="text-danger"> {{$errors->first('default_license_name')}} </div>
        @endif

        <encodedーinputtext-component
        id="license_name" name="license_name"
        style_class="form-control"
        default_body="{{ $errors->all() ? urldecode( old('license_name') ) : $text_bodys['license_name'] }}"
        maxlength="140"
        placeholder="(株)会社名"
        ></encodedーinputtext-component>

    </label>



    <div class="col-md-6 mx-auto my-5">
        <disabled-button style_class="btn btn-lg btn-warning text-white w-100 shadow"
        btn_text="更新する"></disabled-button>
    </div>

</form>
