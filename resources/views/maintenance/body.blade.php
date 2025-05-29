<div class="d-flex align-items-center justify-content-center bg-" style="height: 80vh;">
    <div class="text-center p-3">


        {{-- <i class="bi bi-tools display-1 text-warning"></i> --}}
        <h2 class="mb-3 text-center">メンテナンス中</h2>
        <p class="text-center my-5">
            {!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $message) )!!}
        </p>


        @if($show_date)
            <div class="text-center my-5">
                <h5 class="text-danger">メンテナンス予定時間</h5>
                <div class="rounded shadow-sm p-3 bg-light text-dark">
                    <div class="">
                        {{ $start_at ? \Carbon\Carbon::parse($start_at)->format('Y年m月d日 H:i') : '' }}
                    </div>
                    <div class="">〜</div>
                    <div class="">
                        {{ $end_at ? \Carbon\Carbon::parse($end_at)->format('Y年m月d日 H:i') : '未定'}}
                    </div>
                </div>
            </div>
        @endif




        {{-- <div class="text-center mt-5">
            <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}"
            class="d-brock" style="height:4rem;">
        </div> --}}


        <!--SNS Links-->
        @include('includes.sns_links')


    </div>
</div>


{{-- <replace-text-component text="{{ $body }}"></replace-text-component> --}}

