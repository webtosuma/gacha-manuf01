<template>
    <div class="d-inline-block">
        <!-- Button trigger modal -->
        <button type="button" :class="button_class"
        data-bs-toggle="modal" :data-bs-target="'#deleteModal'+ indexKey "
        >{{ button_text }}<i v-if="button_text=='' " class="bi" :class="icon" ></i></button>

        <!-- Modal -->
        <div class="modal fade"
        :id="'deleteModal'+ indexKey " :aria-labelledby="'deleteModalLabel'+ indexKey " tabindex="-1" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header justify-content-center border-0 pb-0">

                        <!--icon-->
                        <h5 id="AlertModalLabel" class="modal-title" style="font-size: 6rem;">
                            <i class="bi" :class="icon +' text-'+ color"></i>
                        </h5>

                    </div>
                    <div class="modal-body text-center fs-5">

                        <!-- <replace-text-component text="" /> -->
                        <slot></slot>

                    </div>
                    <div class="modal-footer border-0">

                        <div class="col">
                            <button @click="parentFunc"
                            type="button" :class="'btn-'+ color" class="btn text-white w-100"
                            data-bs-dismiss="modal"
                            >OK</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-secondary w-100"
                            data-bs-dismiss="modal"
                            >閉じる</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    export default{
        props: {
            indexKey:    { type: [String,Number], default: '1',},
            icon:        { type: String, default: 'bi-exclamation-triangle',},
            color:       { type: String, default: 'danger',},
            button_text: { type: String, default: '',},
            button_class:{ type: String, default: 'btn btn-primary',},
        },
        data(){ return {　} },
        mounted() { },
        methods: {
            /** 親コンポーネント関数の実行 */
            parentFunc() {

                //モーダルカバーの削除
                var modalBackdrop = document.querySelector('.modal-backdrop');
                if (modalBackdrop) {
                    modalBackdrop.parentNode.removeChild(modalBackdrop);
                }

                this.$emit('parent-func');
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
