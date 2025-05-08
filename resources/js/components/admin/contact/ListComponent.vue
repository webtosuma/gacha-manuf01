<template>
    <div>
        <loading-cover-component :loading="loading" />


        <!-- トーストポップアップ -->
        <div id="toast_container" class="position-fixed bottom-0 end-0 p-2" style="z-index:10;">
            <div v-for="(message, key) in messages" :key="key"
            class="toast fade show mb-1 fade-in-message" role="alert" aria-live="assertive" aria-atomic="true" >
                <div class="toast-header bg-dark text-white">
                    <strong class="me-auto">{{ message }}</strong>
                    <button type="button" class="btn px-1 py-0 text-white fs-5" data-bs-dismiss="toast"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        </div>


        <div class="row g-3 gy-">


            <!-- お問い合わせリスト length -->
            <div class="col order-lg-2">

                <!--header menu-->
                <div class="">
                    <div class="row g-3 align-items-center justify-content-between mb-2 px-2"  style="min-height:3rem;">
                        <div class="col">
                            <label  class="form-check">
                                <input v-model="allChecked" class="form-check-input p-2" type="checkbox" @click="toggleAllChecks">
                                <div class="form-check-label">すべて</div>
                            </label>
                        </div>


                        <div v-if="inputs.contact_ids.length" class="col-auto">
                            チェックしたものを：
                        </div>
                        <!--一括 対応切替-->
                        <div v-if="inputs.contact_ids.length" class="col-auto">
                            <div class="input-group">
                                <select v-model="inputs.bulk_update_responsed_value"
                                class="form-select form-select-sm">
                                    <option v-for="(responsed, key) in ['対応済','未対応']" :key="key"
                                    :value="responsed"
                                    >{{ responsed }}</option>
                                </select>
                                <button @click="bulkUpdateResponsed()"
                                class="btn btn-sm btn-light border text-primary">に変更</button>
                            </div>
                        </div>
                        <!--一括 フォルダ変更-->
                        <div v-if="inputs.contact_ids.length" class="col-auto">
                            <div class="input-group">
                                <select v-model="inputs.bulk_update_typetext_value"
                                class="form-select form-select-sm">
                                    <option value="">{{ '受信箱' }}</option>

                                    <option v-for="(type_text, key) in type_texts" :key="key"
                                    :value="type_text"
                                    >{{ type_text }}</option>
                                </select>
                                <button @click="bulkUpdateTypetext"
                                class="btn btn-sm btn-light border text-primary">フォルダに移動</button>
                            </div>
                        </div>
                        <!--一括 削除-->
                        <div v-if="inputs.contact_ids.length " class="col-auto">
                            <button v-if="inputs.type_text=='ゴミ箱'"
                            data-bs-toggle="modal" :data-bs-target="'#deleteContactsModal'"
                            class="btn btn-sm border btn-light text-danger">すべて削除</button>
                        </div>
                    </div>
                </div>
                <div class="list-group">


                    <div v-if="!contacts.length"
                    class="list-group-item list-group-item-action py-5 d-flex">
                        <h5 class="text-center">お問い合わせ情報はありません</h5>
                    </div>


                    <div v-for=" (contact, dKey) in contacts " :key="dKey"
                    class="list-group-item list-group-item-action p-0 d-flex"
                    >
                        <!--チェックボックス-->
                        <div class="p-2" style="width:2rem;">
                            <input v-model="inputs.contact_ids"
                            :value="contact.id"
                            class="form-check-input p-2"
                            type="checkbox" >
                        </div>


                        <a href="#"
                        data-bs-toggle="offcanvas" :data-bs-target="'#contactListOffcanvas'+dKey " :aria-controls="'contactListOffcanvas'+dKey "
                        class="d-block p-3 py-2 col text-dark text-decoration-none"
                        >

                            <div class="row">
                                <div class="col-auto">
                                    <span v-if="contact.responsed" class="badge text-secondary">対応済</span>

                                    <span v-else class="badge bg-danger">未対応</span>
                                </div>
                                <!-- 日付 　-->
                                <div class="col-auto">{{ formatAt(contact.created_at) }}</div>
                                <!-- 名前 -->
                                <div class="col d-none d-md-block overflow-hidden">
                                    <span class="d-inline-block text-truncate" style="width: 200px;">
                                        {{contact.name}}様
                                    </span>
                                </div>
                            </div>

                        </a>


                        <!-- dropdown menu -->
                        <!-- <div class="col-auto">
                            <div class="dropdown">
                                <button class="btn" type="button"
                                :id="'contactDropdownMenuButton'+dKey" data-bs-toggle="dropdown" aria-expanded="false"
                                >
                                    <span class="fs-5">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </span>
                                </button>

                                <ul class="dropdown-menu" :aria-labelledby="'contactDropdownMenuButton'+dKey">
                                    <li>
                                        <a @click.prevent="destroy(contact.id)"
                                        class="dropdown-item" href="#">削除</a>
                                    </li>
                                </ul>
                            </div>
                        </div> -->



                        <!-- offcanvas -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" style="width:600px;"
                        :id="'contactListOffcanvas'+dKey " :aria-labelledby="'contactListOffcanvasLabel'+dKey "
                        >
                            <div class="offcanvas-header">
                                <h5 :id="'contactListOffcanvasLabel'+dKey ">お問い合わせ内容</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">

                                <div class="card card-body mb-3">

                                    <div class="row">
                                        <div class="col-12 col-md-4 fw-bold">対応状況</div>
                                        <div class="col-12 col-md-8 ps-3">
                                            <div class="row align-items-center">
                                                <!--text-->
                                                <div class="col">
                                                    <span v-if="contact.responsed"
                                                    class="text-success">対応済</span>
                                                    <span v-else
                                                    class="text-danger">未対応</span>
                                                </div>

                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" @change="responsed(contact.id, contact.responsed)"
                                                        :id="'flexSwitchResponsed'+dKey" v-model="contact.responsed">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row py-2 border-lg-top">
                                        <div class="col-12 col-md-4 fw-bold">日時</div>
                                        <div class="col-12 col-md-8 ps-3">{{ formatAt(contact.created_at) }}</div>
                                    </div>
                                    <div class="row py-2 border-top">
                                        <div class="col-12 col-md-4 fw-bold">氏名</div>
                                        <div class="col-12 col-md-8 ps-3">{{contact.name}}</div>
                                    </div>
                                    <div class="row py-2 border-top">
                                        <div class="col-12 col-md-4 fw-bold">メール</div>
                                        <div class="col-12 col-md-8 ps-3">{{contact.email}}</div>
                                    </div>
                                    <div class="row py-2 border-top">
                                        <div class="col-12 col-md-4 fw-bold">電話番号</div>
                                        <div class="col-12 col-md-8 ps-3">{{contact.tell}}</div>
                                    </div>
                                    <div class="row py-2 gy-2 border-top">
                                        <div class="col-12 col-md- fw-bold">お問い合わせ内容</div>
                                        <div class="col-12 col-md- ps-3">
                                            <div v-html="contact.body_text.replace(/\r?\n/g, '<br>')"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </div><!-- end for -->

                    <div v-show="nextPageUrl" class="mt-3">
                        <a @click.prevent="getData( nextPageUrl )"
                        class="btn btn-light border"
                        href="">もっと読み込む</a>
                    </div>

                </div><!-- end list-group -->

            </div>


            <!-- side -->
            <div class="col-12 col-lg-auto order-lg-1">
                <div class="position-sticky" style="top: 2rem; ">


                    <div class="row flex-md-column g-2 mb-2">

                        <!--キーワード検索-->
                        <div class="col input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text"
                            v-model="inputs.keyword"
                            class="form-control"
                            placeholder="氏名・メール">

                            <span
                            @click="deleteKeyword()"
                            class="btn btn-light border"><i class="bi bi-x"></i></span>
                        </div>
                        <!--絞り込み-->
                        <div class="col">
                            <select
                            v-model="inputs.responsed"
                            class="form-select">
                                <option v-for="(responsed ,key) in responseds" :key="key"
                                :value="responsed"
                                >{{ responsed }}</option>
                            </select>
                        </div>
                        <!--年月-->
                        <div class="col">
                            <select
                            v-model="inputs.month"
                            class="form-select">
                                <option value="">年月</option>

                                <option v-for="( month, key ) in months" :key="key"
                                :value="month.date_stanp"
                                >{{month.format}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="list-group mb-2 mt-4">



                        <button type="button"
                        @click.prevent="changeTypeText('')"
                        class="list-group-item"
                        :class="{'bg-primary-subtle disabled':inputs.type_text==''}">
                            <div class="row">
                                <div class="col text-start">
                                    <i class="bi bi-inbox-fill me-1"></i>受信箱
                                </div>
                                <div class="col-auto">
                                    <!--未対応数-->
                                    <span  v-if="not_res_conts['受信箱']>0"
                                    class="badge rounded-pill bg-danger">{{ not_res_conts['受信箱'] }}</span>
                                </div>
                            </div>
                        </button>



                        <!--フォルダ新規作成ボタン-->
                        <button data-bs-toggle="modal" data-bs-target="#createFolderModal"
                        class="list-group-item text-secondary text-start"
                        ><i class="bi bi-plus-square me-1"></i>フォルダを作成</button>

                        <!--フォルダボタン-->
                        <a v-for="(type_text, key) in type_texts" :key="key"
                        href="#"
                        @click.prevent="changeTypeText(type_text)"
                        class="list-group-item position-relative"
                        :class="{'bg-primary-subtle disableddd':inputs.type_text==type_text}">

                            <div class="row g-0">
                                <div class="col">
                                    <i class="bi bi-folder-fill me-1"></i>{{ type_text }}
                                </div>
                                <div class="col-auto">
                                    <!--未対応数-->
                                    <span v-if="not_res_conts[type_text]>0"
                                    class="badge rounded-pill bg-danger">{{ not_res_conts[type_text] }}</span>
                                </div>
                                <div class="col-auto p-0">
                                    <!--削除-->
                                    <button v-if=" ! type_texts_defaults.includes( type_text ) "
                                    data-bs-toggle="modal" :data-bs-target="'#deleteFolderModal'+key"
                                    class="btn btn-sm text-secondary m-0"
                                    ><i class="bi bi-trash"></i></button>
                                </div>
                            </div>

                            <!--削除-->
                            <!-- <button v-if="type_text!='ゴミ箱'"
                            data-bs-toggle="modal" :data-bs-target="'#deleteFolderModal'+key"
                            class="btn btn-sm text-secondary position-absolute top-50 end-0 translate-middle-y"
                            ><i class="bi bi-trash"></i></button> -->
                        </a>

                    </div>


                </div>
            </div>


        </div>



        <!-- Modal  -->
        <div class="">

            <!-- 新規作成Modal -->
            <div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-6" id="createFolderModalLabel">フォルダ作成</h5>
                        </div>
                        <div class="modal-body">
                            <label class="d-block mb-3">
                                <div class="form-label d-flex justify-content-between text-primary">
                                    <div class="">フォルダ名</div>
                                    <div class="">({{inputs.new_type_text.length}}/20)</div>
                                </div>

                                <input v-model="inputs.new_type_text"
                                type="text" class="form-control border-primary" maxlength="20">
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary text-white" data-bs-dismiss="modal"
                            @click="typeCreate()"
                            :disabled="isNewTypeTextValid"
                            >新規作成</button>
                            <button type="button" class="btn border"  data-bs-dismiss="modal">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- フォルダ削除Modal -->
            <div v-for="(type_text, key) in type_texts" :key="key"
            class="modal fade"
            :id="'deleteFolderModal'+key" tabindex="-1"
            :aria-labelledby="'deleteFolderModal'+key+'Label'" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-6" :id="'deleteFolderModal'+key+'Label'">『{{type_text}}』フォルダ削除</h5>
                        </div>
                        <div class="modal-body">
                            『{{type_text}}』フォルダを削除します。<br>
                            フォルダ内に保存されて入るお問い合わせ情報も全て削除されます。<br>
                            よろしいですか？
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal"
                            @click="typeDelete(type_text)"
                            >削除</button>
                            <button type="button" class="btn border"  data-bs-dismiss="modal">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- フォルダ削除Modal -->
            <div class="modal fade"
            :id="'deleteContactsModal'" tabindex="-1"
            :aria-labelledby="'deleteContactsModal'+'Label'" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-6" :id="'deleteContactsModal'+'Label'">お問い合わせ削除</h5>
                        </div>
                        <div class="modal-body">
                            選択したお問い合わせを全て削除します。<br>
                            よろしいですか？
                        </div>
                        <div class="modal-footer">
                            <button @click="bulkDelete()"
                            type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">削除</button>
                            <button type="button" class="btn border"  data-bs-dismiss="modal">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>



    </div>
</template>

<script setup>
    import { ref, computed, watch, onMounted, } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        token: { type: String, default: '' },
        r_api_list: { type: String, default: '' }, // 一覧表示
        r_api_update: { type: String, default: '' },
        r_api_destroy: { type: String, default: '' },
        r_api_type_create: { type: String, default: '' },//フォルダの作成
    });


    const loading       = ref(true);
    const contacts      = ref([]);  //お問い合わせ
    const not_res_conts = ref({});  //未対応カウント

    const inputs   = ref({
        _token: props.token,
        keyword:   '',
        month:     '',
        type_text: '',//フォルダの種類
        responsed: '絞り込み',//対応状況

        /* 一括処理パラメーター */
        contact_ids: [],
        bulk_update_typetext: false, //一括フォルダの変更
        bulk_update_typetext_value: '',
        bulk_update_responsed: false, //一括対応切り替え
        bulk_update_responsed_value: '対応済',
        bulk_delete:           false, //一括削除

        new_type_text:    '',
        delete_type_text: '',
    });

    const months      = ref([]);  /* 年月選択肢 */
    const type_texts  = ref([]);  /* フォルダの種類 */
    const type_texts_defaults  = ref([/*'退会','ゴミ箱'*/]);  /* フォルダの種類(デフォルト値) */

    const responseds  = ref(['絞り込み','対応済','未対応']);  /* フォルダの種類 */
    const nextPageUrl = ref('');  /* 次のデータの読み込みURL */

    const messages    = ref([]);  /* ポップアップメッセージ */


    /* [コンピューティッド]すべてチェック */
    const allChecked = computed({
        get: () => inputs.value.contact_ids.length === contacts.value.length,
        set: (value) => {
            if (value) {
                // すべて選択
                inputs.value.contact_ids = contacts.value.map(contact => contact.id);
            } else {
                // すべて解除
                inputs.value.contact_ids = [];
            }
        }
    });

    /* [コンピューティッド]新規フォルダ作成入力監視 */
    const isNewTypeTextValid = computed(() => {
      return ! ( inputs.value.new_type_text.length > 0 && !type_texts.value.includes(inputs.value.new_type_text) );
    });


    /* 監視 */
    watch(() => inputs.value.keyword,  () => getData());
    watch(() => inputs.value.month,    () => getData());
    watch(() => inputs.value.responsed,() => getData());


    /* 初回データ取得 */
    onMounted(() => {
        resetBulc();/* 一括処理パラメーターのリセット */
        getData();  /* データ取得 */
    });


    /* データ取得 */
    const getData = (route = props.r_api_list) => {

        loading.value = true;/* 読み込み */

        axios.post(route, inputs.value)
        .then(response => {

            const paginate = response.data['contacts'];

            contacts.value =
            route === props.r_api_list ? paginate.data : [...contacts.value, ...paginate.data];

            /* 年月絞り込み */
            months.value        = response.data.months;
            /* フォルダの種類(デフォルト値) */
            type_texts_defaults.value = response.data.type_texts_defaults;
            /* フォルダの種類 */
            type_texts.value    = [ ...response.data.type_texts, ...type_texts_defaults.value ];
            /* 未対応カウント */
            not_res_conts.value = response.data.not_res_conts;



            loading.value = false;/* 読み込み */

            const { current_page, last_page, next_page_url } = paginate;
            nextPageUrl.value = current_page !== last_page ? next_page_url : null;

            resetBulc();/* 一括処理パラメーターのリセット */
        })
        .catch(error => {
            console.error(error.response?.data);
            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }
        });

    };


    /* お問い合わせ更新 */
    const responsed = (id, responsed) => {
        const route = `${props.r_api_update}/${id}`;
        axios.patch(route, { responsed: responsed ? 1 : 0, ...inputs })
            .catch(error => {
                alert('通信エラーが発生しました。');
                console.error(error.response.data);
            });
    };


    /* お問い合わせ削除 */
    const destroy = (id) => {
        const route = `${props.r_api_destroy}/${id}`;
        axios.delete(route, { data: inputs })
            .then(() => {
                getData();
            })
            .catch(error => {
                alert('通信エラーが発生しました。');
                console.error(error.response.data);
            });
    };


    /* フォルダ新規作成 */
    const typeCreate = () => {
        loading.value = true;/* 読み込み */
        const route = props.r_api_type_create;

        axios.post(route, inputs.value )
        .then(response => {
            /* フォルダの種類 */
            type_texts.value = [... response.data.type_texts, 'ゴミ箱'];
            type_texts.value = [ ...response.data.type_texts, ...type_texts_defaults.value ];
            loading.value = false;/* 読み込み */
            resetBulc();/* 一括処理パラメーターのリセット */
        })
        .catch(error => {
            alert('通信エラーが発生しました。');
            console.error(error.response.data);
        });
    };

    /* フォルダの削除 */
    const typeDelete = (delete_type_text) => {
        inputs.value.delete_type_text = delete_type_text;
        getData();
    }

    /* キーワードの削除 */
    const deleteKeyword = () => {
        inputs.value.keyword = '';
        getData();
    };

    /* 選択フォルダの変更 */
    const changeTypeText = (typeText='') => {
        inputs.value.type_text = typeText;//選択フォルダの変更
        getData();//データ取得
    }


    /* 一括対応切り替え */
    const bulkUpdateResponsed = () => {
        inputs.value.bulk_update_responsed = true;
        getData();
    };

    /* 一括対応切り替え */
    const bulkUpdateTypetext = () => {
        inputs.value.bulk_update_typetext = true;
        getData();
    };

    /* 一括削除 */
    const bulkDelete = () => {
        inputs.value.bulk_delete = true;
        getData();
    };


    /* 一括処理パラメーターのリセット */
    const resetBulc = () => {

        // ポップアップメッセージ
        if(inputs.value.bulk_update_typetext){
            messages.value.push('フォルダを一括移動しました。');
        }
        if(inputs.value.bulk_update_responsed){
            messages.value.push('対応状況を一括変更しました。');
        }
        if(inputs.value.bulk_delete){
            messages.value.push('お問い合わせを一括削除しました。');
        }
        if(inputs.value.new_type_text){
            messages.value.push('フォルダを新規作成しました。');
        }
        if(inputs.value.delete_type_text){
            messages.value.push('フォルダを削除しました。');
        }

        inputs.value.contact_ids = [];  //選択中お問い合わせID
        inputs.value.bulk_update_typetext        = false, //一括フォルダの変更
        inputs.value.bulk_update_typetext_value  = '',
        inputs.value.bulk_update_responsed       = false; //一括対応切り替え
        inputs.value.bulk_update_responsed_value = '対応済';
        inputs.value.bulk_delete                 = false;//一括削除
        inputs.value.new_type_text               = '';
        inputs.value.delete_type_text            = '';


    };


    /* すべてチェック */
    const toggleAllChecks = () => {
        if (allChecked.value) {
            inputs.value.contact_ids = [];
        } else {
            inputs.value.contact_ids = contacts.value.map(contact => contact.id);
        }
    };


    /* 日時変換 */
    const formatAt = (inputString) => {
        const date = new Date(inputString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}/${month}/${day} ${hours}:${minutes}`;
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
