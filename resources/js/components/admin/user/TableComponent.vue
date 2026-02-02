<template>
    <div class="">


        <table class="table text-center" style="font-size:11px;">
            <thead class="border-bottom">
                <th>ID</th>
                <th>アカウント</th>
                <th>メール</th>
                <th>Xアカウント</th>
                <th>取得商品数</th>
                <th>ガチャPLAY数</th>
                <th>ポイント購入金額</th>
                <th>取得ポイント</th>
                <th>登録日</th>
                <!-- <th>登録/最終アクセス/ポイント期限</th> -->
            </thead>
            <tbody>
                <tr v-for="( user, key ) in users" :key="key">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.twitter_id ?? '---' }}</td>
                    <td>{{ user.u_prizes_count }}</td>
                    <td>{{ user.gacha_play_count }}</td>
                    <td>{{ user.sail.toLocaleString() }}</td>
                    <td>{{ user.point.toLocaleString() }}</td>
                    <td class="d-none d-lg-table-cell">
                        <div class="text-">{{ user.created_at_format }}</div>
                        <!-- <div class="text-success">{{ user.last_access_at_format }}</div>
                        <div class="text-danger">{{ user.point_deadline_at_format }}</div> -->
                    </td>
                </tr>
            </tbody>
        </table>




    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({

        users:  { type: [Array,Object], default: null },

    });


    /* 初回データ取得 */
    onMounted(() => { });

</script>
