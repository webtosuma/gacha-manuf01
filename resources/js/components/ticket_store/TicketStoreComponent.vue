<template>
    <div class="">ticket_store</div>
</template>
<script>
    import axios from 'axios'

    export default {
        props: {
            token:{ type: String,  default: '', },
            r_api_store:{ type: String,  default: '', },   //交換商品
            r_api_category:{ type: String,  default: '', },//ガチャ カテゴリー
            r_api_prize:{ type: String,  default: '', },   //商品

            r_api_create: { type: String,  default: '', },//新規作成
            r_api_update: { type: String,  default: '', },//更新
            r_api_destroy: { type: String,  default: '', },//削除

            category_id:{ type: [String,Number],  default: '', },
        },
        data() { return {

            loading: true,

            categories:[],//ガチャ カテゴリー
            stores:  [],/* 交換用商品 */

            inputs: {},

            reset_inputs: {
                key_words: '',
                category_id: '',

                order_ticket_count: '',
                order_published_at: '',
                order_point_count:  '',
                order_count: '',
            },

            edit: false,

        } },
        mounted() {

            this.inputs = {...this.reset_inputs}; //入力値のリセット
            this.inputs._token = this.token; //token保存
            // this.getCategoryData();/* データ取得 */

        },
        methods:{

            /* 商品データ取得 */
            getData(route = this.r_api_store) {

                this.loading = true;//読み込み中

                axios.post( route , {_token: this.token, ...this.inputs} )
                .then(json => {
                    // console.log(json.data);

                    //ページネーションデータ
                    const paginate = json.data.stores;

                    // 商品情報の登録（新規登録・ページネーション追加）
                    this.stores = route == this.r_api_store ? paginate.data
                    : [ ...this.stores, ...paginate.data];

                    this.loading = false;//読み込み中

                    /* 次のデータの読み込み */
                    const current_page = paginate.current_page;//表示中ページ
                    const last_page    = paginate.last_page;   //最終ページ
                    if( current_page != last_page ){
                        const nextPageUrl = paginate.next_page_url;     //URLの更新
                        this.getData( nextPageUrl );
                    }
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });
            },


            /** デフォルトのデータ取得 */
            getDataReset(){
                this.inputs = {...this.reset_inputs}; //入力値のリセット
                this.inputs._token = this.token; //token保存
                this.inputs.category_id = this.category_id ;
                this.getData();
            },


            /** カテゴリー　データ取得 */
            getCategoryData() {
                const route = this.r_api_category;
                axios.post( route , this.inputs )
                .then(json => {
                    // console.log(json.data);
                    this.categories = json.data;

                    /** アクティブなカテゴリーのセット *//* 商品データ取得 */
                    this.setActiveCategory( this.category_id );
                })
                .catch(error => {
                    alert('通信エラーが発生しました。')
                    console.log( error.response.data );

                });
            },




            /** アクティブなカテゴリーのセット */
            setActiveCategory( category_id ) {

                this.inputs.key_words=''; //キーワードのリセット
                this.keyWords='',

                this.inputs.category_id = category_id;//アクティブなカテゴリーIDのセット
                this.getData(); /* データ取得 */
            },



            /** 並び替え */
            changeOrder(key) {
                const order = this.inputs[key];

                switch (order) {
                    case '':    this.inputs[key]='desc';  break;
                    case 'desc': this.inputs[key]='asc';  break;
                    default:    this.inputs[key]='';  break;
                }

                this.getData(); /* データ取得 */
            },


            /** 日付データをテクスト変換  */
            formatDate(inputString) {

                if( !inputString ){ return ''; }

                const date = new Date(inputString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // 月は0から始まるため+1し、2桁にパディング
                const day = String(date.getDate()).padStart(2, '0'); // 日も2桁にパディング

                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');

                return `${year}/${month}/${day} ${hours}:${minutes}`;
                return `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;

            },


            /** 編集モード切り替え */
            toggleEdit(){
                this.edit = !this.edit;
            },

        },
    };
</script>
