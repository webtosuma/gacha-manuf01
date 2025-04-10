<template>
    <div class="">


        <button @click="play()"
        type="button"
        class="btn btn-light border" data-bs-toggle="modal"
        :data-bs-target="'#movieModal'+id"
        ><i class="bi bi-play-circle"></i>{{ btn_label }}</button>



        <!-- Modal -->
        <div class="modal fade" :id="'movieModal'+id"
        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        :aria-labelledby="'movieModal'+id+'label'" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"
            :style="'max-width:'+max_width+';'">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">

                        <h5 :id="'movieModal'+id+'label'" class="modal-title"
                        >{{ title }}</h5>

                        <button @click="pause()"
                        type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"
                        ><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="modal-body bg-dark">

                        <video :id="'video'+id"
                        playsinline
                        controls
                        width="100%"
                        poster=""
                        ><source :src="src" />
                        </video>

                    </div>
                </div>
            </div>
        </div>


    </div>
</template>
<script>
    export default {
        props: {

            id:       { type: String, default: '', }, //ID
            btn_label:{ type: String, default: '', }, //
            title:    { type: String, default: '', }, //
            btn_label:{ type: String, default: '', }, //
            src:      { type: String, default: '', }, //
            max_width:{ type: String, default: '800px', }, //

        },
        data(){ return {

            video: null,

        } },
        mounted () {

            this.video = document.getElementById('video'+this.id);

        },
        methods: {

            /* ビデオの再生(モーダルを開ける) */
            play (){ this.video.play(); },

            /* ビデオの停止(モーダルを閉じる) */
            pause (){ this.video.pause();; },

        },
    }
</script>
