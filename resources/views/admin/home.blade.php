@extends('admin.layouts.app')

@section('content')
<div class="container py-4">

    admin


    @if ( Auth::check() )
        <form action="{{ route('admin_auth.logout') }}" method="POST">
            @csrf
            <button class="list-group-item list-group-item-action py-3"
            type="submit">{{ __('ログアウト') }}</button>
        </form>
    @endif

</div>
@endsection
