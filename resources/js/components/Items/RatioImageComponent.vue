<template>
    <div :class="style_class" class="ratio-image-parent-component">

        <div class="ratio-image-component" :style="'background-image: url('+ url +');'"></div>

    </div>
</template>

<script>
    export default {
        props: {

            // 画像URL
            url:{ type: String, default: '', },

            //クラス
            style_class:{ type: String, default: 'ratio ratio-1x1', },

        },
        data : function() {
            return{/* ~ */}
        },
        mounted() {/* ~ */},
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
        background-size    : cover;
        background-position: center center;
        width: 100%;
    }
</style>

