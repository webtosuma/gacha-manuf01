<template>
    <div :class="style_class" class="ratio-image-parent-component">

        <div v-if="!loading"
        class="ratio-image-component"
        :style="
         'background-image: url('+ url +');'
         +
         'background-size:' + (bg_size=='contain'?'contain':'cover') + ';'
        "
        ></div>


        <div v-else
        class="bg-secondary d-flex align-items-center justify-content-center">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        props: {

            // 画像URL
            url:{ type: String, default: '', },

            //クラス
            style_class:{ type: String, default: 'ratio ratio-1x1', },

            bg_size:{ type: String, default: 'cover',},

        },
        data() { return{
            /* 読み込み中 */
            loading: true,

        } },
        mounted() {

            /* 読み込み完了 */
            this.loading = false;

        },
        methods:  {/* ~ */},
    }
</script>
<style  scoped>
    .ratio-image-parent-component
    {
        overflow: hidden;
    }
    .ratio-image-component
    {
        background-repeat  : no-repeat;
        /* background-size    : cover; */
        background-position: center center;
        width: 100%;
    }
    /* トレカの比率 */
    /* .ratio-3x4 {
        --bs-aspect-ratio: 133.3%;
    } */

</style>

