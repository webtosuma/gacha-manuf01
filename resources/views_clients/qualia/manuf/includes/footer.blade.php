<footer class="bg-white border-top px-3 pt-5 mt-5" style="padding-bottom:16rem;">
    <div class="container">
        <div class="row flex-column flex-lg-row justify-content-between mx-0 gy-0">



            <div class="col-12 col-md-auto mb-3">
                <div class="text-center mb-3">
                    <a class="navbar-brand" href="{{ route('manuf') }}">
                        <img src="{{asset('storage/site/image/logo.png')}}"
                        alt="{{ config('app.name') }}" class="d-brock mx-auto" style="max-width:200px">
                    </a>
                    <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
                </div>


                <!--SNS Links-->
                @include('includes.sns_links')
            </div>


            <!-- フッターメニュー -->
            @include('manuf.includes.footer_menu')


            <!--SNS Links-->
            <div class="mt-3" style="max-width:770px">
                @include('manuf.includes.sns_links')
            </div>

        </div>
    </div>
</footer>
