<footer class="bg-white text- px-3 pt-5" style="padding-bottom:14rem;">
    <div class="container">
        <div class="row flex-column flex-md-row mx-0 gy-3">



            <div class="col-12 mb-5">
                <div class="text-center mb-3">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{asset('storage/site/image/logo.png')}}"
                        alt="{{ config('app.name') }}" class="d-brock mx-auto" style="height:4rem;">
                    </a>
                    <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>
                </div>


                <!--SNS Links-->
                @include('includes.sns_links')
            </div>



            @include('includes.footer_menu')



            <div class="col">
                <div class="form-text mb-3">
                    古物商営業許可<br>
                    第 000010000000 号<br>
                    ○○県公安委員会<br>
                    (株)会社名<br>
                </div>
            </div>


        </div>
    </div>
</footer>
