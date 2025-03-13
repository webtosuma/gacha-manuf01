<template>
    <div class="row g-3">

        <!-- <div class="col-12">{{  inputs }}</div> -->


        <!-- side -->
        <div class="col-md-auto">
            <div class="position-sticky" style="top: 2rem; ">


                <loading-cover-component :loading="loading" />


                <div class="row flex-md-column g-2 mb-2">
                    <div class="col">
                        <select
                        v-model="inputs.admin_id"
                        class="form-select">
                            <option value="">サイト管理者</option>

                            <option v-for="( admin, key ) in admins" :key="key"
                            :value="admin.id"
                            selected>{{admin.name}}</option>
                        </select>
                    </div>
                    <div class="col">
                        <select
                        v-model="inputs.type_id"
                        class="form-select">
                            <option value="">履歴の種類</option>

                            <option v-for="( type, key ) in types" :key="key"
                            :value="type.id"
                            selected>{{type.label}}</option>
                        </select>
                    </div>
                    <div class="col">
                        <select
                        v-model="inputs.month"
                        class="form-select">
                            <option value="">年月</option>

                            <option v-for="( month, key ) in months" :key="key"
                            :value="month.date_stanp"
                            selected>{{month.format}}</option>
                        </select>
                    </div>
                </div>


            </div>
        </div>
        <!-- main -->
        <div class="col">

            <div v-if="show_checkbox==true"
            class="row g-0 align-items-center justify-content-between p-2">
                <div class="col">
                    <label  class="form-check">
                        <input v-model="allChecked" class="form-check-input p-2" type="checkbox" @click="toggleAllChecks">
                        <div class="form-check-label">{{ allChecked ? 'すべて解除' : 'すべてチェック' }}</div>
                    </label>
                </div>
                <div class="col-auto">
                    チェックしたものを：
                </div>
                <div class="col-auto">
                    <button @click="destoryData()"
                    :disabled="!inputs.log_ids.length"
                    class="btn btn-sm border btn-light text-danger">削除</button>
                </div>
            </div>
            <table class="table bg-white border-top">
                <tbody>
                    <tr>
                        <td v-if="!logs.length" class="text-center text-secondary border-0 pt-4 pb-3">
                            *操作履歴はありません
                        </td>
                    </tr>
                    <tr v-for="(log, key) in logs" :key="key">
                        <td v-if="show_checkbox==true" class="text-center" style="width:2rem;">
                            <input v-model="inputs.log_ids"
                            :value="log.id"
                            class="form-check-input p-2"
                            type="checkbox" >
                        </td>
                        <td class="py-2">
                            <!--履歴の種類-->
                            <div class="fw-bold">{{ log.type_label }}</div>


                            <!--履歴の種類-->
                            <div v-for="(type, key) in type_ids" :key="key">
                                <div v-if="log[type]">
                                    <a v-if="!log[type].deleted_at" :href="log.type_route"
                                    >{{log[type].name || log[type].title}}</a>
                                    <!-- <a v-if="!log[type].deleted_at" :href="log.type_route">{{log[type].name}}</a> -->
                                    <div v-else
                                    class="">{{log[type].name}} <span class="text-danger">削除済み</span></div>
                                </div>
                            </div>

                        </td>
                        <!--サイト管理者名-->
                        <td class="py-2">{{ log.admin.name}}</td>
                        <!--日時-->
                        <td class="py-2">{{ log.created_at_format}}</td>
                    </tr>
                </tbody>
            </table>


            <div v-show="nextPageUrl" class="mt-3">
                <a @click.prevent="getData( nextPageUrl )"
                class="btn btn-light border"
                href="">もっと読み込む</a>
            </div>


        </div>
    </div>
</template>


 <script setup>
    import { ref, computed, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({

        token:        { type: String, default: '' },
        r_api_list:   { type: String, default: '' }, // カテゴリーcode
        use_mail:     { type: String, default: null }, // メールの利用
        is_published: { type: [String, Number], default: 1 }, // ページ読み込み時の公開状態
        show_checkbox:{ type: String, default: '1' },

    });

    /* データの状態 */
    const loading = ref(true);    /* 読み込み中 */

    const logs   = ref([]); /* 一覧 */
    const months = ref([]); /* 年月絞り込み */
    const admins = ref([]); /* サイト管理者 */
    const types  = ref([]); /* 履歴の種類 */
    const type_ids = ref(['gacha', 'category', 'prize', 'movie', 'infomation', 'user',]);
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    const inputs = ref({
        _token:   props.token,
        month:    '',
        admin_id: '',
        type_id:  '',
        log_ids:  [],
        destory:  false,
    });

    const log_ids  = ref([]); /* チェック */


    /* [コンピューティッド]すべてチェック */
    const allChecked = computed({
        get: () => inputs.value.log_ids.length === logs.value.length,
        set: (value) => {
            if (value) {
                // すべて選択
                inputs.value.log_ids = logs.value.map(log => log.id);
            } else {
                // すべて解除
                inputs.value.log_ids = [];
            }
        }
    });


    /* 監視 */
    watch(() => inputs.value.month,    () => getData());
    watch(() => inputs.value.admin_id, () => getData());
    watch(() => inputs.value.type_id,  () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {

        loading.value = true/* 読み込み */


        try {
            const response = await axios.post(route, inputs.value );
            const paginate = response.data['logs'];

            logs.value =
            route === props.r_api_list ? paginate.data : [...logs.value, ...paginate.data];

            /* 年月絞り込み */
            months.value = response.data.months;
            /* サイト管理者 */
            admins.value = response.data.admins;
            /* 履歴の種類 */
            types.value  = response.data.types;
            /* チェックデータのリセット */
            inputs.value.log_ids = [];


            loading.value = false;/* 読み込み */
            inputs.value.destory = false;

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

        } catch (error) {
            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        }

    };


    /* 選択したデータを削除 */
    const destoryData = () => {
        inputs.value.destory = true;
        getData();
    };


    /* すべてチェック */
    const toggleAllChecks = () => {
        if (allChecked.value) {
            inputs.value.log_ids = [];
        } else {
            inputs.value.log_ids = logs.value.map(log => log.id);
        }
    };


</script>


