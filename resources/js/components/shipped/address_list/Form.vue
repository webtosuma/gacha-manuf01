<template>
    <div class="">


        <div v-if="!comp">
            <div class="form-text">
                <span class="text-danger">*</span>入浴必須
            </div>

            <label class="mb-3 d-block">
                <div class="form-label">
                    氏名（フルネーム）
                    <span class="text-danger">*</span>
                </div>

                <input v-model="inputs.name" name="name"
                :disabled="loading"
                type="text" class="form-control" placeholder="">
                <!-- error message -->
                <div v-if="errors.name" class="text-danger" role="alert">※{{ errors.name[0] }}</div>
            </label>

            <label v-if="use_size!=0" class="mb-3 d-block">
                <div class="form-label">
                    希望の靴サイズ（cm）
                </div>

                <select v-model="inputs.size" name="size" class="form-select" :disabled="loading">
                    <option value="">選択してください</option>
                    <option  v-for="(size, key) in form_option.sizes" :key="key"
                    :value="size">{{ size }}</option>
                </select>

                <div v-if="errors.size" class="text-danger" role="alert">※{{ errors.size[0] }}</div>
            </label>

            <label class="mb-3 d-block">
                <div class="form-label">メールアドレス</div>
                <input v-model="inputs.email" name="email"
                :disabled="loading"
                type="email" class="form-control" placeholder="例：example@email.co.com">

                <div v-if="errors.email" class="text-danger" role="alert">※{{ errors.email[0] }}</div>
            </label>

            <label class="mb-3 d-block">
                <div class="form-label">
                    電話番号（半角数字のみ）
                    <span class="text-danger">*</span>
                </div>

                <input v-model="inputs.tell" name="tell"
                :disabled="loading"
                type="text" class="form-control" placeholder="例：0000000">
                <!-- error message -->
                <div v-if="errors.tell" class="text-danger" role="alert">※{{ errors.tell[0] }}</div>
            </label>

            <label class="mb-3 d-block">
                <div class="form-label">郵便番号（7桁の半角数字のみ）</div>
                <div class="col-6">
                    <input v-model="inputs.postal_code" name="postal_code"
                    :disabled="loading"
                    type="text" class="form-control"  placeholder="例：0001234">
                </div>
                <!-- error message -->
                <div v-if="errors.postal_code" class="text-danger" role="alert">※{{ errors.postal_code[0] }}</div>
            </label>


            <label class="mb-3 d-block">
                <div class="form-label">
                    都道府県
                    <span class="text-danger">*</span>
                </div>
                <select v-model="inputs.todohuken" name="todohuken" class="form-select" :disabled="loading">
                    <option value="">選択してください</option>
                    <option  v-for="(todohuken, key) in form_option.todohukens" :key="key"
                    :value="todohuken">{{ todohuken }}</option>
                </select>
                <!-- error message -->
                <div v-if="errors.todohuken" class="text-danger" role="alert">※{{ errors.todohuken[0] }}</div>
            </label>


            <label class="mb-3 d-block">
                <div class="form-label">
                    市町村
                    <span class="text-danger">*</span>
                </div>
                <input v-model="inputs.shikuchoson" name="shikuchoson"
                :disabled="loading"
                type="text" class="form-control" placeholder="例：〇〇市〇〇街">
                <!-- error message -->
                <div v-if="errors.shikuchoson" class="text-danger" role="alert">※{{ errors.shikuchoson[0] }}</div>
            </label>


            <label class="mb-3 d-block">
                <div class="form-label">
                    丁目・番地・号・建物名・部屋番号
                    <span class="text-danger">*</span>
                </div>
                <input v-model="inputs.number" name="number"
                :disabled="loading"
                type="text" class="form-control" placeholder="例：1-2-3 〇〇ビル 123">
                <!-- error message -->
                <div v-if="errors.number" class="text-danger" role="alert">※{{ errors.number[0] }}</div>
            </label>



            <label class="mb-3 d-block">
                <div class="form-label">備考欄</div>
                <textarea  v-model="inputs.remarks"
                name="remarks"
                class="form-control"
                id="input_body" style="height:6rem;"></textarea>

                <!-- error message -->
                <div v-if="errors.remarks" class="text-danger" role="alert">※{{ errors.remarks[0] }}</div>
            </label>



            <div v-if="!edit_address" class="col-md-8 mx-auto mt-5 mb-4">
                <disabled-button-component @btn-click="store()"
                style_class="btn btn-lg btn-success text-white rounded-pill w-100"
                type="button"
                :disabled="loading" btn_text="登録" />
            </div>
            <div v-else class="col-md-8 mx-auto mt-5 mb-4">
                <div class="d-flex flex-column gap-3">

                    <disabled-button-component @btn-click="edit()"
                    style_class="btn btn-lg btn-warning text-white rounded-pill w-100"
                    type="button"
                    :disabled="loading" btn_text="更新" />

                    <a @click.prevent="goBack()"
                    class="btn btn-lg btn-light border rounded-pill w-100"
                    >戻る</a>

                </div>
            </div>



        </div>
        <div v-else>
            <div class="mb-3 text-center fs-3">
                お届け先情報を追加しました。
            </div>
            <div class="col-md-8 mx-auto my-5">
                <button type="button"
                @click="close"
                data-bs-toggle="collapse" data-bs-target="#collapseCreateAddress" aria-expanded="false" aria-controls="collapseCreateAddress"
                class="btn border w-100">閉じる</button>
            </div>
        </div>



    </div>
</template>

<script setup>
    import { ref, watch, onMounted, defineEmits } from 'vue';
    import axios from 'axios';


    const props = defineProps({

        token:{ type: String,  default: '', },
        r_api_store:  { type: String, default: '' },
        r_api_update: { type: String, default: '' },
        use_size:     { type: [String, Number], default: '0' },
        default_email:{ type: String, default: '' },
        edit_address: { type: Object, default: null },//発送先の修正情報

    });

    const emit = defineEmits(['child-click']);


    /* データの状態 */
    const inputs = ref({});
    const inputs_default = ref({
        name        :'',//宛名
        size        :'',//靴のサイズ
        email       :'',//メールアドレス
        tell        :'',//電話番号
        postal_code :'',//郵便番号
        todohuken   :'',//住所-都道府県
        shikuchoson :'',//住所-市町村
        number      :'',//住所-番地
        remarks     :'',//備考欄
    });

    const nextPageUrl = ref('get-list');   /* 次のデータの読み込みURL */

    const errors  = ref({});/* エラー内容 */
    const loading = ref(false);/* 通信中 */
    const comp    = ref(false);/* 完了 */
    const form    = ref( document.getElementById('edit-form') );
    const btn = document.getElementById('submit-button');


    /* オプションデータ */
    const form_option = ref({
        //靴のサイズ
        sizes : [
            '24.0cm','24.5cm','25.0cm','25.5cm','26.0cm','26.5cm','27.0cm','27.5cm','28.0cm','28.5cm','29.0cm','29.5cm','30.0cm'
        ],
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
    });


    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {

        /*データの初期化*/
        inputs.value = props.edit_address || inputs_default.value;

        inputs.value.email   =  inputs.value.email || props.default_email;
        inputs.value.remarks =  inputs.value.remarks_text || null;

    });


    /* データ取得 */
    const store = async (route = props.r_api_store) => {

        const post_inputs = Object.assign( {}, {...inputs.value, _token: props.token } );

        const encode_list = ['name','remarks'];
        encode_list.forEach(encode_param => {
            post_inputs[encode_param] = uriEncoded(post_inputs[encode_param]);
        });

        try {
            loading.value = true;
            const response = await axios.post(route, post_inputs);
            const json = response;

            console.log( json.data );
            comp.value    = true;
            loading.value = false;// 通信中

            /** お届け先一覧の更新 */
            getList();


        } catch (error) {

            // console.error(error.response?.data);

            //バリデーション結果の受け取り
            if( error.response.status == 422 ){
                // console.log( error.response.data.errors );
                errors.value  = error.response.data.errors;
                loading.value = false;// 通信中終了

            }

            else if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };


    /* 編集(バリデーションチェック) */
    const edit = async (route = props.r_api_update) => {

        const post_inputs = Object.assign( {}, {...inputs.value, _token: props.token } );

        const encode_list = ['name','remarks'];
        encode_list.forEach(encode_param => {
            post_inputs[encode_param] = uriEncoded(post_inputs[encode_param]);
        });

        try {
            loading.value = true;
            const response = await axios.patch(route, post_inputs);
            const json = response;

            // console.log( json.data );

            // form.value.submit();
            btn.click();


        } catch (error) {

            console.error(error.response?.data);

            //バリデーション結果の受け取り
            if( error.response.status == 422 ){
                // console.log( error.response.data.errors );
                errors.value  = error.response.data.errors;
                loading.value = false;// 通信中終了

            }

            else if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };




    /** モーダルを閉じる->初期化 */
    const close = () => {
        comp.value   = false;
        inputs.value = inputs_default.value;
    };


    /** お届け先一覧の更新 */
    const getList = ()=>{ emit('get-list'); };


    /* デフォルト文字列のエンコード処理 */
    const uriEncoded = (body) => { return encodeURIComponent(body); };

    /**　戻るボタン */
    const goBack = ()=>{ window.history.back(); };


</script>
