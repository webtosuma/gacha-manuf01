<footer class="bg-light text- px-3 pt-5" style="padding-bottom:10rem;">
    <div class="container">
        <div class="row flex-column flex-md-row mx-0 gy-3">



            <div class="col">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('storage/site/image/logo.png')}}"
                    alt="{{ config('app.name') }}" class="d-brock" style="height:4rem;">
                </a>
                <small class="d-block text-muteddd">&copy;{{config('app.company_name')}}</small>


                <div class="my- d-flex">
                    <a href="https://twitter.com/CardFesta7627" rel="nofollow" target="_blank">
                        <img src="{{asset('storage/site/image/x-logo/logo-black.png')}}"
                        alt="xロゴ" class="d-block p-2" style=" width:2rem; height:2rem;">
                    </a>
                    <a href="https://note.com/cardfesta" rel="nofollow" target="_blank">
                        <img src="{{asset('storage/site/image/note-logo/main/icon.png')}}"
                        alt="noteロゴ" class="d-block p-" style=" width:2rem; height:2rem;">
                    </a>
                    <a href="https://www.instagram.com/cardfesta/" rel="nofollow" target="_blank">
                        <img src="{{asset('storage/site/image/instagram-logo/01/gradient.png')}}"
                        alt="インスタグラムロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
                    </a>
                    <a href="https://www.tiktok.com/@cardfesta" rel="nofollow" target="_blank">
                        <img src="{{asset('storage/site/image/tiktok-icons/black_circle.png')}}"
                        alt="tiktokロゴ" class="d-block p-1"  style=" width:2rem; height:2rem;">
                    </a>
                </div>


                <div class="form-text mb-3">
                    古物商営業許可<br>
                    第 931010002606 号<br>
                    熊本県公安委員会<br>
                    合同会社 Fobees<br>
                </div>

            </div>



            @include('includes.footer_menu')




        </div>
    </div>
</footer>
