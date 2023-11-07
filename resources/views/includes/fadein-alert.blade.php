@php
$session_alerts = [ 'alert-primary','alert-success','alert-info','alert-warning','alert-danger' ];
@endphp


@foreach ($session_alerts as $alert_name)
    @if ( session( $alert_name ) )

        @php
         $body  = session( $alert_name );
         $color = str_replace('alert-','', $alert_name);
        @endphp
        {{-- @php
        $body  = 'alert-primary';
        $color = 'primary';
        @endphp --}}
        <alert-modal-comp-component color="{{$color}}" body="{{$body}}" />

    @endif
@endforeach
