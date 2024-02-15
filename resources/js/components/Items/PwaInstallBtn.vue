<template>
    <div v-if="ready" class="py-3">
        <button @click="handleClickInstall"
        class="btn btn-lg btn-light border rounded-pill w-100">アプリをインストール<i class="bi bi-box-arrow-in-down ms-3"></i></button>
    </div>
</template>

<script>
    export default {
        data() { return {
            deferredPrompt: null,
            ready: false
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
