<template>
  <div :class="style_class" class="ratio-image-parent-component">

    <!-- 画像 -->
    <img
      v-if="url"
      :src="url"
      :alt="alt"
      class="ratio-image-component"
      :class="bg_size === 'contain' ? 'img-contain' : 'img-cover'"
      @load="onLoad"
      @error="onError"
      loading="lazy"
    />

    <!-- ローディング -->
    <!-- <div
      v-if="loading"
      class="bg-secondary d-flex align-items-center justify-content-center position-absolute top-0 start-0 w-100 h-100"
    >
      <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div> -->

  </div>
</template>

<script setup>
  import { ref, watch } from 'vue'
  
  /* Props */
  const props = defineProps({
      url: { type: String, default: '' },
      style_class: { type: String, default: 'ratio ratio-1x1' },
      bg_size: { type: String, default: 'cover' },
      alt: { type: String, default: '' },
  })
  
  /* 状態 */
  const loading = ref(true)
  
  /* URL変更時は再ローディング */
  watch(() => props.url, () => {
      loading.value = true
  })
  
  /* イベント */
  const onLoad = () => {
      loading.value = false
  }
  
  const onError = () => {
      loading.value = false
  }
</script>

<style scoped>
.ratio-image-parent-component {
  overflow: hidden;
  position: relative;
}

.ratio-image-component {
  width: 100%;
  height: 100%;
}

/* cover */
.img-cover {
  object-fit: cover;
}

/* contain */
.img-contain {
  object-fit: contain;
  background: #f5f5f5;
}
</style>