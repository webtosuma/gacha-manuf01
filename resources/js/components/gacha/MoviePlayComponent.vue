<template>
    <div class="mx-auto overflow-hidden" style="height:100vh; max-width:100vw;">
        <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-">



            <div class="section_video mx-auto w-100 h-100">
                <!-- 動画mobile -->
                <div class="video-area d-flex align-items-center justify-content-center h-100 ">
                    <video class="bg_video h-100"
                    playsinline
                    :muted="muted"
                    poster=""
                    style="object-fit: cover;"
                    ><source :src="movie_path_mobile" />
                    </video>
                </div>
            </div>



            <div class="position-fixed top-0 start-0 p-3 w-100">
                <div class="d-flex justify-content-between align-items-center">

                    <!-- 音声切り替えボタン -->
                    <button @click="switchMuted()"
                    class="btn btn-dark btn-sm py-0 fs-3"
                    id="muteButton" >
                        <i v-if="!muted" class="bi bi-volume-up-fill"></i>
                        <i v-else class="bi bi-volume-mute-fill"></i>
                    </button>


                    <!--スキップボタン-->
                    <form :action="r_action" method="get">
                        <input v-if="rank_up==1" type="hidden" name="rank_up" :value="rank_up">

                        <button v-if="time==0"
                        type="submit"
                        class="btn btn-dark btn-sm py-0">
                            <div class="d-flex justify-content-center align-items-center">
                                <span>演出をスキップ</span>
                                <i class="bi bi-skip-end-fill fs-3"></i>
                            </div>
                        </button>
                        <div v-else
                        class="d-flex align-items-center justify-content-center rounded-pill border bg-light"
                        style="width:2rem; height:2rem;">
                            <div>{{ time }}</div>
                        </div>
                    </form>


                </div>
            </div>


            <!--URLボタン-->
            <div v-if="r_redirect"
            class="position-fixed bottom-0 end-0 p-3 pb-4 w-100 ">
                <div class="col-12 col-md-6 mx-auto">
                    <a :href="r_redirect"  target="_blank"
                    class="btn btn-dark border fs- w-100"
                    >{{ redirect_text }}</a>
                </div>
            </div>

            <!--音声コンフォーム-->
            <!-- <confirm-modal-component
            @click-ok="autoPlay(false)"
            @click-no="autoPlay(true)"
            body="音声が出ます。よろしいですか？" icon="" /> -->

            <!--音声コンフォーム-->
            <u-movie-confirm-modal-component
            @click-ok="autoPlay(false)"
            @click-no="autoPlay(true)"/>

        </div>
    </div>
</template>
<script>
    export default {
        props: {
            token:{ type: String,  default: '', },
            movie_path_mobile:{ type: String,  default: '', },
            r_action:{ type: String,  default: '', },
            r_redirect:{ type: String,  default: '', },
            redirect_text:{ type: String,  default: 'このサイトを見る', },
            rank_up:{ type: [String,Number],  default: '0', },
            max_time:{ type: [String,Number],  default: 0, },
        },
        data() { return {

            videos : '',
            form   : '',
            time   : 0,
            timer  : null,

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
                this.time   = this.max_time;
            },

            /** 自動再生 */
            autoPlay(muted=false) {

                this.muted = muted;

                this.videos.forEach(video => {

                    // 動画が再生された後にフォーム送信
                    const form = this.form;
                    video.addEventListener('ended', function() {

                        // フォーム送信　*広告の時は不要
                        form.submit();

                    });

                    // メディアの再生を開始
                    video.play();

                    // タイマーの再生
                    this.startTimer();

                    this.moviePlaying = true;
                });
            },


            /** 音声切り替え */
            switchMuted() { this.muted = !this.muted; },


            /** カウントダウンタイマー **/
            startTimer() {
                this.timer = setInterval(() => {
                    if (this.time > 0) {
                        this.time--;
                    } else {
                        clearInterval(this.timer);
                    }

                    console.log('hoge');
                }, 1000);
            },


        }

    };

</script>
