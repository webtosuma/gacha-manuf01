<h5 class="fw-bold ps-1
border-start border-info border-5
" >ガチャマシン</h5>


<!--マシーン詳細　offcanvace-->
@include('manuf.gacha.common.title_discription.machine_offcanvace')



@if( $machines->count()>0 )


    <div class="card bg-white mb-4 py-3">

        <!--PC-->
        <div id="splide_title_machine"  
        class="splide 
        d-none d-md-block w-100" aria-label="Splideの基本的なHTML">

            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ( $machines as  $machine )
                        <li class="splide__slide px-2 text-center">

                            @include('manuf.gacha.common.title_discription.machine')

                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
        <!--Mobile-->
        <div id="splide_title_machine_mobile"  
        class="splide 
        d-md-none w-100" aria-label="Splideの基本的なHTML">

            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ( $machines as  $machine )
                        <li class="splide__slide px-2 text-center">

                            @include('manuf.gacha.common.title_discription.machine')

                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>


@elseif( Auth::user()->admin )


    <!--サイト管理者専用-->
    <div class="d-md-flex justify-content-between p-3">
        <div class="fs-5 text-danger mb-2">*登録されていません</div>

        <a href="{{ route('admin.gacha_title.machine.create',$gacha_title) }}"
        class="btn btn-primary text-white  rounded-pill shadow"
        ><i class="bi bi-plus-lg me-2"></i>新規登録</a>
    </div>


@endif
