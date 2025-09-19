<template>
    <div class="">

        <loading-cover-component :loading="loading" />



        <div class="row g-2  align-items-center mb-3">
            <div class="col-auto">
                <select v-model="inputs.selected_column_name" class="form-select">
                    <option v-for="(column_name, key) in column_names" :key="key"
                    :value="key"
                    >{{ column_name }}</option>
                </select>
            </div>
            <div class="col" style="max-width: 20rem;">
                <div class="input-group">
                    <input v-model="inputs.search_keys"
                    type="text" class="form-control" placeholder="検索キーワード"
                    name="search_keys">
                    <button @click.prevent="getData()"
                    class="btn btn-outline-secondary" type="submit">検索</button>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="row align-items-center gx-2">
                    <div class="col-auto">
                        <label class="form-check">
                            <input @change="getData()"
                            v-model="inputs.deleted"
                            class="form-check-input" type="checkbox">
                            <div class="form-check-label">退会ユーザーのみ</div>
                        </label>
                    </div>

                    <div class="col-auto">
                        <form :action="routes.dl_csv">
                            <input type="hidden" name="user_ids" :value="users.map(user => user.id).join(',')">
                            <button class="btn border py-0" type="submit"
                            ><i class="bi bi-filetype-csv fs-5"></i>ダウンロード</button>
                        </form>
                    </div>

                    <div v-if="routes.other_menu" class="col-auto h-100">
                        <a :href="routes.other_menu" class="btn border py-1 h-100"
                        >その他メニュー</a>
                    </div>
                </div>

            </div>

        </div>


        <section class="card card-body bg-white mb-3 overflow-auto">


            <!-- ページネーション -->
            <nav v-if="pagenate.links.length > 3" aria-label="Pagination">
                <ul class="pagination justify-content-start align-items-center">
                    <li
                    v-for="(link, index) in pagenate.links"
                    :key="index"
                    class="page-item"
                    :class="{
                        active: link.active,
                        disabled: !link.url
                    }"
                    >
                        <a
                        v-if="link.url"
                        class="page-link"
                        href="#"
                        v-html="linkLavel( link.label )"
                        @click.prevent="getData( link.url  )"
                        ></a>
                        <span v-else class="page-link" v-html="linkLavel( link.label )"></span>
                    </li>
                </ul>
                <div class="">{{ users.length }}件表示</div>
            </nav>



            <table class="table bg-white mb-3" style="min-width:680px;">
                <!--ヘッド（並べ替えボタン）-->
                <thead>
                    <tr class="bg-white text-center">
                        <th>ID</th>
                        <th></th>
                        <th scope="col">アカウント</th>
                        <th scope="col" class="d-none d-lg-table-cell">メール</th>
                        <th scope="col" class="d-none d-lg-table-cell">
                            <!-- <i class="bi bi-twitter fs-5"></i> -->

                            <img :src="src_x_image"
                            alt="xロゴ" class="d-inline-block p-1 mb-1" style=" width:1.6rem; height:1.6rem;">
                        </th>
                        <th scope="col" class="text-center">
                            <i class="bi bi-envelope fs-5"></i>
                        </th>
                        <th scope="col">
                            <a :href="routes.prize">商品</a>
                        </th>
                        <th scope="col">
                            <a :href="routes.gacha">ガチャ</a>
                        </th>
                        <th scope="col">
                            <a :href="routes.sail">購入</a>
                        </th>
                        <th scope="col">
                            <a :href="routes.point">ポイント</a>
                        </th>
                        <th scope="col" class="d-none d-lg-table-cell">登録/最終アクセス/ポイント期限</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="( user, key ) in users" :key="key"
                    class="bg-white text-center">
                        <td>{{ user.id }}</td>
                        <td>
                            <div style="width:1.6rem;">
                                <ratio-image-component
                                style_class="rounded-circle ratio ratio-1x1 border"
                                :url="user.image_path"
                                ></ratio-image-component>
                            </div>
                        </td>
                        <td>
                            <a :href="user.r_show">
                                <span v-if="user.admin"
                                class="text-primary">●</span>

                                {{ user.name }}
                            </a>

                            <div v-if="user.deleted_at"
                            class="d-block text-danger">*退会済</div>
                        </td>


                        <td class="d-none d-lg-table-cell">{{ user.email.length>14 ? user.email.slice(0, 12)+'...' : user.email}}</td>
                        <td class="d-none d-lg-table-cell">{{  user.twitter_id ?? '---'  }}</td>
                        <td>{{ user.get_email  ? '受取' : '---' }}</td>
                        <!--商品数-->
                        <td>
                            <a :href="user.r_prize">{{ user.u_prizes_count }}</a>
                        </td>
                        <!--ガチャプレイ数-->
                        <td>
                            <a :href="user.r_gacha">{{ user.gacha_play_count }}</a>
                        </td>
                        <!--購入-->
                        <td>
                            <a :href="user.r_sail">{{ user.sail.toLocaleString() }}</a>
                        </td>
                        <!--ポイント-->
                        <td>
                            <a :href="user.r_point">{{ user.point.toLocaleString() }}</a>
                        </td>
                        <!--登録/最終アクセス/ポイント期限-->
                        <td class="d-none d-lg-table-cell">
                            <div class="text-">{{ user.created_at_format }}</div>
                            <div class="text-success">{{ user.last_access_at_format }}</div>
                            <div class="text-danger">{{ user.point_deadline_at_format }}</div>
                        </td>

                    </tr>
                </tbody>
            </table>



            <!-- ページネーション -->
            <nav v-if="pagenate.links.length > 3" aria-label="Pagination">
                <ul class="pagination justify-content-start">
                    <li
                    v-for="(link, index) in pagenate.links"
                    :key="index"
                    class="page-item"
                    :class="{
                        active: link.active,
                        disabled: !link.url
                    }"
                    >
                        <a
                        v-if="link.url"
                        class="page-link"
                        href="#"
                        v-html="linkLavel( link.label )"
                        @click.prevent="getData( link.url  )"
                        ></a>
                        <span v-else class="page-link" v-html="linkLavel( link.label )"></span>
                    </li>
                </ul>
                <div class="">{{ users.length }}件表示</div>
            </nav>


        </section>

    </div>
</template>
<script setup>
    import axios from 'axios';
    import { ref, onMounted } from 'vue';


    const props = defineProps({
        token:      { type: String, default: '', },
        src_x_image:{ type: String, default: '', },
        r_api_list: { type: String, default: '', },
        // r_user_show:{ type: String, default: '', },
    });


    const loading      = ref(true);/* 通信中 */

    const nextPageUrl  = ref(null);/* 次のページネーションURL */

    const column_names = ref([]);/* 検索絞り込み用のカラム一覧 */

    const routes       = ref([]);/* ルーティング */


    const inputs  = ref({ /* 通信パラメーター */
        _token       : props.token,
        search_keys  : '',
        selected_column_name :'name',
        deleted: false,
    });

    const pagenate = ref({/* ページネーションデータ */
        current_page :0,
        links: {}
    });

    const users = ref([ ]);  /* データリスト */

    // const selected_column_name = ref('name');//選択中の検索絞り込みカラム



    onMounted(()=>{

        getData(); //一覧取得

    });



    /* 一覧取得 */
    const getData = (route = props.r_api_list)=>{

        loading.value = true;// 通信中
        if( route == props.r_api_list ){ users.value = []; } //リセット

        axios.post( route, inputs.value )
        .then(json => {
            console.log(json.data);
            // return;

            //ページネーションデータ
            const paginate = json.data['users'];

            // 情報の登録（新規登録・ページネーション追加）
            users.value = paginate.data;


            column_names.value = json.data['column_names'];/* 検索絞り込み用のカラム一覧の保存 */
            routes.value       = json.data['routes'];      /* ルーティングの保存 */

            loading.value = false;// 通信中


            /* 次のデータの読み込み */
            const current_page = paginate.current_page;//表示中ページ
            const last_page    = paginate.last_page;   //最終ページ
            nextPageUrl.value  = current_page != last_page ? paginate.next_page_url : null;//URLの更新

            pagenate.value.current_page = paginate.current_page;//表示中ページ
            pagenate.value.links = paginate.links;//ページネートURL
        })
        .catch(error => {

            //その他のエラー
            console.log( error.response.data );

            // 現在のページをリロードする
            alert("通信エラーが発生しました。再読み込みします。");
            location.reload();

        });
    };


    /* ページネーションラベルのカスタマイズ */
    const linkLavel = label => {

        if(label=='pagination.next'){     return '>>'; }

        if(label=='pagination.previous'){ return '<<'; }

        return label;
    };
</script>
