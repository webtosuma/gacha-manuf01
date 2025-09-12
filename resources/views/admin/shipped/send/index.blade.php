@extends('admin.layouts.app')


@section('title','発送受付・発送済み')


@section('meta') @php
$active_key = 'shipped';
$active_gacha_menu = config('store.admin');//ECガチャ用Adminのとき
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.shipped') }}"
                >{{ '発送受付' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">発送済み</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">発送受付</h2>


        <section class="p-3">
            <ul class="nav nav-tabs text-center mb-5">
                <li class="nav-item col">
                    <a class="nav-link bg-light" href="{{ route('admin.shipped.waiting') }}"
                    >発送待ち</a>
                  </li>
                <li class="nav-item col">
                  <div class="nav-link active bg-white" aria-current="page">発送済み</div>
                </li>
            </ul>


            <div class="row text-secondary my-3">
                <div class="col">
                    <div class="d-flex gap-2 align-items-end">
                        <div class="">発送済み</div>
                        <h3 class="fw-bold m-0">{{$count}}</h3>
                        <span>件</span>
                    </div>
                </div>
            </div>


            <!-- ページネーション -->
            @if( $paginate_shippeds->count() )
            <div class="d-flex justify-content-start mt-3">
                {{ $paginate_shippeds->links('vendor.pagination.bootstrap-4') }}
            </div>
            @endif

            <table class="table bg-white my-3">
                <!--ヘッド（並べ替えボタン）-->
                @if ($paginate_shippeds->count())
                <thead>
                    <tr class="bg-white">
                        <th scope="col">発送コード</th>
                        <th scope="col">宛名</th>
                        <th scope="col">都道府県</th>
                        <th scope="col">商品数</th>
                        <th scope="col">発送日時</th>
                        <th></th>
                    </tr>
                </thead>
                @endif
                <tbody>
                    @forelse ($paginate_shippeds as $shipped)
                        <tr>
                            <td class="py-3">{{ $shipped->code}}</td>
                            <td class="py-3">
                                <a href="{{ route('admin.shipped.send.show', $shipped) }}"
                                >{{ $shipped->user_address->name }}様</a>
                            </td>
                            <td class="py-3">{{ $shipped->user_address->todohuken }}</td>
                            <td class="py-3">{{ $shipped->user_prizes->count() }}</td>
                            <td class="py-3">{{ $shipped->shipment_at->format('Y年m月d日 H:i') }}</td>
                            <!--menu-->
                            <td class="py-3" style="width:2rem;">
                                @php $user = $shipped->user; @endphp
                                @if($user)
                                    <div class="dropdown">
                                        <button class="btn btn-light border rounded-pill" type="button"
                                        id="{{'dropdownMenuButton'.$shipped->id}}" data-bs-toggle="dropdown" aria-expanded="false"
                                        ><i class="bi bi-three-dots-vertical"></i></button>


                                        <ul class="dropdown-menu bg-white" aria-labelledby="{{'dropdownMenuButton'.$shipped->id}}">
                                            <li><a  href="{{route('admin.user.show',$user)}}"
                                            class="dropdown-item">ユーザー情報</a></li>
                                            <li><a href="{{route('admin.user.point_history',['user_id'=>$user->id,'reason_id'=>22,])}}"
                                            class="dropdown-item">発送申請履歴</a></li>
                                        </ul>
                                    </div>
                                @else
                                    <span class="text-danger">退会</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary border-0 py-5">
                                *発送済みの商品はありません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- ページネーション -->
            @if( $paginate_shippeds->count() )
            <div class="d-flex justify-content-start mt-3">
                {{ $paginate_shippeds->links('vendor.pagination.bootstrap-4') }}
            </div>
            @endif


        </section>


    </div>
@endsection
