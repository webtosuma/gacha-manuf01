<template>
    <div class="">

        <!-- 動画 -->
        <div v-if="src" class="mb-2">
            <video
            :key="src"
            class="bg_video"
            playsinline
            controls
            width="100%"
            poster="">
                <source :src="src" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>

        <!--ファイル　インプット-->
        <div class="input-group mb-3">
            <input type="file" :name="name" class="form-control" :id="'file_input'+name"
            style="padding:.4rem;"
            @change="onChange"
            >
            <label class="input-group-text text-white" :class="{'bg-danger': delete_radio==null}"
            data-bs-toggle="tooltip" data-bs-placement="bottom" title="画像の削除"
            :for="name+'_dalete'"><i class="bi bi-x-lg"></i></label>
        </div>

        <div class="form-text">※ファイルは8Mバイト以内で、mp4・movのいずれかの形式を選択してください。</div>

        <!-- delete(hidden) -->
        <div class="form-check d-none">
            <input
            v-model="delete_radio"
            @change="delete_image"
            class="form-check-input" type="radio"
            :name="name+'_dalete'" :id="name+'_dalete'"
            value="delete" >
        </div>

        <!-- message -->
        <div class="form-text text-danger">{{err_message}}</div>


    </div>
</template>

<script>
    export default {
        props: {

            video_path:   { type: String, default: '', }, //表示動画のパス
            no_video_path: { type: String, default: '', }, //動画無しのパス
            name:       { type: String, default: 'image', }, //インプット要素のname名

        },
        data() { return{

            /* 表示画像のソース */
            src: null,

            /* エラーメッセージ */
            err_message: '',

            /* 削除 */
            delete_radio: null,

        } },
        mounted() {

            this.src = this.video_path !== '' ? this.video_path : this.no_video_path;
            this.delete_radio = this.video_path == this.no_video_path ? 'delete' : null;

        },
        methods:{

            onChange(event) {

                const file = event.target.files[0];
                const input_file = document.getElementById('file_input'+ this.name);

                if(
                    //ファイル形式
                    ( file.type === 'video/mp4' || file.type === 'video/mov' ) &&
                    // file.type === 'video/mp4'
                    //ファイルサイズ
                    file.size < 8*1000*1000

                ){
                    this.src = URL.createObjectURL(file); //表示画像の変更
                    this.err_message = ''

                    // 削除チェックを外す
                    this.delete_radio = null;
                }else{
                    this.src = this.img_path;
                    this.err_message = '※エラー：ファイルサイズか形式が異なります。'
                    input_file.value = ''; //インプット要素内を空にする。
                }


            },

            delete_image: function(){

                this.src = this.no_video_path;
                this.delete_radio = 'delete';

                const input_file = document.getElementById('file_input'+ this.name);
                input_file.value = ''; //インプット要素内を空にする。
            },

        }
    }
</script>
