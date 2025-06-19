<template>
    <div class="border p-3 rounded-4">

        <p class="fs-3">＊カードをドラックしながら移動し、並び替えを行ってください。</p>

        <ul class="list-group list-group-flush">

            <!-- 一覧 -->
            <li v-for="(item, index) in items" :key="item.id"
            class="list-group-item border-0 bg-white  px-0">


                <!---入力-->
                <div
                class="item card rounded-4 fs-5"
                :class="{ dragging: index === draggedIndex || index === touchDragIndex }"
                draggable="true"
                @dragstart="onDragStart($event, index)"
                @dragover="onDragOver($event, index)"
                @drop="onDrop($event, index)"
                @touchstart="onTouchStart($event, index)"
                @touchmove="onTouchMove($event)"
                @touchend="onTouchEnd($event, index)"
                >
                    <div class="row g-0 mx-0 py-1 align-items-center">

                        <!-- name -->
                        <div class="col">
                            <input type="hidden" name="category_ids[]" :value="item.id">
                            <div class="p-3">{{ item.name }}</div>
                        </div>
                    </div>
                </div>






            </li>
        </ul>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({
        token:       { type: String, default: '' },
        r_api_list:  { type: String, default: '' },
    });

    const items = ref([]);  /* データリスト */

    // 並び替え変数
    const draggedIndex   = ref(null);
    const touchDragIndex = ref(null);
    const touchStartY    = ref(0);
    const touchCurrentY  = ref(0);


    /* 初回データ取得 */
    onMounted(() => { getData(); });


    /* データ取得 */
    const getData = async (route = props.r_api_list) => {
        const inputs = { _token: props.token, };

        try {

            const response   = await axios.post(route, inputs);
            items.value = response.data;


        } catch (error) {

            console.error(error.response?.data);

            if (confirm('通信エラーが発生しました。再読み込みを行いますか？')) {
                location.reload();
            }

        }
    };




    /** ドラッグ操作が開始されたときに呼び出されます。ドラッグされるアイテムのインデックスを保存し、ドラッグ効果を設定します。 */
    const onDragStart = (event, index) => {
        draggedIndex.value = index;
        event.dataTransfer.effectAllowed = 'move';
    };


    /** ドラッグ中にドラッグ対象のアイテムが他のアイテム上にあるときに呼び出されます。ドロップを許可するためにデフォルトの動作を無効にします。 */
    const onDragOver = (event, index) => {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
    };


    /* ドロップ操作が完了したときに呼び出されます。ドラッグされたアイテムを新しい位置に移動します。 */
    const onDrop = (event, index) => {
        event.preventDefault();
        if (draggedIndex.value !== null && draggedIndex.value !== index) {
          const draggedItem = items.value[draggedIndex.value];
          items.value.splice(draggedIndex.value, 1);
          items.value.splice(index, 0, draggedItem);
          draggedIndex.value = null;
        }
    };


    /** タッチが開始されたときに呼び出されます。タッチされたアイテムのインデックスと開始位置を保存します。 */
    const onTouchStart = (event, index) => {
        touchDragIndex.value = index;
        touchStartY.value = event.touches[0].clientY;
    };


    /** タッチ移動中に呼び出されます。アイテムの位置をタッチ移動に合わせて更新します。 */
    const onTouchMove = (event) => {
        touchCurrentY.value = event.touches[0].clientY;
        const moveDistance = touchCurrentY.value - touchStartY.value;
        event.currentTarget.style.transform = `translateY(${moveDistance}px)`;
    };

    /** タッチが終了したときに呼び出されます。ドラッグされたアイテムを新しい位置に移動します。 */
    const onTouchEnd = (event, index) => {
        const moveDistance = touchCurrentY.value - touchStartY.value;
        event.currentTarget.style.transform = '';
        const targetIndex = touchDragIndex.value + Math.round(moveDistance / event.currentTarget.clientHeight);
        if (targetIndex !== touchDragIndex.value && targetIndex >= 0 && targetIndex < items.value.length) {
          const draggedItem = items.value[touchDragIndex.value];
          items.value.splice(touchDragIndex.value, 1);
          items.value.splice(targetIndex, 0, draggedItem);
        }
        touchDragIndex.value = null;
        touchStartY.value = 0;
        touchCurrentY.value = 0;
    };


    /** ドラッグ操作が終了したときに呼び出されます。draggedIndex を null にリセットして、ドラッグ中のスタイルを解除します。 */
    const onDragEnd = () => {
        draggedIndex.value = null;
    };

</script>
<style scoped>
    .item {
        cursor: grab;
        touch-action: none; /* タッチイベントのデフォルト動作を無効化 */
    }
    .dragging {
        z-index: 10;
        position: relative; /* z-index を有効にするために position を設定 */
    }
</style>
