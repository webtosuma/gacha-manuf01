<template>
    <div v-if="ready" class="py-3">
        <button @click="handleClickInstall"
        class="btn btn-lg btn-light border rounded-pill w-100">アプリをインストール<i class="bi bi-box-arrow-in-down ms-3"></i></button>

        <a href="https://note.com/cardfesta/n/nd0b9e0fdcc15" style="font-size:11px;" class="mt-2" target="_blank"
        ><i class="bi bi-question-circle me-2"></i>プログレッシブ ウェブアプリ（PWA）について</a>
    </div>
</template>

<script>
    export default {
        data() { return {
            deferredPrompt: null,
            ready: false,
            // ready: true,
        }; },
        mounted() {
            window.addEventListener("beforeinstallprompt", this.beforeInstallPromptHandler);
        },
        beforeDestroy() {
            window.removeEventListener("beforeinstallprompt", this.beforeInstallPromptHandler);
        },
        methods: {
            beforeInstallPromptHandler(event) {
                this.ready = true;
                this.deferredPrompt = event;
            },
            handleClickInstall() {
                if (this.deferredPrompt) {
                // インストールプロンプトを表示する
                this.deferredPrompt.prompt();
                }
            }
        }
    };
</script>
