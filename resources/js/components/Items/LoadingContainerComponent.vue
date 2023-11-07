<template>
    <div class="h-100">
        <div v-if="loading"
        class="d-flex align-items-center justify-content-center  h-100">

            <!--loding-->
            <div class="spinner-border text-secondary my-5" role="status">
                <span class="visually-hidden ">Loading...</span>
            </div>

        </div>
        <div v-else class="h-100">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {

            loading:{ type: Boolean, default: true, }, //

        },
        data : function() {
            return{/* ~ */}
        },
        mounted() {/* ~ */},
        methods:  {/* ~ */}
    }
</script>
<style scoped>
/* ここにscssを書く */
/* "lang=scss"を取り除くとcssで書ける */
</style>
