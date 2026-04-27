<div class="offcanvas offcanvas-end  bg-whie text-"
tabindex="-1" id="offcanvasHumberge" aria-labelledby="offcanvasHumbergeLabel"
style="max-width:90vw; min-width:30vw;">

    <div class="offcanvas-header align-items-center border-bottom">
        <h5 id="offcanvasHumbergeLabel" class="m-0">マイメニュー</h5>

        <!--閉じる-->
        <button type="button" class="btn px-2 py-1 btn-light text-info" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="bi bi-x-lg fs-5"style="line-height:.6rem;"></i>
        </button>
    </div>




    <div class="offcanvas-body px- pt-">

        @include('manuf.includes.my_menu')


        <!--ロゴ-->
        <div class="text-center mb-3 mt-5">
            <a class="navbar-brand" href="{{ route('manuf') }}">
                <img src="{{asset('storage/site/image/logo.png')}}"
                alt="{{ config('app.name') }}" class="d-brock mx-auto" style="max-width:200px">
            </a>
            <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
        </div>


        <!--SNS Links-->
        @include('includes.sns_links')


        <!--SNS Links-->
        <div class="mt-5 mx-auto" style="max-width:300px">
            @include('manuf.includes.sns_links')
        </div>


    </div>


</div>
