<footer class="bg-white text- px-3 pt-5" style="padding-bottom:14rem;">
    <div class="container">
        <div class="row flex-column flex-md-row mx-0 gy-0">



            <div class="col-12">
                <div class="text-center mb-3">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{asset('storage/site/image/logo.png')}}"
                        alt="{{ config('app.name') }}" class="d-brock mx-auto" style="height:6rem;">
                    </a>
                    <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
                </div>


                <!--SNS Links-->
                @include('includes.sns_links')
            </div>


            <!-- フッターメニュー -->
            @include('includes.footer_menu')


        </div>
    </div>
</footer>
