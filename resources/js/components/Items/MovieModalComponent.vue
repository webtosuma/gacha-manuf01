<template>
    <div>
      <button
        @click="play"
        type="button"
        class="btn btn-light border"
        data-bs-toggle="modal"
        :data-bs-target="'#movieModal' + id"
      >
        <i class="bi bi-play-circle"></i>{{ btn_label }}
      </button>

      <!-- Modal -->
      <div
        class="modal fade"
        :id="'movieModal' + id"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        :aria-labelledby="'movieModal' + id + 'label'"
        aria-hidden="true"
      >
        <div
          class="modal-dialog modal-dialog-centered"
          :style="'max-width:' + max_width + ';'"
        >
          <div class="modal-content">
            <div class="modal-header bg-dark text-white">
              <h5
                :id="'movieModal' + id + 'label'"
                class="modal-title"
              >
                {{ title }}
              </h5>
              <button
                @click="pause"
                type="button"
                class="btn text-white"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
            <div class="modal-body bg-dark">
              <div v-if="isYouTube">
                <div :id="'youtube-player-' + id"></div>
              </div>
              <video
                v-else
                :id="'video' + id"
                playsinline
                controls
                width="100%"
                poster=""
              >
                <source :src="src" />
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
      id: { type: String, default: '' },
      btn_label: { type: String, default: '' },
      title: { type: String, default: '' },
      src: { type: String, default: '' },
      max_width: { type: String, default: '800px' },
    },
    data() {
      return {
        video: null,
        player: null,
        apiLoaded: false,
      };
    },
    computed: {
      isYouTube() {
        return this.src.includes('youtube.com');
      },
      youtubeVideoId() {
        try {
          const url = new URL(this.src);
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
      if (this.isYouTube) {
        this.loadYouTubeAPI();
      }
    },
    methods: {
      loadYouTubeAPI() {
        // すでに API が読み込まれている場合
        if (window.YT && window.YT.Player) {
          this.apiLoaded = true;
          return;
        }

        // 初回読み込みの場合のみスクリプトを挿入
        if (!window.YTReadyCallbacks) {
          window.YTReadyCallbacks = [];

          const tag = document.createElement('script');
          tag.src = 'https://www.youtube.com/iframe_api';
          const firstScriptTag = document.getElementsByTagName('script')[0];
          firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

          // APIが読み込まれたらすべてのコールバックを実行
          window.onYouTubeIframeAPIReady = () => {
            window.YTReadyCallbacks.forEach((cb) => cb());
            window.YTReadyCallbacks = [];
          };
        }

        // このコンポーネント用の初期化処理を登録
        window.YTReadyCallbacks.push(() => {
          this.apiLoaded = true;
        });
      },

      play() {
        if (this.isYouTube) {
          if (!this.apiLoaded) {
            this.loadYouTubeAPI();
            return;
          }

          if (this.player) {
            this.player.playVideo();
          } else {
            this.player = new window.YT.Player(`youtube-player-${this.id}`, {
              height: '400',
              width: '100%',
              videoId: this.youtubeVideoId,
              playerVars: {
                autoplay: 1,
                controls: 1,
                mute: 0,
              },
              events: {
                onReady: (event) => {
                  event.target.unMute();
                  event.target.playVideo();
                },
              },
            });
          }
        } else {
          this.video = document.getElementById('video' + this.id);
          if (this.video) {
            this.video.play();
          }
        }
      },

      pause() {
        if (this.isYouTube && this.player) {
          this.player.pauseVideo();
        } else if (this.video) {
          this.video.pause();
        }
      },
    },
  };
  </script>
