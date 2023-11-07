<template>
    <div class="w-100">

        <div v-if="!notFound">
            <slot></slot>

        </div>
        <div v-else>
            <div class="d-flex flex-column justify-content-center align-items-center" style="min-height: 90vh;">
                <h1>404</h1>
                <h5>お探しのページが見つかりません。</h5>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        props: {

            // notFound
            notFound:{ type: Boolean, default: false, },

        },

    }
</script>
