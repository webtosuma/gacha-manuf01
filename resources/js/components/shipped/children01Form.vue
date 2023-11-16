<template>
    <div class="">
        <div class="mx-auto" style="max-width:900px;">

            <!-- お届け先選択 -->
            <section class="my-4">
                <h5>お届け先の選択</h5>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" value="" checked>
                    <div class="form-check-label">
                        毎回このお届け先を使う
                    </div>
                </label>
                <ul class="list-group bg-white">
                    <li class="list-group-item">
                        <label class="d-block">
                            <div class="row g-0">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_address_id" value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">
                                        芥川　伸雄 様
                                    </div>
                                    <div class="fw-bold">
                                        113-0033
                                        東京都文京区本郷4丁目16-6
                                        天翔オフィス後楽園507
                                    </div>
                                </div>
                            </div>
                        </label>
                    </li>
                    <li class="list-group-item">
                        <label class="d-block">
                            <div class="row g-0">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_address_id" value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">
                                        芥川　伸雄 様
                                    </div>
                                    <div class="fw-bold">
                                        113-0033
                                        東京都文京区本郷4丁目16-6
                                        天翔オフィス後楽園507
                                    </div>
                                </div>
                            </div>
                        </label>
                    </li>
                    <li class="list-group-item">
                        <label class="d-block">
                            <div class="row g-0">
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_address_id" value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="fw-bold">
                                        芥川　伸雄 様
                                    </div>
                                    <div class="fw-bold">
                                        113-0033
                                        東京都文京区本郷4丁目16-6
                                        天翔オフィス後楽園507
                                    </div>
                                </div>
                            </div>
                        </label>
                    </li>
                    <!-- お問い合わせ先追加 -->
                    <li class="list-group-item">

                        <!-- お届け先の新規登録フォーム -->
                        <u-create-user-address-form
                        :token="token" :r_store="r_store"/>

                    </li>
                </ul>
            </section>

            <!-- 利用ポイント -->
            <section class="my-4">
                <h5>利用ポイント</h5>
                <ul class="list-group bg-white">
                    <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between">
                            <span class="form-text">配送料・手数料：</span>
                            <span>¥0</span>
                        </div>
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span class="">合計利用ポイント：</span>
                            <span class="text-danger">¥0</span>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- 発送する景品 -->
            <section class="my-4">
                <h5>発送する景品</h5>
                <ul class="list-group bg-white">
                    <li class="list-group-item">
                        <div class="row">


                            <div class="col-3 col-md-2 p-0 pe-2">
                                <div class="">
                                    <!-- <ratio-image-component
                                    style_class="ratio ratio-3x4 rounded-3"
                                    url="{{ $user_prize->prize->image_path }}" /> -->
                                </div>
                                <!-- <h6 classs="form-text">{{ $user_prize->prize->name }}</h6> -->
                            </div>

                        </div>
                    </li>
                    <li class="list-group-item text-end">
                        <span class="me-3">合計</span>
                        <span class="fs-3">{{  }}</span>点
                    </li>
                </ul>
            </section>


            <section class="my-5">
                <div class="col-md-8 mx-auto my-3">
                    <button class="btn btn-lg btn-warning text-white w-100"
                    >発送内容を確認する</button>
                </div>
                <div class="col-md-8 mx-auto my-3">
                    <button class="btn btn-lg btn-light border w-100"
                    >発送する景品を変更する</button>
                </div>
            </section>


        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        props: {
            token:{ type: String,  default: '', },
            r_store: { type: [String,Number], default: null },
        },
        data() { return {

            inputs: {
                // name        :'',//宛名
                // tell        :'',//電話番号
                // postal_code :'',//郵便番号
                // todohuken   :'',//住所-都道府県
                // shikuchoson :'',//住所-市町村
                // number      :'',//住所-番地
                name        :'hogehoge',//宛名
                tell        :'09011112222',//電話番号
                postal_code :'1234567',//郵便番号
                todohuken   :'北海道',//住所-都道府県
                shikuchoson :'まち',//住所-市町村
                number      :'１２３',//住所-番地
            },

            errors : {},/* エラー内容 */

            loading: false,/* 通信中 */
            comp: false,   /* 完了 */


            /* オプションデータ */
            form_option : {
                // 都道府県
                todohukens : {
                    1 : '北海道', 2 : '青森県', 3 : '岩手県', 4 : '宮城県', 5 : '秋田県', 6 : '山形県', 7 : '福島県',
                    8 : '茨城県', 9 : '栃木県', 10 : '群馬県', 11 : '埼玉県', 12 : '千葉県', 13 : '東京都', 14 : '神奈川県',
                    15 : '新潟県', 16 : '富山県', 17 : '石川県', 18 : '福井県', 19 : '山梨県', 20 : '長野県', 21 : '岐阜県',
                    22 : '静岡県', 23 : '愛知県', 24 : '三重県', 25 : '滋賀県',
                    26 : '京都府', 27 : '大阪府', 28 : '兵庫県', 29 : '奈良県', 30 : '和歌山県',
                    31 : '鳥取県', 32 : '島根県', 33 : '岡山県', 34 : '広島県', 35 : '山口県',
                    36 : '徳島県', 37 : '香川県', 38 : '愛媛県', 39 : '高知県',
                    40 : '福岡県', 41 : '佐賀県', 42 : '長崎県', 43 : '熊本県', 44 : '大分県', 45 : '宮崎県', 46 : '鹿児島県', 47 : '沖縄県',
                },
            },
        } },
        mounted() {

            /*tokenの保存*/
            this.inputs = {...this.inputs, _token: this.token }

        },
        methods: {


            /* 新規登録*/
            store: function(){
                this.loading = true;// 通信中

                const route = this.r_store; //新規作成
                axios.post( route, this.inputs )
                .then(json => {
                    console.log( json.data );

                    this.comp = true;
                })
                .catch(error => {
                    // alert('データ送信エラーが発生しました。');
                    // console.log( error.response.data );
                    // return;


                    //バリデーション結果の受け取り
                    if( error.response.status == 422 ){
                        console.log( error.response.data.errors );
                        this.errors = error.response.data.errors;
                        this.loading = false;// 通信中終了
                    }
                    //その他のエラー
                    else{ alert('データ送信エラーが発生しました。'); }
                    // console.log( error.response.data );

                });
            },


        },
    }
</script>
