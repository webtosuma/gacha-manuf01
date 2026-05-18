<section class="mb-4">

    <div class="d-flex justify-content-between ">

        <h5 class="fw-bold">お届け先</h5>

        @if(
            Auth::user()->id === $user_address->user_id
            && isset($user_shipped)
            && $user_shipped->state_id==11//未発送
        )
            <a href="{{ route('settings.user_address.edit',$user_address ) }}"
            class="">お届け先住所の変更</a>
        @endif

    </div>

    <div class="card card-body bg-white">




        <input type="hidden" name="user_address_id" value="{{ $user_address->id }}">

        <div class="fw-bold">

            <!--発送住所の変更-->
            @if( isset($user_shipped) && $user_shipped->update_user_address_label  )
                <div class="text-danger mb-3">{{ $user_shipped->update_user_address_label }}</div>
            @endif


            <!--お届け先-->
            @include('shipped.common.user_address')


        </div>

    
    </div>

</section>
