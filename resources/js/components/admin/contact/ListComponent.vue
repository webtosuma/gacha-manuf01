<template>
    <div>

        <!-- お問い合わせリスト -->
        <div>
            <div v-if="loading"
            class="card card-body py-5">


                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <h2 class="text-center">読み込み中</h2>


            </div>
            <div v-else-if="!contacts.length"
            class="card card-body py-5">


                <h5 class="text-center">お問い合わせ情報はありません</h5>


            </div>
            <div v-else
            class="list-group">


                <div v-for=" (contact, dKey) in contacts " :key="dKey"
                class="list-group-item list-group-item-action p-0 d-flex"
                >

                    <a href="#"
                    class="d-block p-3 py-2 col text-dark text-decoration-none"
                    data-bs-toggle="offcanvas" :data-bs-target="'#contactListOffcanvas'+dKey " :aria-controls="'contactListOffcanvas'+dKey "
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
                    <div class="col-auto">
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
                    </div>



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
                                <div class="row py-2 border-top">
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
                                <div class="row py-2 border-top">
                                    <div class="col-12 col-md-4 fw-bold">お問い合わせ内容</div>
                                    <div class="col-12 col-md-8 ps-3">
                                        <div v-html="contact.body_text.replace(/\r?\n/g, '<br>')"></div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>


                </div><!-- end for -->


            </div><!-- end list-group -->

        </div>



    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
            token:{ type: String,  default: '', },
            r_api_list:{ type: String, default: '', }, //一覧表示
            r_api_update:{ type: String, default: '', },
            r_api_destroy:{ type: String, default: '', },
        },

        data () { return{

            test: false,
            loading: true,

            /* データリスト */
            contacts: [ ] ,
            inputs: {},

        } },
        mounted() {

            this.inputs._token = this.token; //token保存

            /* お問い合わせデータの取得 */
            this.getContactList();

        },
        methods:{

            /** お問い合わせデータの取得route_list */
            getContactList: function(){

                // [ 非同期通信 ]
                const route = this.r_api_list;
                axios.post( route , this.inputs )
                .then(json => {
                    // console.log(json.data);

                    // データの保存
                    this.contacts = json.data;

                    // 読み込み完了
                    this.loading = false;
                })
                .catch( error =>{
                    alert('通信エラーが発生しました。');
                    console.log( error.response.data );
                })
            },


            /** 対応状況の変更 */
            responsed: function(id,responsed){

                // [ 非同期通信 ]
                const route = this.r_api_update+'/'+id;
                axios.patch( route , { responsed:responsed ? 1 : 0, ...this.inputs } )
                .then(json => {

                    // 保存状態の変更
                    // console.log( json );

                })
                .catch(error=>{
                    alert('通信エラーが発生しました。');
                    // console.log( error.response.data );
                })


            },

            /** お問い合わせの削除 */
            destroy: function(id){


                // [ 非同期通信 ]
                const route = this.r_api_destroy+'/'+id;
                axios.delete( route , this.inputs )
                .then(json => {

                    /* お問い合わせデータの取得 */
                    this.getContactList();

                })
                .catch(error=>{
                    alert('通信エラーが発生しました。');
                    console.log( error.response.data );
                })

            },


            /** 日時間データをテクスト変換  */
            formatAt(inputString) {
                const date = new Date(inputString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
                const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング
                const hours = String(date.getHours()).padStart(2, '0'); // 時間も2桁にパディング
                const minutes = String(date.getMinutes()).padStart(2, '0'); // 分も2桁にパディング

                return `${year}/${month}/${day} ${hours}:${minutes}`;
            }
        }
    }
</script>
