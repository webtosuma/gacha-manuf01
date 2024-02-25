{{-- @extends('layouts.app') --}}
@extends('layouts.sub')

<!----- title ----->
@section('title','発送履歴・発送済み')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shipped') }}">発送履歴</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送済み</li>
            </ol>
        </nav>
    </div>




    <div class="container py-4 mb-5">
        <h3 class="d-none d-md-block mb-5">発送履歴</h3>

        <section>
            <div class="card p-3 bg-white">
                <ul class="nav text-center mb-md-5">
                    <li class="col">
                        <div class="nav-link text-dark border-bottom ">
                            <a class="text-dark" href="{{ route('shipped.waiting') }}"
                            >発送待ち</a>
                        </div>
                    </li>
                    <li class="col">
                        <div class="nav-link text-dark border-bottom border-primary border-2" aria-current="page">
                            発送済み
                            @php $unread_count = Auth::user()->unread_send_shippeds_count; @endphp
                            @if ( $unread_count )
                                <!--未読-->
                                <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                            @endif
                        </div>
                    </li>
                </ul>
                {{-- <ul class="nav text-center mb-5">
                    <li class="col">
                      <div class="nav-link text-dark border-bottom border-primary border-2" aria-current="page">発送待ち</div>
                    </li>
                    <li class="nav-link text-dark border-bottom col">
                        <a class="text-dark" href="{{ route('shipped.send') }}">
                            発送済み
                            @php $unread_count = Auth::user()->unread_send_shippeds_count; @endphp
                            @if ( $unread_count )
                                <!--未読-->
                                <span class="badge rounded-pill bg-warning">{{$unread_count}}</span>
                            @endif
                        </a>
                    </li>
                </ul> --}}

                <table class="table bg-white my-3">
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
                </table>
            </div>

        </section>
    </div>
@endsection
