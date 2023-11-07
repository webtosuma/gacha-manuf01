<template>
    <div>
        <div class="input-group border" style=" border-radius:5px;">
            <input type="text" class="form-control border-0 bg-light"
            :value="copy_word"  disabled >




            <button v-if=" !disabled "
            class="btn bg-white btn-sm" type="button"
            data-bs-toggle="tooltip" data-bs-placement="bottom" title="URLのコピー"
            @click="copy(copy_word)"
            ><i class="bi bi-files"></i>コピー
            </button>

            <button v-else disabled
            class="btn btn-sm bg-success text-white" type="button"
            ><i class="bi bi-check"></i>　完了
            </button>

        </div>
    </div>
</template>

<script>
    export default {
        data() { return{
            disabled: false,

        } },
        props: {
            copy_word: { type: String, default: '',},
        },
        methods:{

            copy : function(text) {
                navigator.clipboard.writeText(text)
                .then(() => {
                    this.disabled = true;
                    window.setTimeout( this.disabled_false , 2000);
                })
                .catch(e => {
                    console.error(e)
                })
            },

            disabled_false: function(){ this.disabled = false }

        }
    }
</script>
