<template>
    <div class="">

        <u-shipped-children01form
        :token="token" :r_store="r_store" />

        <u-shipped-children02form />
        <u-shipped-children03form />


    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        props: {
            token:{ type: String,  default: '', },
            r_store: { type: [String,Number], default: null },
        },
        data() { return {

        } },
        mounted() {


        },
        methods: {


        },
    }
</script>
