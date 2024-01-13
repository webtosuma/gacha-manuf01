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



        {{-- <h2 class="mb-5 py-3 border-bottom">Admin TOP</h2> --}}

        <section class="row g-0 mt-5">
            <div class="col"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.gacha')}}">
                <div class="fw-bold">公開中</div>

                <div class="fs-3"><number-comma-component number="{{ $gachas->count() }}"></number-comma-component></div>
            </a></div>

            <div class="col"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.point_history')}}">
                <div class="fw-bold">月間売上</div>
                <div class="fs-3"><number-comma-component number="{{ $sales }}"></number-comma-component></div>
            </a></div>

            <div class="col"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.user')}}">
                <div class="fw-bold">登録ユーザー</div>
                <div class="fs-3"><number-comma-component number="{{ $users->count() }}"></number-comma-component></div>
            </a></div>

            <div class="col"><a class="btn text-start text-secondary w-100"
            href="{{route('admin.shipped')}}">
                <div class="fw-bold">発送待ち</div>

                <div class="fs-3">
                    @if ($waiting_shippeds_count)
                    <span class="text-warning">
                        <number-comma-component number="{{ $waiting_shippeds_count }}"></number-comma-component>
                    </span>
                    @else{{ '0' }}@endif
                </div>
            </a></div>

        </section>



        <section class="carddd card-body p-3 bg-white my-3 overflow-auto">
            {{-- <h5 class="m-0">公開中</h5> --}}
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
                                    {{-- @include('includes.point_icon') --}}
                                    <div class="">
                                        1回×
                                        <span class="fs-5">
                                            <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                                        </span>pt
                                    </div>
                                </div>
                            </td>
                            <td>
                                残り
                                <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                                /
                                <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                            </td>

                            <td class="text-center">
                                <number-comma-component number="{{ $gacha->played_count *  $gacha->one_play_point }}pt"></number-comma-component>
                            </td>

                            </td>
                            <td>{{ $gacha->published_at->format('Y年m月d日公開') }}</td>
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
        </section>


    </div>
@endsection
