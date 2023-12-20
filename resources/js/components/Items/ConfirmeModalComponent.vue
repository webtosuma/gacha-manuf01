<template>
    <div v-if="show">


        <div id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" class="modal fade show" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered anima-fadein-alert">
                <div class="modal-content">
                    <div class="modal-header justify-content-center border-0 pb-0">

                        <h5 id="AlertModalLabel" class="modal-title" style="font-size: 6rem;">
                            <i class="bi" :class="icon+' text-'+color"></i>
                        </h5>

                    </div>
                    <div class="modal-body text-center fs-5">

                        <replace-text-component :text="body " />

                    </div>
                    <div class="modal-footer border-0">
                        <div class="row g-2 w-100">
                            <div class="col-6">
                                <button @click="clickNo()"
                                type="button"  class="btn btn-light borderr text-dark w-100">いいえ</button>
                            </div>
                            <div class="col-6">
                                <button @click="clickOk()"
                                type="button" :class="'btn-'+color" class="btn text-white w-100">はい</button>
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

</style>
