<template>
    <div v-if="show">


        <div id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" class="modal fade show" aria-modal="true" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered anima-fadein-alert">
                <div class="modal-content"
                :class="dark==1?'dark-modal':''">

                    <div class="modal-header justify-content-center border-0 pb-0">

                        <h5 id="AlertModalLabel" class="modal-title" style="font-size: 6rem;">
                            <i class="bi" :class="icon+' text-'+color"></i>
                        </h5>

                    </div>
                    <div class="modal-body text-center fs-5">

                        <slot></slot>


                        <!-- <replace-text-component :text="body " /> -->

                    </div>
                    <div class="modal-footer border-0">

                        <div class="col-12">
                            <button @click="fadeAlertComp()"
                            type="button" :class="'btn-'+color" class="btn text-white w-100"
                            >{{ btn_text }}</button>
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
            color:   { type: String, default: '',},
            body:    { type: String, default: '',},
            icon:    { type: String, default: 'bi-check-circle',},
            btn_text:{ type: String, default: '閉じる',},
            dark:    { type: [String,Number], default: 0,},
        },
        mounted() {
            this.show = this.color!='';
        },
        data(){ return {

            show: false,

         } },
        methods: {
            fadeAlertComp() { this.show = false; },
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


    .dark-modal{
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
    }
</style>
