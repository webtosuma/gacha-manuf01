@php
    $active_class = "text-primary fw-bold border-end border-bottom border-start border-top border-primary border-2 bg-white active_menu disabled";
@endphp
<div class="d-flex flex-column justify-content-between py-3 px-2">



    <!--ロゴ-->
    <div class="list-group-item border-0 w-100 text-center mb-3">
        <a class="navbar-brand" href="{{ route('manuf') }}">
            <img src="{{asset('storage/site/image/logo.png')}}" alt="{{ config('app.name') }}" 
            class=""
            style="max-width:160px"
            >
        </a>        
    </div>


    @include('manuf.includes.my_menu')




    <div class="list-group-item border-0 p-2 px-0 text-start">
        <!--SNS Links-->
        @include('includes.sns_links')
    </div>



    <div class="list-group-item border-0 p-2 px-0 text-start my-5"
    style="width:240px"
    >

        <!--SNS Links-->
        @include('manuf.includes.sns_links')

    </div>

</div>


