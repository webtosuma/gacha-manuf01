<template> 
    <form method="POST" :action="r_action"
    class="w-100 text-center" style="min-height:12rem;">

        <!--入力値-->
        <input v-for="(value,name) in inputs" :key="name"
        type="hidden" :name="name" :value="value">


        <loading-cover-component :loading="loading" />


        <!--エラーメッセージ-->
        <div v-if="err_message"
        class="text-danger mb-3 text-center">※{{ err_message }}</div>



        <!-- page00 認証凍結 -->
        <div v-if="page==0" class="fs-3"></div>
        <!-- page01 パスワード認証 -->
        <div v-if="page===1">
            <!--メールアドレス-->
            <div class="form-floating mb-3">
                <input v-model="inputs.email"
                type="email" class="form-control" id="floatingInput" autofocus
                name="email">
                <label for="floatingInput">メールアドレス</label>
            </div>
            <!--パスワード-->
            <div class="form-floating mb-3">
                <input v-model="inputs.password"
                type="password" class="form-control" id="floatingPassword"
                name="password">
                <label for="floatingPassword">パスワード</label>
            </div>

            <div class="mt-5 my-3">
                <button @click="checkPassword()"
                class="w-100 btn btn-lg text-white"
                :class="is_admin ? ' btn-info' : ' btn-primary'"
                type="button">ログイン</button>
            </div>

            <a :href="r_pass_request" class="text-decoration-none"
            >パスワードをお忘れの方はこちら</a>

        </div>
        <!-- page02 TFAキー認証 -->
        <div v-if="page===2" class="text-start">

            <p class="bg-light text-dark p-3 rounded"
            :class="is_admin ? 'bg-info-subtle' : 'bg-primary-subtle'"
            >
                入力されたメールアドレス宛にメールをお送りしました。<br>
                メールに記載された8ケタの認証キーを入力してください。
            </p>

            <label>{{ '認証キー（半角英数字）' }}</label>

            <div class="d-flex gap-3">
                <input v-model="inputs.tfa_key"
                type="text" class="form-control w-50"
                autofocus
                >

                <input type="button"
                @click="checkPassword"
                value="認証メール再送" class="btn btn-outline-secondary"/>
            </div>

            <div class="mt-5 my-3">
                <button @click="checkTfaKey()"
                class="w-100 btn btn-lg text-white"
                :class="is_admin ? ' btn-info' : ' btn-primary'"
                type="button">次へ</button>
            </div>

        </div>
        <!-- page03 認証完了 -->
        <div v-if="page===3">

            <div class="mt-5 my-3">

                <h5>認証が完了しました。</h5>

                <div class="mt-5 my-3">
                    <button
                    class="w-100 btn btn-lg text-white"
                    :class="is_admin ? ' btn-info' : ' btn-primary'"
                    type="submit">ログインする</button>
                </div>
            </div>

        </div>


    </form>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token     :{ type: String, default: '' },
        email     :{ type: String, default: '' },
        password  :{ type: String, default: '' },
        is_admin  :{ type: [String,Number,Boolean], default: 0 },//Admin用か否か

        r_api_pass    :{ type: String, default: '' },//[ルーティング]パスワード認証
        r_api_tfa_key :{ type: String, default: '' },//[ルーティング]tfaキー認証
        r_pass_request:{ type: String, default: '' },
        r_action      :{ type: String, default: '' },
    });


    /* データの状態 */
    const loading = ref(false); /* 読み込み中 */

    const page      = ref(1); /* 表示中ページ */

    const inputs    = ref({
        _token:   props.token,
        email:    props.email,
        password: props.password,
        tfa_key:  '',
    });

    const err_message = ref('');/* エラーメッセージ */

    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => { });


    /* パスワード認証 */
    const checkPassword = async () => {

        loading.value = true;
        const route   = props.r_api_pass;
        try {

            const response = await axios.post(route, inputs.value);
            // console.log(response.data.not_tfa);

            if( response.data.not_tfa ) //二段階認証を利用しない
            {
                //フォーム送信
                document.querySelector('form')?.submit();
                return;
            }

            if( response.data.valid ){ page.value = 2; }       //認証成功!

            if( response.data.max_failures ){ page.value = 0; }//認証凍結


            err_message.value = response.data.err_message;//エラーメッセージ
            loading.value = false;


        } catch (error) {

            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };



    /* TFAキー認証 */
    const checkTfaKey = async () => {

        loading.value = true;
        const route   = props.r_api_tfa_key;
        try {
            const response = await axios.post(route, inputs.value);

            if( response.data.valid ){ page.value = 3; }//認証成功

            err_message.value = response.data.err_message;//エラーメッセージ
            loading.value = false;


        } catch (error) {

            // console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    }




</script>
