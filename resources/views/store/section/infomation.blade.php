<section class="bg- mb-"  style="background:rgb(255, 255, 255, .0);">

    <div class="container py-5">
        <div class="col-md-8 mx-auto">
            <div class="list-group list-group-flush shadow- rounded-4">
                <div class="list-group-item border-0" style="background:rgb(255, 255, 255, .7);">
                    <h3 class="text-center text-whiteee my-3 fw-bold mb-4 fs-2 border-bottom border-info border-2 pb-3"
                    >お知らせ</h3>
                </div>

                @forelse ($infomations as $infomation)
                    <div class="list-group-item list-group-item-actionnn border-0 pozition-relative" style="background:rgb(255, 255, 255, .7);">
                        <a href="{{ route('infomation.show',$infomation) }}" class="text-dark">
                            <div class="d-flex align-items-center px-0">
                                <div class="col">

                                    <div class="text-info">
                                        {{ $infomation->created_at->format('Y.m.d') }}
                                    </div>
                                    <div class="text-whiteee">
                                        {{ $infomation->title }}
                                    </div>

                                </div>
                                @if( $infomation->image_path )
                                    <div class="col-auto" style="width:3rem;">
                                        <ratio-image-component
                                        url="{{ $infomation->image_path }}" style_class="ratio ratio-1x1 w-100 rounded"
                                        ></ratio-image-component>
                                    </div>
                                @endif
                                <div class="col-auto text-rlght ms-2">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="list-group-item border-0 pozition-relative" style="background:rgb(255, 255, 255, .7);">
                        <div class="">
                            * お知らせはありません
                        </div>
                    </div>
                @endforelse

                <div class="list-group-item border-0 text-end" style="background:rgb(255, 255, 255, .7);">
                    <a href="{{route('infomation')}}" class="btn btn- text-whiteee ">もっと見る ></a>
                </div>
            </div>

            {{-- <div class="text-end mt-3">
                <a href="{{route('infomation')}}" class="btn btn- text-whiteee ">もっと見る ></a>
            </div> --}}
        </div>
    </div>
</section>
