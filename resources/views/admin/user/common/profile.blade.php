<div class="row p-3 border-bottom mb-5 justify-content-center gx-5">
    <div class="col-12 col-md">


        <!-- アカウント画像 -->
        <div class="mx-auto" style="width:8rem;">
            <ratio-image-component
            style_class="rounded-circle ratio ratio-1x1 border"
            url="{{$user->image_path}}"
            ></ratio-image-component>
        </div>


    </div>
    <div class="col-12 col-md-8">

        <div class="my-3">
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
        <div class="my-2">
            <h6>会員登録日</h6>
            <div class="border p-2 rounded">{{$user->created_at->format('Y年m月d日 H:i') }}</div>
        </div>

        @if($user->recruiter)
        <div class="my-2">
            <h6>紹介元ユーザー</h6>
            <div class="border p-2 rounded">
                ID：{{ $user->recruiter->id }}
                <a href="{{ route('admin.user.show',$user->recruiter) }}" class="ms-3">{{ $user->recruiter->name }}</a>
            </div>
        </div>
        @endif


    </div>
</div>
