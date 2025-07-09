{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','発送・発送済み')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-md-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('gacha_category') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shipped') }}">発送</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送済み</li>
            </ol>
        </nav>
    </div>




    <div class="container py-md-4 mb-5">
        <h3 class="d-none d-md-block mb-5">発送</h3>

        <section>
            <div class="">
                <ul class="nav text-center bg-white rounded border">
                    <li class="col">
                        <div class="nav-link text-dark">
                            <a class="text-dark" href="{{ route('shipped.waiting') }}"
                            >発送待ち</a>
                        </div>
                    </li>
                    <li class="col">
                        <div class="nav-link text-dark border-bottom border-primary border-3" aria-current="page">
                            発送済み
                            @php $unread_count = Auth::user()->unread_send_shippeds_count; @endphp
                            @if ( $unread_count )
                                <!--未読-->
                                <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                            @endif
                        </div>
                    </li>
                </ul>


                @forelse ($shippeds as $shipped)
                    <a href="{{ route('shipped.send.show', $shipped) }}"
                    class="d-block card card-body py-2 my-2 bg-white">
                        {{-- <div class="form-text">申請日{{ $shipped->created_at->format('Y年m月d日') }}</div> --}}
                        <div class="form-text">
                            発送日{{ $shipped->shipment_at->format('Y年m月d日') }}

                            @if ( !$shipped->shipment_read )
                                <!--未読-->
                                <span class="badge rounded-pill bg-warning">{{'未読'}}</span>
                            @endif
                        </div>

                        <div class="row text-primary fs-5">
                            <div class="col">
                                {{ $shipped->user_address->name }}様
                            </div>
                            <div class="col-auto">
                                {{ $shipped->user_prizes->count() }}点
                            </div>
                        </div>
                        <div class="form-text">
                            <span>{{ '〒'.$shipped->user_address->postal_code }}</span>
                            <span>{{ $shipped->user_address->todohuken }}</span>
                            <span>{{ $shipped->user_address->shikuchoson }}</span>
                            <span>{{ $shipped->user_address->number }}</span>
                        </div>
                    </a>
                @empty
                    <div class="p-3 py-5">*発送済みの商品はありません</div>
                @endforelse

                {{-- <table class="table bg-white my-3">
                    <!--ヘッド（並べ替えボタン）-->
                    @if ($shippeds->count())
                    <thead>
                        <tr class="bg-white">
                            <th scope="col" >宛名</th>
                            <th scope="col" class="d-none d-md-table-cell">都道府県</th>
                            <th scope="col" class="d-none d-md-table-cell">発送数</th>
                            <th scope="col" class="">発送日</th>
                        </tr>
                    </thead>
                    @endif
                    <tbody>
                        @forelse ($shippeds as $shipped)
                            <tr>
                                <td class="py-3">
                                    <a href="{{ route('shipped.send.show', $shipped) }}">
                                        {{ $shipped->user_address->name }}様

                                        @if ( !$shipped->shipment_read )
                                            <!--未読-->
                                            <span class="badge rounded-pill bg-warning">{{'未読'}}</span>
                                        @endif

                                    </a>
                                </td>
                                <td class="d-none d-md-table-cell py-3">{{ $shipped->user_address->todohuken }}</td>
                                <td class="d-none d-md-table-cell py-3">{{ $shipped->user_prizes->count() }}</td>
                                <td class="py-3">{{ $shipped->shipment_at->format('Y年m月d日') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary border-0 py-5">
                                    *発送待ちの商品はありません
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table> --}}
            </div>

        </section>
    </div>
@endsection
