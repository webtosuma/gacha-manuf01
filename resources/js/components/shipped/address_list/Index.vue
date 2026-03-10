<template>
    <div class="">


        <label v-if="show_check!='0'"
        class="form-check">
            <input name="default_address" :value="true"
            class="form-check-input" type="checkbox" checked>
            <div class="form-check-label">
                毎回このお届け先を使う
            </div>
        </label>



        <ul class="list-group bg-white">


            <!-- お問い合わせ先追加 -->
            <li class="list-group-item">

                <!-- お届け先の新規登録フォーム -->
                <button v-if="!show_create"
                @click="show_create=true"
                type="button"
                class="btn btn-primary text-white"
                >お届け先情報を追加する</button>

                <button v-else
                @click="show_create=false"
                type="button"
                class="btn btn-link">閉じる</button>



                <!-- data-bs-toggle="collapse" data-bs-target="#collapseCreateAddress" aria-expanded="false" aria-controls="collapseCreateAddress" -->

                <div v-if="use_size!=0"
                class="text-danger mt-2 form-text">スニーカーを発送の際は発送先情報のサイズの欄に入力登録をお願いします。</div>


                <div v-if="show_create"
                class="mx-auto col-lg-6 p-3" >

                    <h5>お届け先情報の追加</h5>

                    <u-edit-user-address-form
                    @get-list="created()"
                    :token="token"
                    :r_api_store="r_store"
                    :use_size="use_size"
                    :default_email="default_email"
                    />


                </div>


                <!-- <div class="collapse" id="collapseCreateAddress">
                    <div class="mt-3 mb-5">
                        <div class="mx-auto col-lg-6" >

                            <u-edit-user-address-form
                            @get-list="getList()"
                            :token="token"
                            :r_api_store="r_store"
                            :use_size="use_size"
                            />

                        </div>
                    </div>
                </div> -->

            </li>




            <li v-if=" addresses.length==0 "
            class="list-group-item py-4">
                お届け先情報を登録してください。
            </li>

            <li v-for="(address, key) in addresses" :key="key"
            class="list-group-item">
                <div class="d-block">
                    <div class="row gx-0 gy-2 align-items-center">
                        <div v-if="show_check!='0'" class="col-auto">
                            <div class="form-check">
                                <input v-model="selectedAddressId"
                                @change="updateSelectedAddressId( selectedAddressId )"
                                class="form-check-input" type="radio" name="user_address_id" :value="address.id">
                            </div>
                        </div>
                        <div class="col">
                            <div class="fw-bold">
                                {{ address.name }} 様
                            </div>
                            <div class="fw-bold">
                                <span>{{ '〒'+address.postal_code.substring(0,3)+'-'+address.postal_code.substring(3,7) }}</span>
                                <span>{{ address.todohuken }}</span>
                                <span>{{ address.shikuchoson }}</span>
                                <span>{{ address.number }}</span>
                            </div>
                            <div class="fw-bold">
                                <span>{{ address.tell }}</span>
                            </div>
                            <div v-if="address.email" class="fw-bold">
                                <span>{{ address.email }}</span>
                            </div>
                            <div v-if="address.size" class="fw-bold">
                                <span>靴のサイズ：{{ address.size }}</span>
                            </div>

                            <div v-if="address.remarks_text" class="fw-bold">
                                <span>備考欄：{{ address.remarks_text.substring(0, 16) + (address.remarks_text.length>16?'...':'') }}</span>
                            </div>
                        </div>
                        <div v-if="show_check=='0'"
                        class="col-12 col-md-auto">
                            <div class="row g-2 align-items-center justify-content-end">
                                <!--発送待ち-->
                                <div class="col col-md-12 text-md-end">
                                    <a v-if="address.shipped_waiting_count>0"
                                    :href="address.r_shipped"
                                    class="btn badge text-danger border border-danger">
                                        <i class="bi bi-box-seam fs-6"></i>
                                        <span>発送待ち</span>
                                        <span class="ms-2 fs-5 fw-bold">{{ address.shipped_waiting_count }}</span>
                                    </a>
                                </div>
                                <!--編集ボタン-->
                                <div class="col-auto">
                                    <a :href="address.r_edit"
                                    class="btn border rounded-pill text-warning"
                                    ><i class="bi bi-pencil-square"></i></a>
                                </div>
                                <!--削除ボタン-->
                                <div class="col-auto">
                                    <!-- Modal -->
                                    <delete-modal-component
                                    v-if="address.shipped_waiting_count<1"
                                    @parent-func="destroy(address.id)"
                                    :index_key="address.id"
                                    icon="bi-trash"
                                    func_btn_type="button"
                                    button_class="invisible d-none">
                                        <div>
                                            <span class="fw-bold">{{ address.name }} 様</span>のお届け先情報を削除します。
                                            <br />よろしいですか？
                                        </div>
                                    </delete-modal-component>

                                    <button type="button" data-bs-toggle="modal"
                                    :data-bs-target="'#deleteModal'+address.id"
                                    class="btn border rounded-pill "
                                    :class="address.shipped_waiting_count<1 ? 'text-danger' : 'text-secondary' "
                                    ><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </li>


        </ul>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        props: {

            token:{ type: String,  default: '', },
            r_index:      { type: [String,Number], default: null },
            r_store:      { type: [String,Number], default: null },//＊新規作成コンポーネントで利用
            r_destroy:    { type: [String,Number], default: null },
            show_check :  { type: [String,Number], defualt: '1' },//チェックボックスの表示
            use_size:     { type: [String,Number], default: 0 },
            default_email:{ type: String, default: '' },

        },
        data() { return {

            addresses: [],
            selectedAddressId: 0, //お届け先住所の選択ラジオ

            show_create: false,

        } },
        mounted() {

            /* 一覧取得 */
            this.getList();

        },
        methods: {


            /* 一覧取得 */
            getList() {
                console.log('get list!')

                const route = this.r_index; //一覧取得
                axios.post( route, { _token: this.token } )
                .then(json => {
                    // console.log( json.data );
                    this.addresses =json.data;

                    this.addresses.forEach(address => {
                        if( address.is_default ){
                            this.selectedAddressId = address.id;
                            this.updateSelectedAddressId(address.id);
                        }
                    });
                    // console.log( this.addresses );
                })
                .catch(error => {
                    alert('データ送信エラーが発生しました。');
                    // console.log( error.response.data );
                });
            },


            /** 削除 */
            destroy(id) {

                const route = this.r_destroy+'/'+id ; //一覧取得

                axios.delete( route, { _token: this.token } )
                .then(json => {

                    /* 一覧取得 */
                    this.getList();

                    /** お届け先アドレスの選択変更*/
                    this.updateSelectedAddressId(0);
                })
                .catch(error => {
                    alert('データ送信エラーが発生しました。');
                    console.log( error.response.data );
                });
            },


            /* 新規作成完了 */
            created() {
                this.show_create = false;
                this.getList();
            },

            /** お届け先アドレスの選択変更*/
            updateSelectedAddressId( id ) {

                // console.log(id);
                this.$emit('update-address',id)
            },

        },
    }
</script>
