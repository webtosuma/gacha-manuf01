<div class="position-fixed bottom-0 end-0 w-100 bg-white border d-md-none"
style="z-index:50; border-radius: 1rem 1rem 0 0;">
    <div class="container mx-auto" style="max-width:900px;">
        <div class="row">

            <!--買い物カート-->
            <div class="col">
                <a href="{{route('store.keep')}}"
                class="btn fs-3 px-1 position-relative w-100">
                    <i class="bi bi-cart4"></i>

                    @if( auth()->check() && auth()->user()->store_keeps->count()>0 )
                        <div class="position-absolute bottom-0 end-0">
                            <i class="bi bi-circle-fill text-warning fs-6"></i>
                        </div>
                    @endif
                    <div style="font-size:8px;">買物カート</div>
                </a>
            </div>


            <!--検索-->
            <div class="col">
                <button class="btn fs-3 px-1 position-relative w-100"
                type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvaSearch"
                aria-controls="offcanvaSearch">
                    <i class="bi bi-search fs-3"></i>
                    <div style="font-size:8px;">検索</div>
                </button>
            </div>


            <!--ガチャサイト-->
            @if(config('store.r_gacha'))
                <div class="col">
                    <a href="{{config('store.r_gacha')}}"
                    class="btn fs-3 px-1 position-relative w-100">
                        <img src="{{asset('storage/site/image/icon/gacha-black.png')}}"
                        alt="ガチャサイト" class="d-block mx-auto" style="height:2.2rem;">

                        <div style="font-size:8px;">ガチャ</div>
                    </a>
                </div>
            @endif


            <!--メニュー-->
            @auth
                <div class="col">
                    <button class="btn fs-3 px-1 position-relative w-100"
                    type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasHumberge"
                        aria-controls="offcanvasHumberge">
                        <i class="bi bi-list fs-3"></i>
                        <div style="font-size:8px;">メニュー</div>
                    </button>
                </div>
            @endauth
        </div>
    </div>
</div>
