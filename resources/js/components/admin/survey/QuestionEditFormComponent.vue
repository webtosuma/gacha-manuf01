<template>
    <div class="">

        {{ inputs }}
        <div class="mt-3">
            <button class="btn py-3 btn-outline-primary w-100" type="button"
            data-bs-toggle="offcanvas" :data-bs-target="'#'+'surveyQuestion'" aria-controls="offcanvasRight"
            ><i class="bi bi-plus-lg me-2"></i>問を追加</button>
        </div>

        <!--offcanvas-->
        <div class="offcanvas offcanvas-end" tabindex="-1"
        :id="'surveyQuestion'"
        :aria-labelledby="'surveyQuestion'+'Label'">
            <div class="offcanvas-header">
                    <h5 class="offcanvas-title"
                    :id="'surveyQuestion'+'Label'"
                    >問い情報</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

                <div class="form-text mb-3">
                    <span class="text-danger">＊</span>入力必須
                </div>


                <!--問い文章(body_text)-->
                <label class="d-block mb-4">
                    <div class="form-label">
                        問い文章
                    </div>

                    <textarea v-model="inputs.body_text"
                    class="form-control" style="height:10rem;"
                    placeholder="アンケートの問い文章を入力してください。"
                    ></textarea>


                    <!--error message-->
                    <!-- <div v-if="errors?.body_text" class="text-danger">{{ errors.body_text[0] }}</div> -->
                </label>


                <!--問いの種類(type)-->
                <label class="d-block mb-4">
                    <div class="form-label">
                        問いの種類
                    </div>

                        <select
                        v-model="inputs.type"
                        class="form-select">
                            <option value="">選択してください</option>

                            <option v-for="( label, key ) in select_question_types" :key="key"
                            :value="key"
                            >{{label}}</option>
                        </select>

                    <!--error message-->
                    <!-- <div v-if="errors?.type" class="text-danger">{{ errors.type[0] }}</div> -->
                </label>



                <!--回答選択肢(options_array)-->
                <div class="d-block mb-4">
                    <div class="form-label">
                        回答選択肢
                    </div>

                    <!--値一覧-->
                    <div v-for="(option, key) in inputs.options_array" :key="key"
                    class="row mb-2 g-2 align-items-center">
                        <div class="col">
                            <div class="border px-3 py-1  rounded">{{ option }}</div>
                        </div>
                        <div class="col-auto">
                            <button
                            @click="removeOption(option)"
                            class="btn btn-sm btn-light border" type="button">x</button>
                        </div>
                    </div>


                    <!--新規入力-->
                    <div class="mt-3">
                        <div class="input-group mb-">
                            <input v-model="inputs.new_option"
                            class="form-control col-8" type="text" placeholder="追加する回答選択技を入力">
                            <button type="button" class="btn btn-light border">追加</button>
                        </div>
                        <div class="form-text mb-3">*合計100文字以内</div>
                    </div>

                    <!--error message-->
                    <!-- <div v-if="errors?.options_array" class="text-danger">{{ errors.options_array[0] }}</div> -->
                </div>



                <div class="row mt-5">
                    <div class="col">
                        <button class="btn btn-light border w-100"
                        type="button"
                        data-bs-dismiss="offcanvas" aria-label="Close"
                        >閉じる</button>
                    </div>
                    <div class="col">
                        <button
                        class="btn btn-primary text-white w-100"
                        type="button"
                        >登録</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        survey_id:   { type: [String,Number], default: 0 },
        question:    { type: [Object,Array], default: {} },
        select_question_types: { type: [Object,Array], default: null },///問いの種類
        r_api_list:  { type: String, default: '' },
    });


    /* データの状態 */
    const loading     = ref(true); /* 読み込み中 */

    /* 入力データ */
    const inputs = ref({
        _token:    props.token,
        new_option: 'new',
        // survey_id: props.question.survey_id||null,
        // id:        props.question.id||null,
        // body_text: '',
        // type: '',
    });

    /* 監視 */
    // watch(data, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        /* 問いデータのコピー */
        inputs.value = { ...inputs.value, ...props.question, };
    });



    /* 回答選択肢の削除 */
    const removeOption = (value)=>{
        inputs.value.options_array = inputs.value.options_array.filter(option => option !== value);
    }

</script>
