@extends('layouts.app')

<!----- title ----->
@section('title','発送履歴・発送待ち')


@section('content')
    <!--breadcrumb-->
    <div class="container mt-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">トップ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shipped') }}">発送履歴</a></li>
            <li class="breadcrumb-item active" aria-current="page">発送待ち</li>
            </ol>
        </nav>
    </div>




    <div class="container py-4 mb-5">
        <h3 class="mb-5">発送履歴</h3>

        <section>
            <div class="card p-3 bg-white">
                <ul class="nav nav-tabs text-center mb-5">
                    <li class="nav-item col">
                      <div class="nav-link active bg-white" aria-current="page">発送待ち</div>
                    </li>
                    <li class="nav-item col">
                      <a class="nav-link bg-light" href="{{ route('shipped.send') }}">発送済み</a>
                    </li>
                </ul>

                <table class="table bg-white my-3">
                    <!--ヘッド（並べ替えボタン）-->
                    <thead>
                        <tr class="bg-white">
                            <th scope="col" >宛名</th>
                            <th scope="col" class="d-none d-md-table-cell">都道府県</th>
                            <th scope="col" class="d-none d-md-table-cell">発送数</th>
                            <th scope="col" class="">申請日</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shippeds as $shipped)
                            <tr>
                                <td class="py-3">
                                <a href="{{ route('shipped.waiting.show',$shipped) }}"
                                >{{ $shipped->user_address->name }}様</a></td>

                                <td class="d-none d-md-table-cell my-3">{{ $shipped->user_address->todohuken }}</td>
                                <td class="d-none d-md-table-cell my-3">{{ $shipped->user_prizes->count() }}</td>
                                <td class="py-3">{{ $shipped->created_at->format('Y年m月d日') }}</td>
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
