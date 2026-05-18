@if( $infomations->count()>0 )
    <section class="bg- mb-5"
    >
    {{-- data-aos="fade-in" --}}

        <div class="container">
            <div class="rounded-4 col-10 mx-auto  border border-white border-5" 
            style="background:rgb(255, 255, 255, 1);">
                <div class="row g-0">


                    <div class="col-12 col-md-auto">
                        <div class="d-flex flex-md-column justify-content-between align-items-center h-100">

                            <div class="p-3 fw-bold">お知らせ</div>

                            <div class="text-end">
                                <a href="{{route('infomation')}}" 
                                class="btn text-secondary "
                                style="font-size:12px;"
                                >一覧を見る </a>    
                            </div>
                        </div>
                    </div>


                    <div class="col" style="font-size:12px;">
                        @forelse ($infomations as $infomation)
                            <div class="p-2 pozition-relative">
                                <a href="{{ route('infomation.show',$infomation) }}">
                                    <div class="d-flex align-items-center px-0">
                                        <div class="col">

                                            <div class="text-info">
                                                {{ $infomation->created_at->format('Y.m.d') }}
                                            </div>
                                            <div class="text-secondary fw-bold">
                                                {{ $infomation->title }}
                                            </div>

                                        </div>
                                        @if( $infomation->image_path )
                                            <div class="col-auto" style="width:3rem;">
                                                <ratio-image-component
                                                url="{{ $infomation->image_path }}" 
                                                style_class="ratio ratio-1x1 w-100 rounded"
                                                ></ratio-image-component>
                                            </div>
                                        @endif
                                        <div class="col-auto text-secondary ms-2 fs-5">
                                            <i class="bi bi-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="p-2 pozition-relative">
                                <div class="">
                                    * お知らせはありません
                                </div>
                            </div>
                        @endforelse
                    </div>


                    {{-- <div class="col-12 col-md-auto">
                        <div class="d-flex flex-column justify-content-top h-100 ">
                            <div class="text-end p-3">
                                <a href="{{route('infomation')}}" 
                                class="btn text-secondary "
                                style="font-size:12px;"
                                >一覧を見る </a>    
                            </div>
                        </div>
                    </div> --}}



                </div>
            </div>
        </div>
    </section>
@endif
