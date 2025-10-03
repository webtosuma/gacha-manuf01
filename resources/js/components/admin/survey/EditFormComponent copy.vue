<template>
    <div class="">

        <loading-cover-component :loading="loading" />


        <!-- トーストポップアップ -->
        <div id="toast_container" class="position-fixed bottom-0 end-0 p-2" style="z-index:1050;">
            <div v-for="(message, key) in messages" :key="key"
            class="toast fade show mb-1 fade-in-message" role="alert" aria-live="assertive" aria-atomic="true" >
                <div class="toast-header bg-dark text-white">
                    <strong class="me-auto">{{ message }}</strong>
                    <button type="button" class="btn px-1 py-0 text-white fs-5" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        </div>


        {{ r_admin_api_update }}
        <!-- アンケート基本情報 -->
        <section>

            <div class="row">
                <div class="col-lg-6">

                    <!--新規登録 CONTAINER-->
                    <div v-if=" !survey.id "
                    class="d-flex align-items-center justify-content-center p-3 bg-light rounded-4"
                    style="min-height: 300px;">

                        <div class="col-md-8">
                            <h5 class="fw-bold">アンケートの新規登録</h5>
                            <p class="text-secondary"
                            >「タイトル」・「説明文」など、新規登録するアンケートの基本情報を登録してください。</p>

                            <button
                            class="btn btn-primary text-white"
                            data-bs-toggle="offcanvas" data-bs-target="#ocSurvey" aria-controls="offcanvasRight"
                            type="button"
                            ><i class="bi bi-plus-lg me-2"></i>新規登録</button>
                        </div>

                    </div>
                    <!--編集 CONTAINER-->
                    <div v-else
                    class="d-flex align-items-center justify-content-center p-3 bg-light rounded-4"
                    style="min-height: 300px;">

                        <div class="w-100">
                            <h6 class="fw-bold">アンケート基本情報</h6>

                            <h4>{{ survey.title }}</h4>

                            <p class="mb-4 border-top pt-2"
                            >{{ survey.resume_text }}</p>

                            <button
                            class="btn btn-warning text-white"
                            data-bs-toggle="offcanvas" data-bs-target="#ocSurvey" aria-controls="offcanvasRight"
                            type="button"
                            ><i class="bi bi-pencil me-2"></i>編集</button>

                        </div>

                    </div>




                    <div class="my-5 d-none">

                        <div class="form-text mb-3">
                            <span class="text-danger">＊</span>入力必須
                        </div>


                        <!--タイトル(title resume_text)-->
                        <label class="d-block mb-4">
                            <div class="form-label">
                                タイトル
                                <span class="text-danger">＊</span>
                            </div>

                            <input v-model="survey.title"
                            type="text" class="form-control">

                            <!--error message-->
                            <div v-if="errors?.title" class="text-danger">{{ errors.title[0] }}</div>
                            {{ survey.encode_title }}
                        </label>


                        <!--本文(resume_text encode_resume_text)-->
                        <label class="d-block mb-4">
                            <div class="form-label">
                                説明文
                            </div>

                            <textarea v-model="survey.resume_text"
                            class="form-control" style="height:10rem;"
                            placeholder="アンケートの説明文を入力してください。"
                            ></textarea>


                            <!--error message-->
                            <div v-if="errors?.resume_text" class="text-danger">{{ errors.resume_text[0] }}</div>
                            {{ survey.encode_resume_text }}
                        </label>


                        <div class="row">
                            <div class="col">
                                <button class="btn btn-light border w-100" type="button"
                                >閉じる</button>
                            </div>
                            <div class="col">
                                <button @click="updateSurvey()"
                                class="btn text-white w-100" type="button"
                                :class=" !survey.id?'btn-primary':'btn-warning' "
                                >{{ !survey.id?'登録':'更新' }}</button>
                            </div>
                        </div>

                    </div>




                </div>
            </div>

            <!--offcanvas-->
            <div class="offcanvas offcanvas-end" tabindex="-1"
            id="ocSurvey" aria-labelledby="ocSurveyLabel">
                <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="ocSurveyLabel">アンケート基本情報</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">

                    <div class="form-text mb-3">
                        <span class="text-danger">＊</span>入力必須
                    </div>


                    <!--タイトル(title resume_text)-->
                    <label class="d-block mb-4">
                        <div class="form-label">
                            タイトル
                            <span class="text-danger">＊</span>
                        </div>

                        <input v-model="survey.title"
                        type="text" class="form-control">

                        <!--error message-->
                        <div v-if="errors?.title" class="text-danger">{{ errors.title[0] }}</div>
                    </label>


                    <!--本文(resume_text encode_resume_text)-->
                    <label class="d-block mb-4">
                        <div class="form-label">
                            説明文
                        </div>

                        <textarea v-model="survey.resume_text"
                        class="form-control" style="height:10rem;"
                        placeholder="アンケートの説明文を入力してください。"
                        ></textarea>


                        <!--error message-->
                        <div v-if="errors?.resume_text" class="text-danger">{{ errors.resume_text[0] }}</div>
                    </label>


                    <div class="row">
                        <div class="col">
                            <button class="btn btn-light border w-100"
                            type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#ocSurvey" aria-controls="offcanvasRight"
                            >閉じる</button>
                        </div>
                        <div class="col">
                            <button @click="updateSurvey()"
                            class="btn text-white w-100"
                            type="button"
                            :class=" !survey.id?'btn-primary':'btn-warning' "
                            >{{ !survey.id?'登録':'更新' }}</button>
                        </div>
                    </div>

                </div>
            </div>

        </section>


    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_show:  { type: String, default: '' },
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */
    const messages    = ref([]);   /* ポップアップメッセージ */

    const survey = ref({});           //アンケート基本情報
    const survey_questions = ref({}); //アンケート・問い
    const errors = ref({});           //エラー

    const r_admin_api_update = ref('');//[ルーティングAPI]アンケートデータ更新

    /* 入力データ */
    // const inputs = ref({
    //     _token: props.token,
    //     survey: {},
    // });


    /* 監視 */
    watch(() => survey.value.title,  () => {
        survey.value.encode_title = encodeURIComponent( survey.value.title );
    });
    watch(() => survey.value.resume_text,  () => {
        survey.value.encode_resume_text = encodeURIComponent( survey.value.resume_text );
    });


    /* 初回データ取得 */
    onMounted(() => { getSurvey(); });


    /* アンケートデータ取得 */
    const getSurvey = async () => {
        loading.value = true;
        const route  = props.r_api_show;
        const inputs = { _token: props.token, }
        try {
            const response = await axios.post(route, inputs);
            // console.log(response.data);

            survey.value = response.data['survey'];
            survey_questions.value = response.data['survey_questions'] || {};
            r_admin_api_update.value = response.data['survey']['r_admin_api_update'] || '';

            loading.value = false;

        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };



    /* アンケートデータ更新 */
    const updateSurvey = async () => {
        loading.value = true;
        errors.value  = null;
        const route  = r_admin_api_update.value;
        const inputs = {
            _token: props.token,
             ...survey.value
        }
        const message_type = !survey.value.id?'survey.post':'survey.update';
        try {
            const response = await axios.post(route, inputs);
            // console.log(response.data);
            // console.log('登録');

            survey.value = response.data['survey'];
            r_admin_api_update.value = response.data['survey']['r_admin_api_update'] || '';

            loading.value = false;

            /* ポップアップメッセージのセット */
            setMessage( message_type );



        } catch (error) {
            if(error.response.data.errors){
                /* バリデーションエラー */
                errors.value = error.response.data.errors;
                loading.value = false;
            }
            else if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
            console.log(error.response);

        }
    };



    /* ポップアップメッセージのセット */
    const setMessage = (key) => {

        // ポップアップメッセージ
        switch (key) {
            case 'survey.post':
                messages.value.push('アンケートの基本情報を登録しました。');
                break;

            case 'survey.update':
                messages.value.push('アンケートの基本情報を更新しました。');
                break;

            default:
                messages.value.push('テストメッセージ');
                break;
            //
        }
    };

</script>
<style scoped>
    @keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px); /* 50px下から */
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }

    .fade-in-message {
        opacity: 0; /* 初期状態を透明に */
        animation: fadeInUp .8s ease-out forwards;
    }
</style>
