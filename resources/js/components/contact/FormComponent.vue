<template>
    <div>
        <!-- <div>
            <input type="text" v-for="(input, key) in inputs" :key="key" :value="input">
        </div> -->

        <!-- Steps 1-2-3 -->
        <div class="card-body my-5">
            <div class="form-steps-pill mx-auto" style="max-width: 600px;">
                <div class="step-box" :class="{'text-primary':true}">
                    <div class="step_num" :class="{'bg-primary':true}"
                    >1</div>入　力
                </div>
                <i class="bi bi-dash mb-3" :class="{'text-primary':step_num>1}"></i>
                <div class="step-box" :class="{'text-primary':step_num>1}">
                    <div class="step_num" :class="{'bg-primary':step_num>1}"
                    >2</div>確　認
                </div>
                <i class="bi bi-dash mb-3" :class="{'text-primary':step_num>2}"></i>
                <div class="step-box" :class="{'text-primary':step_num>2}">
                    <div class="step_num "  :class="{'bg-primary':step_num>2}"
                    >3</div>完　了
                </div>
            </div>
        </div>


        <!-- step01[ 入力 ] -->
        <section  v-show="step_num===1"
        class="fs-5">

            <p class="text-secondary text-center mb-4" style="font-size:11px;">
                ご不明な点は、下記フォームよりお問い合わせください。<br>
                お問合せ内容の確認後、担当者よりご連絡致します。
            </p>

            <div class="mb-3">
                <label for="input_name" class="form-label fw-bold">
                {{'氏名'}}
                </label><span class="badge bg-danger ms-1">必須</span>
                <input type="text" v-model="inputs.name"
                :class="{'border-danger':errors.name}" name="name"
                class="form-control" id="input_name" placeholder="例) 山田　太郎">
                <!--err-->
                <div class="text-danger" v-if="errors.name">※{{ errors.name[0] }}</div>
            </div>

            <div class="mb-3">
                <label for="input_email" class="form-label fw-bold">
                {{'メールアドレス(半角英数)'}}
                </label><span class="badge bg-danger ms-1">必須</span>
                <input type="email"  v-model="inputs.email"
                :class="{'border-danger':errors.email}"
                class="form-control" id="input_email" name="email"
                placeholder="例) yamada@mail.co.jp">
                <!--err-->
                <div class="text-danger" v-if="errors.email">※{{ errors.email[0] }}</div>
            </div>

            <div class="mb-3">
                <label for="input_tell" class="form-label fw-bold">
                {{'電話番号(半角数字)'}}
                </label><span class="badge bg-danger ms-1">必須</span>
                <input type="tel" v-model="inputs.tell"
                :class="{'border-danger':errors.tell}"
                class="form-control" id="input_tell" placeholder="例) 09012345678" pattern="\d{10,11}">
                <!--err-->
                <div class="text-danger" v-if="errors.tell">※{{ errors.tell[0] }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold" for="input_body">
                {{'お問い合わせ内容'}}
                </label><span class="badge bg-danger ms-1">必須</span>
                <textarea type="text" v-model="inputs.body"
                :class="{'border-danger':errors.body}"
                class="form-control" placeholder="ご自由にご記入ください"
                id="input_body" style="height:10rem;"></textarea>
                <!--err-->
                <div class="text-danger" v-if="errors.body">※{{ errors.body[0] }}</div>
            </div>



            <div class="card border-light  mt-5">
                <div class="card-body text-md-center">
                    <h5 class="card-title fw-bold  text-center">個人情報の取り扱いについて</h5>
                    <p class="card-text text-center" style="font-size:11px;">
                        <a href="#" @click.prevent="windowOpen(props.r_privacy_policy)"
                        >プライバシーポリシー</a>をご確認ください。<br>
                        同意いただけた場合のみ「同意する」にチェックを入れ、<br>
                        確認画面へお進みください。
                    </p>
                    <div class="mt-3 form-check  text-center">
                        <div class="d-inline-block">
                            <input v-model="inputs.agree"
                            type="checkbox" class="form-check-input" id="input_agree"
                            >
                            <label class="form-check-label" for="input_agree">同意する</label>
                            <span class="badge bg-danger ms-1">必須</span>
                        </div>
                    </div>
                    <!--err-->
                    <div class="text-danger" v-if="errors.agree">※{{ errors.agree[0] }}</div>
                </div>
            </div>

            <div class="form_group my-5">
                <div class="col-sm-8 mb-3 mx-auto">
                    <disabled-button-component
                    style_class="btn btn-lg btn-primary text-white w-100"
                    @btn-click="step01()"
                    :disabled="loading" btn_text="入力内容の確認" />
                </div>
            </div>

        </section>

        <!-- step02[ 確認 ] -->
        <section v-show="step_num===2"
        class="fs-5">

            <!-- ご入力内容 -->
            <h5 class="mb-3">ご入力内容の確認をお願いします。</h5>

            <div class="card w-100 mb-5 bg-white">
                <div class="card-body">

                    <div class="row mb-3">
                        <p class="col-12"><strong>氏名：</strong></p>
                        <p class="col-12">{{ inputs['name'] }}</p>
                    </div>

                    <div class="row mb-3">
                        <p class="col-12"><strong>メールアドレス：</strong></p>
                        <p class="col-12">{{ inputs['email'] }}</p>
                    </div>

                    <div class="row mb-3">
                        <p class="col-12"><strong>電話番号：</strong></p>
                        <p class="col-12">{{ inputs['tell'] }}</p>
                    </div>

                    <div class="row mb-3">
                        <p class="col-12"><strong>お問い合わせ内容：</strong></p>
                        <p class="col-12" v-html="inputs.body.replace(/\r?\n/g, '<br>')"></p>
                    </div>

                </div>
            </div>



            <!-- 送信ボタン -->
            <div class="form_group mb-5">
                <div class="col-sm-8 mb-3 mx-auto">
                    <disabled-button-component
                    style_class="btn btn-primary btn-arrow btn-lg text-white fs-5 w-100"
                    @btn-click="step02()"
                    :disabled="loading" btn_text="確定" />
                </div>
                <div class="col-sm-8 mb-3 mx-auto">
                    <button class="btn btn-outline-secondary btn-arrow btn-lg fs-5 w-100" type="button"
                    @click="subStepNum()"
                    >戻る</button>
                </div>
            </div>

        </section>


        <!-- step03[ 完了 ] -->
        <section v-show="step_num===3"
         class="text-center">

            <h5 class="mb-3">お問い合わせ内容を送信しました。</h5>

            <div class="cardd w-100 mb-5">
                <div class="p-3">お問い合わせ頂き、ありがとうございまいた。</div>
            </div>
            <div class="form_group my-5">
                <div class="col-sm-6 mb-3 mx-auto">
                    <a :href="r_top" class="btn btn-light border w-100"
                    onclick="stopOnbeforeunload()">{{ "Topに戻る" }}</a>
                </div>
            </div>

        </section>



    </div>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, defineProps, defineEmits } from "vue";

    const props = defineProps({
        token: { type: String, default: '', },
        r_api_validation: { type: String, default: '', },
        r_api_completion: { type: String, default: '', },
        r_privacy_policy:{ type: String, default: '', },
        r_top:{ type: String, default: '', },
    });


    const loading  = ref(false);/* 通信中 */
    const step_num = ref(1);    /* 表示中ステップ番号 */
    const errors   = ref({});   /* エラー内容 length */

    /* 入力内容 */
    const inputs= ref({
        _token : '',
        name : '', email: '',  tell : '', body : '',
        agree: false, // プライバシーポリシー同意

        // name : 'ホゲ', //氏名
        // email: 't.sakai@tosuma.ltd', //メールアドレス(半角英数)
        // tell : '09011112222', //電話番号(半角数字)
        // body : 'hoge', //お問い合わせ内容
        // agree: true, // プライバシーポリシー同意
    });


    onMounted(()=>{
        inputs.value._token = props.token;//tokenのセット
    });




    /* ステップ01完了 */
    const step01 = ()=>{

        loading.value = true;// 通信中
        inputs.value.agree = inputs.value.agree ? 'on' : '';//同意チェック

        const route = props.r_api_validation;
        axios.post( route, inputs.value )
        .then(json => {
            // console.log(json.data);

            loading.value = false;// 通信中
            errors.value  = {};
            addStepNum()
        })
        .catch(error => {

            //バリデーション結果の受け取り
            if( error.response.status == 422 ){
                // console.log( error.response.data.errors );
                errors.value = error.response.data.errors;
                loading.value = false;// 通信中
            }
            //その他のエラー
            else{ alert('データ送信エラーが発生しました。'); }
            // console.log( error.response.data );

        });
    };


    /* ステップ02完了 */
    const step02 = ()=>{

        loading.value = true;// 通信中

        const route = props.r_api_completion;
        axios.post( route, inputs.value )
        .then(json => {
            // console.log(json.data);

            loading.value = false;// 通信中
            addStepNum();
        })
        .catch(error => {

            alert('データ送信エラーが発生しました。');
            // console.log( error.response.data );

        });
    };


    /* 次へメソッド */
    const addStepNum = ()=>{
        step_num.value ++;
        window.scroll({top: 0, behavior: 'smooth'});
    };


    /* 前へメソッド */
    const subStepNum = ()=>{
        step_num.value --;
        window.scroll({top: 0, behavior: 'smooth'});
    };


    /* 別タブで開く */
    const windowOpen = (route)=>{ window.open( route ); }


</script>
