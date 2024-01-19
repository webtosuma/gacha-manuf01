<div class="row p-3 border-bottom">
    <div class="col">


        <!-- アカウント画像 -->
        <div style="width:8rem;">
            <ratio-image-component
            style_class="rounded-circle ratio ratio-1x1 border"
            url="{{$user->image_path}}"
            ></ratio-image-component>
        </div>


    </div>
    <div class="col">

        <div class="my-2">
            ID：{{ $user->id }}
            <a href="{{ route('admin.user.show',$user) }}" class="ms-3">{{ $user->name }}</a>
        </div>
        <div class="my-2">
            <h6>メールアドレス</h6>
            <div class="col-md-">
                <coppy-button-component copy_word="{{$user->email}}"></coppy-button-component>
            </div>
        </div>
        <div class="my-2">
            <h6>X(旧twitter)ID</h6>
            <div class="col-md-">
                <coppy-button-component copy_word="{{$user->twitter_id}}"></coppy-button-component>
            </div>
        </div>


    </div>
</div>
