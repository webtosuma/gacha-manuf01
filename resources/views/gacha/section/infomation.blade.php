<section class="bg- mb-5">
    <div class="container py-5">
        <div class="col-md-8 mx-auto">

            {{-- <h3 class="text-center text-white fw-bold mb-4 fs-1">お知らせ</h3> --}}

            <div class="list-group list-group-flush shadow-sm rounded-4"
            style="background:rgb(0, 0, 0, .8;">
                <div class="list-group-item border-0">
                    <h3 class="text-center text-white my-3 fw-bold mb-4 fs-2 border-bottom border-primary border-2 pb-3"
                    >お知らせ</h3>
                </div>

                @forelse ($infomations as $infomation)
                    <div class="list-group-item list-group-item-action border-0 pozition-relative">
                        <a href="{{ route('infomation.show',$infomation) }}" class="text-dark">
                            <div class="d-flex align-items-center px-3">
                                <div class="col">
                                    <div class="row py-2">

                                        <div class="col-auto text-primary">
                                            {{ $infomation->created_at->format('Y.m.d') }}
                                        </div>
                                        <div class="col-12 col-md text-white">
                                            {{ $infomation->title }}
                                        </div>

                                    </div>
                                </div>
                                <div class="col-auto text-primary">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="list-group-item border-0 pozition-relative">
                        <div class="">
                            * お知らせはありません
                        </div>
                    </div>
                @endforelse

                <div class="list-group-item border-0 text-end">
                    <a href="{{route('infomation')}}" class="btn btn- text-white ">もっと見る ></a>
                </div>
            </div>

            {{-- <div class="text-end mt-3">
                <a href="{{route('infomation')}}" class="btn btn- text-white ">もっと見る ></a>
            </div> --}}
        </div>
    </div>
</section>
