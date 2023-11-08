@extends('layouts.900_simple_base')


<!----- title ----->
@section('title','お問い合わせ')

<!----- meta ----->
@section('meta')
@endsection


<!----- style ----->
@section('style')
@endsection


<!----- script ----->
@section('script')
@endsection


<!----- contents ----->
@section('contents')
<h2 class="text-secondary fw-bold mb-5 border-start border-primary border-5 ps-2">
    お問い合わせ
</h2>


<section id="app"  class="mb-5">

    <contact-form-component
    token="{{csrf_token()}}"
    route_data_list="{{route('contact.component_data_api')}}"
    privacy_policy ="{{route('privacy_policy')}}"
    ></contact-form-component>


</section>

@endsection
