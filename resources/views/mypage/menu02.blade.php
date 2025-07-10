<!-- メニュー -->
<div class="bg-white border- p-3">
    <h6 class="fw-bole pb-0">メニュー</h6>
    <div class="row g-2">
        <div class="col-4">
            <a href="{{ route('infomation') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-megaphone" viewBox="0 0 16 16">
                    <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.18.01.359.022.537.036 2.568.189 5.093.744 7.463 1.993V3.85zm-9 6.215v-4.13a95.09 95.09 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A60.49 60.49 0 0 1 4 10.065zm-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68.019 68.019 0 0 0-1.722-.082z"/>
                </svg> --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                </svg>
                <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">お知らせ</div>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('shipped') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                    <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                </svg>
                <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">
                    <span>発送</span>

                    @php $unread_count = Auth::user()->unread_send_shippeds_count; @endphp
                    @if ( $unread_count )
                        <!--未読-->
                        <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                    @endif
                </div>
            </a>
        </div>
        @if( config('app.coupon') )
            <div class="col-4">
                <a href="{{ route('coupon') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 512 512">
                    <path d="M506.734 117.695c-5.06-11.96-13.434-22.128-24.22-29.417-11.048-7.481-23.969-11.434-37.41-11.434H66.892c-9.04 0-17.804 1.768-26.042 5.258-11.959 5.064-22.131 13.442-29.42 24.228C3.941 117.394-0.012 130.326 0 143.737v224.526c-.004 9.018 1.768 17.781 5.266 26.043 5.064 11.966 13.434 22.135 24.216 29.416 11.048 7.48 23.969 11.434 37.372 11.434h378.25c9.037 0 17.796-1.776 26.042-5.273 11.96-5.05 22.132-13.427 29.42-24.212 7.489-11.064 11.442-23.996 11.434-37.408V143.737c0-9.034-1.772-17.797-5.266-26.042zm-32.454 250.576c-.007 3.945-.776 7.759-2.285 11.342-2.185 5.157-5.946 9.72-10.597 12.854-4.891 3.29-10.373 4.957-16.294 4.965H66.892c-3.941 0-7.759-.765-11.345-2.278-5.15-2.177-9.716-5.937-12.859-10.608-3.281-4.88-4.953-10.362-4.964-16.284V143.729c.004-3.945.772-7.759 2.285-11.341 2.17-5.142 5.93-9.705 10.597-12.855 4.903-3.289 10.38-4.957 16.282-4.965h378.216c3.93 0 7.748.772 11.346 2.286 5.153 2.17 9.724 5.93 12.862 10.593 3.278 4.879 4.953 10.353 4.968 16.29v224.52z"/>
                    <path d="M413.681 158.144h-56.037L248.913 353.856h164.768c9.4 0 17.02-7.62 17.02-17.016V175.168c0-9.404-7.62-17.024-17.02-17.024z"/>
                    </svg>

                    <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">
                        <span>クーポン</span>
                    </div>
                </a>
            </div>
        @endif
        <div class="col-4">
            <a href="{{ route('guide') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                </svg>
                <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">利用ガイド</div>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('settings') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                </svg>
                <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">設定</div>
            </a>
        </div>
        <div class="col-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                    </svg>
                    <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">ログアウト</div>
                </button>
            </form>
        </div>

    </div>
</div>

<!-- 履歴 -->
<div class="bg-white border- p-3">
    <h6 class="fw-bole pb-0">履歴</h6>
    <div class="row g-2">
        <div class="col-4">
            <a href="{{ route('gacha_history') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
                    <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828l.645-1.937zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.407 2.31l.387-1.162zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L10.863.1z"/>
                </svg>
                <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">ガチャ</div>
            </a>
        </div>
        <div class="col-4">
            <a href="{{ route('point_history') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-p-circle" viewBox="0 0 16 16">
                    <path d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.5 4.002h2.962C10.045 4.002 11 5.104 11 6.586c0 1.494-.967 2.578-2.55 2.578H6.784V12H5.5zm2.77 4.072c.893 0 1.419-.545 1.419-1.488s-.526-1.482-1.42-1.482H6.778v2.97z"/>
                </svg>

                <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">ポイント</div>
            </a>
        </div>
        @if( config('u_rank_ticket.ticket',false) )
            <div class="col-4">
                <a href="{{route('ticket_history')}}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 w-100" style="font-size:11px;">
                    {{-- <img src="{{asset('storage/site/image/ticket/dark.png')}}"
                    alt="チケット" class="d-block mx-auto" style=" width:24px; height:24px;"> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-ticket-perforated-fill" viewBox="0 0 16 16">
                        <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4-1v1h1v-1zm1 3v-1H4v1zm7 0v-1h-1v1zm-1-2h1v-1h-1zm-6 3H4v1h1zm7 1v-1h-1v1zm-7 1H4v1h1zm7 1v-1h-1v1zm-8 1v1h1v-1zm7 1h1v-1h-1z"/>
                    </svg>

                    <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">チケット</div>
                </a>
            </div>
        @endif
        <div class="col-4">
            <a href="{{ route('user_rank_history') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                {{-- <img src="{{asset('storage/site/image/icon/user_rank.png')}}"
                alt="会員ランク" class="d-block mx-auto" style=" width:24px; height:24px;"> --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-trophy" viewBox="0 0 16 16">
                    <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5q0 .807-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33 33 0 0 1 2.5.5m.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935m10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935M3.504 1q.01.775.056 1.469c.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.5.5 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667q.045-.694.056-1.469z"/>
                </svg>

                <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">会員ランク</div>
            </a>
        </div>
        @if( config('app.coupon') )
            <div class="col-4">
                <a href="{{ route('coupon.history') }}" class="btn rounded-3 text-dark shadow-sm fw-bold p-2 px-1 w-100" style="font-size:11px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 512 512">
                    <path d="M506.734 117.695c-5.06-11.96-13.434-22.128-24.22-29.417-11.048-7.481-23.969-11.434-37.41-11.434H66.892c-9.04 0-17.804 1.768-26.042 5.258-11.959 5.064-22.131 13.442-29.42 24.228C3.941 117.394-0.012 130.326 0 143.737v224.526c-.004 9.018 1.768 17.781 5.266 26.043 5.064 11.966 13.434 22.135 24.216 29.416 11.048 7.48 23.969 11.434 37.372 11.434h378.25c9.037 0 17.796-1.776 26.042-5.273 11.96-5.05 22.132-13.427 29.42-24.212 7.489-11.064 11.442-23.996 11.434-37.408V143.737c0-9.034-1.772-17.797-5.266-26.042zm-32.454 250.576c-.007 3.945-.776 7.759-2.285 11.342-2.185 5.157-5.946 9.72-10.597 12.854-4.891 3.29-10.373 4.957-16.294 4.965H66.892c-3.941 0-7.759-.765-11.345-2.278-5.15-2.177-9.716-5.937-12.859-10.608-3.281-4.88-4.953-10.362-4.964-16.284V143.729c.004-3.945.772-7.759 2.285-11.341 2.17-5.142 5.93-9.705 10.597-12.855 4.903-3.289 10.38-4.957 16.282-4.965h378.216c3.93 0 7.748.772 11.346 2.286 5.153 2.17 9.724 5.93 12.862 10.593 3.278 4.879 4.953 10.353 4.968 16.29v224.52z"/>
                    <path d="M413.681 158.144h-56.037L248.913 353.856h164.768c9.4 0 17.02-7.62 17.02-17.016V175.168c0-9.404-7.62-17.024-17.02-17.024z"/>
                    </svg>

                    <div class="text-secondary mt-2" style="font-size:10px; line-height:18px;">
                        <span>クーポン</span>
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>



<!-- PWAインストールボタン -->
<div class="px-3">
    <pwa-install-btn
    r_about_pwa="{{route('about_pwa')}}"
    ></pwa-install-btn>
</div>
