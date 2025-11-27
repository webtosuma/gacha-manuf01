<template>
    <div class="row g-3">

        <!-- <div class="col-12">{{  inputs }}</div> -->


        <!-- side -->
        <div class="col-md-auto">
            <div class="position-sticky" style="top: 2rem; ">


                <loading-cover-component :loading="loading" />


                <div class="row flex-md-column g-2 mb-2">
                    <div class="col">
                        <button @click="inputsReset"
                        class="btn btn-light border w-100 mb-3"
                        type="button"
                        >絞り込みリセット</button>
                    </div>
                    <div class="col">
                        <div>日付絞り込み</div>
                        <input type="date" class="form-control" v-model="inputs.date" />
                    </div>
                    <div class="col">
                        <div>表示件数</div>
                        <select
                        v-model="inputs.page_count"
                        class="form-select">
                            <option v-for="( page_count, key ) in page_counts" :key="key"
                            :value="page_count"
                            >{{page_count+'件表示'}}</option>
                        </select>
                    </div>
                    <!-- <div class="col">
                        <select
                        v-model="inputs.admin_id"
                        class="form-select">
                            <option value="">サイト管理者</option>

                            <option v-for="( admin, key ) in admins" :key="key"
                            :value="admin.id"
                            selected>{{admin.name}}</option>
                        </select>
                    </div> -->
                </div>


            </div>
        </div>
        <!-- main -->
        <div class="col">


            <table class="table bg-white border-top">
                <tbody>
                    <tr>
                        <td v-if="!access_logs.length" class="text-center text-secondary border-0 pt-4 pb-3">
                            *操作履歴はありません
                        </td>
                    </tr>
                    <tr v-for="(access_log, key) in access_logs" :key="key">
                        <td>
                            <!--日時-->
                            <div class="form-text">{{ access_log.created_at_format}}</div>

                            <!--ユーザー名-->
                            <div class="">
                                アカウント：<a href="#" @click.prevent="inputs.user_id=access_log.user.id"
                                >{{ access_log.user.name}}</a>
                            </div>

                            <!--IP-->
                            <div class="">
                                IP：<a href="#" @click.prevent="inputs.ip=access_log.ip"
                                >{{ access_log.ip}}</a>
                            </div>

                            <!--URL-->
                            <div class="">
                                URL：{{ access_log.path}}
                                <a :href="access_log.path" target="_blank"
                                ><i class="bi bi-box-arrow-up-right"></i></a>
                            </div>

                            <!--ユーザーエージェント-->
                            <div class="">
                                <a href="#" @click.prevent="inputs.user_agent=access_log.user_agent"
                                >{{ access_log.user_agent_text}}</a>
                            </div>

                        </td>
                        <td class="py-3">
                            <a :href="access_log.r_admin_user_show"
                            class="btn btn-sm btn-light border"
                            >ユーザー詳細</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- ページネーション -->
            <pagenation-component
            :pagenate="pagenate"
            :data="access_logs"
            @cahnge-data="getData"
            />

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

    const access_logs = ref([]); /* 一覧 */
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    /* 入力値 */
    const inputs = ref({});

    /* 入力値リセット */
    const inputsReset = ()=>{
        inputs.value = {
            _token:   props.token,
            ip:          null,
            user_agent:  null,
            user_id:     null,
            date:        null,
            page_count: 20,//表示ページ
        };
    };


    /* ページネーションデータ */
    const pagenate = ref({
        current_page :0,
        links: {}
    });

    /* 選択枝 */
    const page_counts = ref([20,50,100]);


    /* 監視 */
    watch(() => inputs.value.ip,         () => getData());
    watch(() => inputs.value.user_agent,() => getData());
    watch(() => inputs.value.user_id,    () => getData());
    watch(() => inputs.value.page_count, () => getData());
    // watch(() => inputs.value.date, (newVal) => {
    //     if (newVal instanceof Date && !isNaN(newVal)) { getData(); }
    // });
    watch(() => inputs.value.date, () => getData());


    /* 初回データ取得 */
    onMounted(() => {
        inputsReset();
        getData();
    });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {

        loading.value = true/* 読み込み */


        try {
            const response = await axios.post(route, inputs.value );
            const paginate = response.data['access_logs'];

            access_logs.value = paginate.data;

            loading.value = false;/* 読み込み */
            inputs.value.destory = false;

            /* 次のデータURLの保存 */
            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            pagenate.value.current_page = paginate.current_page;//表示中ページ
            pagenate.value.links = paginate.links;//ページネートURL


        } catch (error) {
            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        }

    };

</script>


