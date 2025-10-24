<template>

    <nav v-if="pagenate.links.length > 3" aria-label="Pagination">
        <ul class="pagination justify-content-start">
            <li
            v-for="(link, index) in pagenate.links"
            :key="index"
            class="page-item"
            :class="{
                active: link.active,
                disabled: !link.url
            }"
            >
                <a
                v-if="link.url"
                class="page-link"
                href="#"
                v-html="linkLavel( link.label )"
                @click.prevent="changeData( link.url  )"
                ></a>
                <span v-else class="page-link" v-html="linkLavel( link.label )"></span>
            </li>
        </ul>
        <div v-if="data">{{ data.length }}件表示</div>
    </nav>

</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({

        pagenate: { type: [Object,Array], default: {
            current_page :0,
            links: {}
        } },

        data: { type: [String,Object,Array], default: null },

    });


    const emit = defineEmits(['cahnge-data']);

    /* ページの移動 */
    const changeData = (url) => {
        emit('cahnge-data',url);
    };

    /* ページネーションラベルのカスタマイズ */
    const linkLavel = label => {

        if(label=='pagination.next'){     return '>>'; }

        if(label=='pagination.previous'){ return '<<'; }

        return label;
    };


</script>
