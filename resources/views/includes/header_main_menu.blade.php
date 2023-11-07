@php
    $main_menus = [
        [
            'label'      => 'ホーム',
            'icon'       => 'bi-house-door-fill',
            'route'      => 'home',
            'active_key' => 'home',
        ],

    ];
@endphp
<nav class="main_nav d-none d-lg-block w-100">
    <ul class="">
        @foreach ($main_menus as $main_menu)
            <li class="">
                <a class="nav-link list-group-item-action text-center fs-5 py-4 pb-2
                @if( isset($active_menu) && $active_menu===$main_menu['active_key']) text-primary fw-bold @else text-secondary @endif"
                href="{{ route( $main_menu['route'] ) }}"
                ><i class="bi {{$main_menu['icon']}} me-3 position-relative">
                    @if ( /*未読スカウト数Badge*/ $main_menu['active_key']==='scout_message' && $worker->unreadScoutCount()>0 )
                        <span class="badge rounded-pill bg-danger">{{$worker->unreadScoutCount()}}</span>
                    @endif
                    @if ( /*未読選考中数Badge*/ $main_menu['active_key']==='scout_selection' && $worker->unread_scout_selection_count>0 )
                        <span class="badge rounded-pill bg-danger">{{$worker->unread_scout_selection_count}}</span>
                    @endif
                    @if (/*お知らせ数Badge*/    $main_menu['active_key']==='infomation' && $worker->unread_infomation_count>0)
                        <span class="badge rounded-pill bg-danger">{{$worker->unread_infomation_count}}</span>
                    @endif
                </i>{{$main_menu['label']}}</a>
            </li>
        @endforeach
    </ul>
</nav>
