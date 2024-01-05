<template>
    <div class="mx-auto" style="height:100vh; min-width:100vw;">
        <div class="d-flex align-items-center align-items-center h-100 w-100 bg-">

            <div class="section_video w-100">
                <!-- 動画mobile -->
                <div class="video-area d-md-none">
                    <video class="bg_video"
                    playsinline
                    :muted="muted"
                    width="100%"
                    poster=""
                    ><source :src="movie_path_mobile" />
                    </video>
                </div>
                <!-- 動画PC -->
                <div class="video-area d-none d-md-block">
                    <video class="bg_video"
                    playsinline
                    :muted="muted"
                    width="100%"
                    poster=""
                    ><source :src="movie_path_pc" />
                    </video>
                </div>
            </div>


            <!-- 音声切り替えボタン -->
            <div class="position-fixed top-0 start-0 p-3">
                <button @click="switchMuted()"
                class="btn btn-light btn-sm py-0 fs-3"
                id="muteButton" >

                    <i v-if="!muted" class="bi bi-volume-up-fill"></i>
                    <i v-else class="bi bi-volume-mute-fill"></i>
                </button>
            </div>


            <!--スキップボタン-->
            <div class="position-fixed bottom-0 end-0 p-3">
                <form :action="r_action" method="get">
                    <!-- <input type="hidden" name="_token" :value="token"> -->
                    <button type="submit"
                    class="btn btn-light btn-sm flort-right py-0">
                        <div class="d-flex justify-content-center align-items-center">
                            <span>演出をスキップ</span>
                            <i class="bi bi-skip-end-fill fs-3"></i>
                        </div>
                    </button>
                </form>
            </div>

            <!--音声コンフォーム-->
            <confirm-modal-component
            @click-ok="autoPlay(false)"
            @click-no="autoPlay(true)"
            body="音声が出ます。よろしいですか？" icon="" />

        </div>
    </div>
</template>
<script>
    export default {
        props: {
            token:{ type: String,  default: '', },
            movie_path_mobile:{ type: String,  default: '', },
            movie_path_pc:{ type: String,  default: '', },
            r_action:{ type: String,  default: '', },
        },
        data() { return {

            videos : '',
            form   : '',

            muted: true,
            moviePlaying: false,//動画再生中
        } },
        mounted() {

            this.set();

        },
        methods: {

            set() {
                this.videos = document.querySelectorAll('video');
                this.form   = document.querySelector('form');
            },

            /** 自動再生 */
            autoPlay(muted=false) {

                this.muted = muted;

                this.videos.forEach(video => {

                    // 動画が再生された後にフォーム送信
                    const form = this.form;
                    video.addEventListener('ended', function() {

                        // フォーム送信
                        form.submit();

                    });

                    // メディアの再生を開始
                    video.play();

                    this.moviePlaying = true;
                });
            },


            /** 音声切り替え */
            switchMuted() { this.muted = !this.muted; },
        }

    };

</script>
