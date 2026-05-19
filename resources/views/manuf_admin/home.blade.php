@extends('admin.layouts.app')


@section('title','Admin TOP')


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ 'Top' }}</li>
            </ol>
        </nav>


        <section class="row g-2 mt-5">

            <!--月間売上-->
            <div class="col-6 col-md-3"><a class="btn text-start text-secondary bg-body rounded w-100"
            href="">
                <div class="fw-bold border-bottom border-1 "
                >月間売上</div>

                <div class="fs-3">{{ number_format($sales)}}</div>
            </a></div>

            <!--月間販売数-->
            <div class="col-6 col-md-3"><a class="btn text-start text-secondary bg-body rounded w-100"
            href="">
                <div class="fw-bold border-bottom border-1 "
                >月間販売数</div>

                <div class="fs-3">{{ number_format( $purchases_count ) }}</div>
            </a></div>


            <!--公開タイトル-->
            <div class="col-6 col-md-3"><a class="btn text-start text-secondary bg-body rounded w-100"
            href="">
                <div class="fw-bold border-bottom border-1 "
                >公開中タイトル</div>

                <div class="fs-3">{{ number_format($published_title_count)}}</div>
            </a></div>
    

            <!--登録ユーザー-->
            <div class="col-6 col-md-3"><a class="btn text-start text-secondary bg-body rounded w-100"
            href="{{route('admin.user')}}">
                <div class="fw-bold border-bottom border-1 "
                >登録ユーザー</div>
                <div class="fs-3">{{ number_format($users_count)}}</div>
            </a></div>


            <!--発送待ち-->
            <div class="col-6 col-md-3"><a class="btn text-start text-secondary bg-body rounded w-100"
            href="{{route('admin.shipped')}}">
                <div class="fw-bold border-bottom border-1 "
                >発送待ち</div>

                <div class="fs-3">
                    @if ($waiting_shippeds_count)
                    <span class="text-warning">{{ number_format($waiting_shippeds_count)}}</span>
                    @else{{ '0' }}@endif
                </div>
            </a></div>

        </section>



        {{-- <section class="carddd card-body p-3 bg-white my-3 overflow-auto">
            <table class="table bg-white my-3">
                <tbody>
                    <!--ヘッド（並べ替えボタン）-->
                    <thead>
                        <tr class="bg-white">
                            <th scope="col">公開中ガチャ名</th>
                            <th scope="col">価格</th>
                            <th scope="col">残り数</th>

                            <th class="text-center" scope="col"
                            >ポイント売上</th>

                            <th scope="col">公開日</th>
                        </tr>
                    </thead>

                    @forelse ($gachas as $gacha)
                        <tr>
                            <td>
                                <a href="{{route('admin.gacha.show',$gacha)}}" class="">{{ $gacha->name }}</a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="">
                                        1回×
                                        <span class="fs-5">
                                            <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                                        </span>pt
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('admin.gacha.history',$gacha)}}">
                                    残り
                                    <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                                    /
                                    <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                                </a>
                            </td>

                            <td class="text-center">
                                <number-comma-component number="{{ $gacha->played_count *  $gacha->one_play_point }}pt"></number-comma-component>
                            </td>

                            </td>
                            <td>{{ $gacha->published_at->format('Y年m月d日 H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary border-0 py-5">
                                *公開中のガチャはありません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- ページネーション -->
            @if( $gachas->count() )
                <div class="d-flex justify-content-start mt-3">
                    {{ $gachas->links('vendor.pagination.bootstrap-4') }}
                </div>
            @endif
        </section> --}}


    </div>
@endsection
