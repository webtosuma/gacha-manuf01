<div class="text-dark">

    @php
    $session_alerts = [ 'alert-primary','alert-success','alert-info','alert-warning','alert-danger','alert-secoundary','alert-dark' ];
    @endphp


    @foreach ($session_alerts as $alert_name)
        @if ( session( $alert_name ) )

            @php
            $body  = session( $alert_name );
            $color = str_replace('alert-','', $alert_name);
            $icon = session( 'icon' ) ? session( 'icon' ) : 'bi-check-circle';
            @endphp
            {{-- @php
            $body  = 'alert-primary';
            $color = 'primary';
            @endphp --}}
            <alert-modal-comp-component color="{{$color}}" icon="{{$icon}}" btn_text="閉じる">
                <replace-text-component text="{{$body}}" ></replace-text-component>
            </alert-modal-comp-component>

        @endif
    @endforeach


    {{-- 新規会委員登録後アラート --}}
    @if( session( 'alert_register' ) )
    {{-- @if( true ) --}}

        @php
        $color = 'success';
        $icon  = 'bi-check-circle';

        $gacha = \App\Models\Gacha::where('type','only_new_user')
        ->where('published_at','<=',now())
        ->where('is_sold_out',0)
        ->orderByDesc('created_at')->first();
        @endphp
        <alert-modal-comp-component color="{{$color}}" icon="{{$icon}}" btn_text="閉じる">

            <h3 class="fw-bold text-success mb-3">会員登録が完了しました！</h5>

            <!-- 新規会委員限定ガチャがるとき -->
            @if( $gacha )
                <div class="alert alert-warning text-dark fw-bold" role="alert">
                    会員登録記念として、<br>一回限定で<br>
                    新規会員限定のガチャを<br>ご利用いただけます！！<br>
                    ぜひお楽しみください！
                </div>

                <div class="my-5 mx-2">

                    <a href="{{$gacha->route}}"
                    class="card border-secondary border-3 shadow bg-white
                    text-dark text-center overflow-hidden text-decoration-none
                    hover_anime" style="border-radius:1rem;">


                        <!--image-->
                        @include('gacha.common.top_image')

                        <!--metter-->
                        @include('gacha.common.metter')

                    </a>
                </div>
            @endif


        </alert-modal-comp-component>

        {{--- 紙吹雪　CDN ---}}
        @include('includes.confetti_js')

    @endif



    {{-- ランクアップアラート：ガチャの結果 --}}
    @if( isset($rank_up) && $rank_up )

        @php
        $color = 'success';
        $icon  = 'bi-check-circle';
        $user = Auth::user();
        @endphp
        <alert-modal-comp-component color="{{$color}}" icon="" btn_text="閉じる">

            <h3 class="fw-bold text-success mb-3">会員ランクが昇格しました！</h5>

            <ratio-image-component
            style_class="ratio ratio-16x9 rounded-3 overflow-hidden
            position-relative shiny"
            url="{{ $user->now_rank->image_path }}"
            ></ratio-image-component>

        </alert-modal-comp-component>

        {{--- 紙吹雪　CDN ---}}
        @include('includes.confetti_js')

    @endif


</div>
