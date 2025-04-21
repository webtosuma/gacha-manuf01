<template>
    <div class="mx-auto overflow-hidden" style="height:100vh; max-width:100vw;">

        <loading-cover-component :loading="loading" />

        <div class="d-flex align-items-center h-100 w-100">
            <div class="section_video mx-auto">
                <div class="video-area">
                    <!-- プレイヤー読み込み完了後にフェードイン -->
                    <transition name="fade">
                        <div :id="'youtube-player-' + id" style="height:100vh; transform: scale(1.6);"></div>
                    </transition>
                </div>
            </div>

            <!-- 操作ボタン系 -->
            <div class="position-fixed top-0 start-0 p-3 w-100 h-100">
            <div class="d-flex justify-content-between align-items-center">

                <!-- 音声切り替えボタン -->
                <button @click="switchMuted" class="btn bg-dark text-white btn-sm py-0 fs-3" id="muteButton">
                <i v-if="!muted" class="bi bi-volume-up-fill"></i>
                <i v-else class="bi bi-volume-mute-fill"></i>
                </button>

                <!--スキップボタン-->
                <form :action="r_action" method="get">
                <input v-if="rank_up == 1" type="hidden" name="rank_up" :value="rank_up" />
                <button v-if="time == 0" type="submit" class="btn bg-dark text-white btn-sm py-0">
                    <div class="d-flex justify-content-center align-items-center">
                    <span>スキップ</span>
                    <i class="bi bi-skip-end-fill fs-3"></i>
                    </div>
                </button>
                <div v-else class="d-flex align-items-center justify-content-center rounded-pill border bg-light" style="width:2rem; height:2rem;">
                    <div>{{ time }}</div>
                </div>
                </form>
            </div>
            </div>

            <!-- URLボタン -->
            <div v-if="r_redirect" class="position-fixed bottom-0 end-0 p-3 pb-4 w-100">
            <div class="col-12 col-md-6 mx-auto">
                <a :href="r_redirect" target="_blank" class="btn btn-light border w-100">{{ redirect_text }}</a>
            </div>
            </div>

            <!-- 音声コンフォーム -->
            <confirm-modal-component @click-ok="autoPlay(false)" @click-no="autoPlay(true)" body="音声が出ます。よろしいですか？" icon="" />
        </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      token: { type: String, default: '' },
      movie_path_mobile: { type: String, default: '' },
      r_action: { type: String, default: '' },
      r_redirect: { type: String, default: '' },
      redirect_text: { type: String, default: 'このサイトを見る' },
      rank_up: { type: [String, Number], default: '0' },
      max_time: { type: [String, Number], default: 0 },
    },
    data() {
      return {
        time: 0,
        timer: null,
        muted: true,
        player: null,
        apiLoaded: false,
        id: 'ytPlayer',
        loading: true,
        redirectCountdownStarted: false,
      };
    },
    computed: {
      youtubeVideoId() {
        try {
          const url = new URL(this.movie_path_mobile);
          if (url.pathname.startsWith('/shorts/')) {
            return url.pathname.split('/shorts/')[1];
          }
          return url.searchParams.get('v');
        } catch (e) {
          return null;
        }
      },
    },
    mounted() {

        this.loadYouTubeAPI();
        this.time = this.max_time;

    },
    methods: {
      loadYouTubeAPI() {
        if (window.YT && window.YT.Player) {
          this.apiLoaded = true;
          return;
        }
        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        window.onYouTubeIframeAPIReady = () => {
          this.apiLoaded = true;
        };
      },

      autoPlay(muted = false) {
        this.muted = muted;
        if (!this.apiLoaded || this.player) return;

        this.player = new window.YT.Player(`youtube-player-${this.id}`, {
            height: '360',
            width: '100%',
            videoId: this.youtubeVideoId,
            playerVars: {
                autoplay: 0,
                controls: 0,
                rel: 0,
                modestbranding: 1,
            },
            events: {
                onReady: (event) => {
                    if (this.muted) {
                        event.target.mute();
                    } else {
                        event.target.unMute();
                    }
                    event.target.playVideo();
                    this.startTimer();

                },
                onStateChange: (event) => {
                    /*動画をロードしたとき、読み込み中の解除*/
                    if (event.data === window.YT.PlayerState.PLAYING && !this.playerReady) {
                        this.loading = false;
                    }
                    /*動画の再生が終了したとき、読み込み中・リダイレクト*/
                    // if (event.data === window.YT.PlayerState.ENDED) {
                    //     this.loading = true;
                    //     document.querySelector('form')?.submit();
                    // }
                    /*動画の再生が終了したとき、読み込み中・リダイレクト*/
                    if (event.data === window.YT.PlayerState.PLAYING && !this.redirectCountdownStarted) {

                        // 動画終了1秒前に自動送信するタイマー
                        this.redirectCountdownStarted = true;
                        this.checkRemainingTime();
                    }
                },
            },
        });
      },

        switchMuted() {
            this.muted = !this.muted;
            if (this.player) {
            if (this.muted) {
                this.player.mute();
            } else {
                this.player.unMute();
            }
            }
        },

        startTimer() {
            this.timer = setInterval(() => {
                if (this.time > 0) {
                    this.time--;
                } else {
                    clearInterval(this.timer);
                }
            }, 1000);
        },

        /*動画終了1秒前に自動送信するタイマー*/
        checkRemainingTime() {
            const check = () => {
                if (!this.player || typeof this.player.getDuration !== 'function') return;

                const duration = this.player.getDuration();
                const currentTime = this.player.getCurrentTime();
                const remaining = duration - currentTime;

                if (remaining <= 0.5) {
                    this.loading = true;
                    document.querySelector('form')?.submit();
                } else {
                    // 200msごとに再チェック
                    setTimeout(check, 200);
                }
            };
            check();
        }


    },
  };
  </script>

  <style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>
