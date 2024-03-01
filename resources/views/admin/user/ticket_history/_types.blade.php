@forelse ($ticket_histories as $ticket_history)
    <li class="list-group-item bg-white py-3">
        <div class="row align-items-center ">
            <div class="col">



                @php
                $user_id    = $ticket_history->user ? $ticket_history->user->id  : '';
                $user_name  = $ticket_history->user ? $ticket_history->user->name  : '退会済み';
                $user_email = $ticket_history->user ? $ticket_history->user->email : '---';
                @endphp

                <div class="d-flex align-items-center justify-content-between">
                    @php
                    $text_color = $ticket_history->value >= 0 ? 'text-primary' : 'text-danger';
                    $text_color = $ticket_history->reason_id==14 ? 'text-warning' : $text_color;
                    $sine = $ticket_history->value > 0 ? '+' : ( $ticket_history->value < 0 ? '-' : '' );
                    @endphp
                    <div class="">
                        <div class="form-text">{{$ticket_history->created_at->format('Y/m/d H:i')}}</div>
                        @if(!$user)
                            <a href="{{route('admin.user.ticket_history',$user_id)}}"
                            >{{ 'ID:'.$user_id.' '.$user_name.' '.$user_email}}</a>
                        @endif
                        <div class="fw-bold"><span class="{{$text_color}}">●</span>{{ $ticket_history->reason }}</div>
                    </div>

                    <div class="col-auto {{$ticket_history->value >= 0 ?'':'text-danger'}}">
                        {{$ticket_history->value >= 0 ?'+':''}}
                        <number-comma-component number="{{ $ticket_history->value }}"></number-comma-component>
                        {{'枚'}}
                    </div>
                </div>



            </div>
        </div>
    </li>

@empty
    <li class="list-group-item bg-white py-3 fw-bold">履歴はありません。</li>
@endforelse
