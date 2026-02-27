<footer class="bg-info text-white px-3 pt-5" style="padding-bottom:4rem;">
    <div class="container">
        <div class="row flex-column flex-lg-row justify-content-between mx-0 gy-0">



            <div class="col-12 col-md-auto">
                <div class="text-center mb-3">
                    <a class="navbar-brand" href="{{ route('manuf') }}">
                        <img src="{{asset('storage/site/image/logo_white.png')}}"
                        alt="{{ config('app.name') }}" class="d-brock mx-auto" style="max-width:200px">
                    </a>
                    <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
                </div>


                <!--SNS Links-->
                @include('includes.sns_links_white')
            </div>


            <!-- フッターメニュー -->
            @include('manuf.includes.footer_menu')


        </div>
    </div>
</footer>
