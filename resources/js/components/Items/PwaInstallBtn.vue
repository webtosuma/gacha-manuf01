<template>
    <div>
      <!-- Chromeなどでbeforeinstallpromptが使える場合 -->
      <div v-if="ready" class="py-3">
        <button @click="handleClickInstall" class="btn btn-lg btn-light border rounded-pill w-100">
          アプリをインストール <i class="bi bi-box-arrow-in-down ms-3"></i>
        </button>
        <a :href="r_about_pwa" style="font-size:11px;" class="mt-2" target="_blank">
          <i class="bi bi-question-circle me-2"></i>プログレッシブ ウェブアプリ（PWA）について
        </a>
      </div>

      <!-- Safariなど、インストールイベントが発火しないブラウザ向け -->
      <div v-else-if="isSafari" class="py-3">
        <button @click="showSafariInstructions" class="btn btn-lg btn-light border rounded-pill w-100">
          ホーム画面に追加する方法 <i class="bi bi-info-circle ms-3"></i>
        </button>
        <div v-if="showInstructions" class="mt-2 text-muted" style="font-size: 13px;">
          <i class="bi bi-hand-index-thumb me-2"></i>
          Safariの共有メニューから「ホーム画面に追加」を選んでください。
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      r_about_pwa: { type: String, default: '' },
    },
    data() {
      return {
        deferredPrompt: null,
        ready: false,
        isSafari: false,
        showInstructions: false,
      };
    },
    mounted() {
      // Safari検出
      const ua = window.navigator.userAgent;
      this.isSafari = /^((?!chrome|android).)*safari/i.test(ua);

      // ChromeなどのPWA対応ブラウザ向け
      window.addEventListener("beforeinstallprompt", this.beforeInstallPromptHandler);
    },
    beforeDestroy() {
      window.removeEventListener("beforeinstallprompt", this.beforeInstallPromptHandler);
    },
    methods: {
      beforeInstallPromptHandler(event) {
        event.preventDefault(); // Chromeの標準動作をキャンセル
        this.ready = true;
        this.deferredPrompt = event;
      },
      handleClickInstall() {
        if (this.deferredPrompt) {
          this.deferredPrompt.prompt();
        }
      },
      showSafariInstructions() {
        this.showInstructions = !this.showInstructions;
      }
    }
  };
  </script>
