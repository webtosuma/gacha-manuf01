@php
$sns_url  = isset($sns_url) ? $sns_url : route('home');
$sns_text = isset($sns_text) ? $sns_text : '';
$sns_size = isset($sns_size) ? $sns_size : '2.2rem';
@endphp
<div class="d-flex gap-2 justify-content-center">


    <!--X-->
    {{-- <a href="http://twitter.com/share?text={{ $sns_text }}&url={{ $sns_url }}" rel="nofollow" target="_blank"
    class="btn btn-sm d-flex align-items-center  rounded-pill py-2 px-3"
    style="background-color:#000; border-color:#000;"
    >
        <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
        alt="xロゴ" class="d-block" style="height:1.2rem;">

        <span class="ms-3 text-white">ポストする</span>
    </a> --}}


    <!--facebook-->
    {{-- <a href="http://www.facebook.com/share.php?u={{ $sns_url }}" target="_blank"
    class="btn btn-sm d-flex align-items-center  rounded-pill py-0 px-3"
    style="background-color:#1778F2; border-color:#1778F2;"
    >
        <img src="{{asset('storage/site/image/facebook-logo/primary.png')}}"
        alt="facebookロゴ" class="d-block" style="height:2rem;">

        <span class="ms-3 text-white">シェアする</span>
    </a>
    --}}

    <!--LINE-->
    {{-- <a href="https://social-plugins.line.me/lineit/share?url={{ $sns_url }}" target="_blank"
    class="btn btn-sm d-flex align-items-center  rounded-pill py-0 px-3"
    style="background-color:#4CC764; border-color:#4CC764;"
    >
        <img src="{{asset('storage/site/image/line-logo/success.png')}}"
        alt="LINEロゴ" class="d-block" style="height:2rem;">

        <span class="ms-2 text-white">LINEで送る</span>
    </a> --}}



    <!--X-->
    <a href="http://twitter.com/share?text={{ $sns_text }}&url={{ $sns_url }}" rel="nofollow" target="_blank"
    class="d-block rounded-pill p-2" style="background-color:#000; border-color:#000; width:{{$sns_size}}; height:{{$sns_size}};"
    >
        <img src="{{asset('storage/site/image/x-logo/logo-white.png')}}"
        alt="xロゴ" class="d-block h-100">
    </a>

    <!--facebook-->
    <a href="http://www.facebook.com/share.php?u={{ $sns_url }}" target="_blank"
    class="d-block rounded-pill" style="background-color:#3578E5; border-color:#3578E5; width:{{$sns_size}}; height:{{$sns_size}};"
    >
        <img src="{{asset('storage/site/image/facebook-logo/primary.png')}}"
        alt="facebookロゴ" class="d-block h-100">
    </a>

    <!--LINE-->
    <a href="https://social-plugins.line.me/lineit/share?url={{ $sns_url }}" target="_blank"
    class="d-block rounded-pill" style="background-color:#01ba01; border-color:#01ba01; width:{{$sns_size}}; height:{{$sns_size}};"
    >
        <img src="{{asset('storage/site/image/line-logo/success.png')}}"
        alt="LINEロゴ" class="d-block h-100">
    </a>

</div>

