@php
    $active_class = "text-primary fw-bold border-end border-bottom border-start border-top border-primary border-2 bg-white active_menu disabled";
@endphp
<div class="d-flex flex-column justify-content-between py-3 px-2">



    <!--ロゴ-->
    <div class="list-group-item border-0 w-100 text-start pe-3 mb-3">
        <a class="navbar-brand  text-primary" href="{{ route('manuf') }}">
            <h1 class="fs-6 m-0 text-center d-flex flex- align-items-center gap-2">
                <img src="{{asset('storage/site/image/logo_white.png')}}" alt="{{ config('app.name') }}" class="d-brock w-100">
            </h1>
        </a>
    </div>


    @include('manuf.includes.my_menu')




    <div class="list-group-item border-0 p-2 px-3 w-100 text-start mb-5">

        <!--SNS Links-->
        @include('includes.sns_links_white')

    </div>

</div>


