<template>
    <div v-if="loading">
        <div class="modal-backdrop faid show">
            <div class="d-flex align-items-center gap-3 justify-content-center h-100">


                <div class="spinner-grow text-dark" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-dark" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-dark" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>


            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';

    const props = defineProps({
        loading:{ type: [Number,Boolean], default: true, },
    });

    const body = ref("I'm an example component.");

    onMounted( () => {
        console.log('Component mounted step.')
    } );
</script>
