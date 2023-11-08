@extends('layouts.app')


<!----- title ----->
@section('title','プライバシーポリシー')

<!----- contents ----->
@section('content')

    <div class="container my-5">
        @include( 'privacy_policy.'.$revision_date )
    </div>

@endsection
