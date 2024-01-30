<template>
    <div v-if="card_num===0" class="anima-fadein-bottom">
        <div class="card shadow border-0 w-100 p-3 mb-3 bg-white">
            <div class="card-body">

                <div class="card-body">
                    <h5 class="card-title fw-bold  text-center mb-3">警告</h5>
                    <p class="">
                        一人のユーザーが、アカウントを複数作成することはできません。
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'

    export default {

        props: {

            card_num: { type: Number,  default: 1, },

        },
        data() { return {
            loading: false,/* 通信中 */
        } },
        mounted() {
        },
        methods: {

        }
    }
</script>
