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
            <li v-for="(address, key) in addresses" :key="key"
            class="list-group-item">
                <label class="d-block">
                    <div class="row g-0 align-items-center">
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
                        </div>
                        <div class="col-auto">
                            <!-- <button @click="destroy(address.id)"
                            type="button"
                            class="btn btn-sm border">削除</button> -->


                            <button type="button" data-bs-toggle="modal"
                            :data-bs-target="'#deleteModal'+address.id"
                            class="btn btn-sm border"
                            >削除する</button>

                            <!-- Modal -->
                            <delete-modal-component
                            @parent-func="destroy(address.id)"
                            :index_key="address.id"
                            icon="bi-trash"
                            func_btn_type="submit"
                            button_class="invisible">
                                <div>
                                    <span class="fw-bold">{{ address.name }} 様</span>のお届け先情報を削除します。
                                    <br />よろしいですか？
                                </div>
                            </delete-modal-component>

                        </div>
                    </div>
                </label>
            </li>
            <li v-if=" addresses.length==0 "
            class="list-group-item py-4">
                お届け先情報を登録してください。
            </li>
            <!-- お問い合わせ先追加 -->
            <li class="list-group-item">

                <!-- お届け先の新規登録フォーム -->
                <u-create-user-address-form
                @my-created="getList()"
                :token="token" :r_store="r_store"/>

            </li>
        </ul>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        props: {
            token:{ type: String,  default: '', },
            r_index:  { type: [String,Number], default: null },
            r_store:  { type: [String,Number], default: null },//＊新規作成コンポーネントで利用
            r_destroy:{ type: [String,Number], default: null },
            show_check :{ type: [String,Number], defualt: '1' },//チェックボックスの表示
        },
        data() { return {

            addresses: [],
            selectedAddressId: 0, //お届け先住所の選択ラジオ

        } },
        mounted() {

            /* 一覧取得 */
            this.getList();

        },
        methods: {


            /* 一覧取得 */
            getList() {

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
                // console.log(route);


                axios.delete( route, { _token: this.token } )
                .then(json => {
                    // console.log( json.data );
                    /* 一覧取得 */
                    this.getList();
                })
                .catch(error => {
                    alert('データ送信エラーが発生しました。');
                    console.log( error.response.data );
                });
            },



            /** お届け先アドレスの選択変更*/
            updateSelectedAddressId( id ) {

                // console.log(id);
                this.$emit('update-address',id)
            },

        },
    }
</script>
