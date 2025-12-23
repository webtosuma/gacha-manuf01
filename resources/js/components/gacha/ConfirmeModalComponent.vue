<template>
    <div v-if="show">


        <div id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        class="modal fade show" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered anima-fadein-alert">
                <div class="modal-content bg-transparent">
                    <div class="modal-footer bg-transparent border-0">
                        <div class="col-12 text-white text-center fs-5">
                            演出動画を再生します。
                        </div>
                        <div class="row g-4 w-100">
                            <div class="col-12">
                                <button @click="clickOk()"
                                type="button" :class="'btn-'+color"
                                class="btn btn-lg text-white w-100 shadow rounded-pill btn-pulse"
                                >タップでスタート！</button>
                            </div>
                            <div class="col-12">
                                <button @click="clickNo()"
                                type="button"
                                class="btn btn-dark border-0 w-100 rounded-pill"
                                >音声なしでスタート！</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal-backdrop faid show" style=""></div>


    </div>
</template>
<script>
    export default{
        props: {
            color: { type: String, default: 'primary',},
            body:  { type: String, default: '',},
            icon:  { type: String, default: 'bi-check-circle',},
        },
        mounted() {
            this.show = this.color!='';
        },
        data(){ return {

            show: false,

         } },
        methods: {

            /* はい */
            clickOk() {
                this.show = false;
                this.$emit('click-ok')
            },
            /* いいえ */
            clickNo() {
                this.show = false;
                this.$emit('click-no')
            },

        }
    }
</script>
<style scoped>
    .anima-fadein-alert{
        animation: anima-fadein-alert 1s forwards;
    }
    @keyframes anima-fadein-alert {
        from {
            opacity: 0;
            transform: translateY(1rem);
        }
        to {
            opacity: 1;
            transform: translateY(0rem);
        }
    }


        @keyframes pulse-depth {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
        }

        .btn-pulse {
        animation: pulse-depth 1.4s ease-in-out infinite;
        transform-origin: center;
        }

        /* ホバー時は停止 */
        .btn-pulse:hover {
        animation-play-state: paused;
        }


</style>
