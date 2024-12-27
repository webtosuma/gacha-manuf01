@extends('admin.layouts.app')


@section('title','ガチャ管理')


@section('meta') @php
$active_key = 'gacha';
@endphp @endsection

@section('style')
<style>
    /* ガチャのホバーアニメーション */
    .hover_anime:hover{
        position: relative;
        transform: scale(1.05) translateY(-1rem);

        transition: all .2s;
    }
</style>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">ガチャ管理</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">ガチャ管理</h2>


        @php
        /* ガチャ制限数 */
        $limig_gacha_count = env('LIMIT_GACHA_COUNT');
        @endphp
        @if( $limig_gacha_count )
        <!--公開・公開予約数制限あり-->
        <div class="alert alert-success border-0" role="alert">
            <h6 class="fw-bold text-success">公開ガチャ数の制限あり</h6>
            同じカテゴリー内で<span class="fw-bold">公開・公開予約</span>できるガチャ数は、合わせて<span class="fw-bold">{{$limig_gacha_count}}件</span>以内です。
        </div>
        @endif



        <section class="mb-3">
            <ul class="nav nav-tabs">
                @php
                $active = !$category_code ? 'active' : '';
                @endphp
                <li class="nav-item">
                    <a  href="{{ route('admin.gacha') }}"
                    class="nav-link {{ $active }}"
                    >{{ 'すべて' }}</a>
                </li>

                @foreach ($categories as $category)
                    @php
                    $active = $category_code == $category->code_name ? 'active' : '';
                    @endphp
                    <li class="nav-item">
                        <a  href="{{ route('admin.gacha',$category->code_name) }}"
                        class="nav-link {{ $active }}"
                        >{{ $category->name }}</a>
                    </li>
                @endforeach
              </ul>
        </section>

        <!--card-->
        <section class="row gy-5 my-3 overflow-hidden g-2">
            <div class="col-12 col-md-4 col-lg-3 ">
                <a href="{{ route('admin.gacha.create',$category_code) }}"
                class="btn btn-primary shadow text-white
                hover_anime w-100 h-100" style="border-radius:1rem;"
                ><div class="d-flex align-items-center justify-content-center h-100 fs-3"
                >新規登録</div></a>


            </div>
            @foreach ($gachas as $gacha)
                <div class="col-12 col-md-4 col-lg-3 ">
                    <div class="position-relative">


                        <div class="">公開{{$gacha->published_at??'--/--/-- --:--:--'}}</div>


                        <a href="{{route('admin.gacha.show',$gacha)}}"
                        class="card border-secondary border-0 shadow bg-white h-100
                        text-dark text-center overflow-hidden text-decoration-none
                        hover_anime" style="border-radius:1rem;">

                            <div class="d-flex gap-1 flex-wrap p-2" style="font-size:11px;">
                                <!--広告-->
                                @if($gacha->sponsor_ad)
                                    <div class="border border-danger text-danger px-3 rounded-pill">
                                        広告
                                        <span>{{'×'.$gacha->sponsor_ads->count()}}</span>
                                    </div>
                                @endif
                                <!--ガチャの種類-->
                                <span class="border px-3 rounded-pill">{{ $gacha->types()[$gacha->type] }}</span>
                                <!--ランクの指定-->
                                @if( env('NEW_TICKET_SISTEM',false) )
                                    <span class="border px-3 rounded-pill">{{ $gacha->user_rank_id!==null ? $gacha->user_rank->label : '全ての' }}会員</span>
                                @endif
                                <!--時間帯-->
                                <span class="border px-3 rounded-pill">{{ $gacha->min_time.'〜'.$gacha->max_time }}</span>
                            </div>

                            <!--image-->
                            @include('gacha.common.top_image')

                            <!--metter-->
                            @if ( $gacha->is_published )
                                <div class="card-body py-1">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            @include('includes.point_icon')
                                            <div class="">
                                                1回×
                                                <span class="fs-3">
                                                    <number-comma-component number="{{ $gacha->one_play_point }}"></number-comma-component>
                                                </span>pt
                                            </div>
                                        </div>
                                    </div>
                                    <p class="card-text m-0">
                                        残り
                                        <number-comma-component number="{{ $gacha->remaining_count }}"></number-comma-component>
                                        /
                                        <number-comma-component number="{{ $gacha->max_count }}"></number-comma-component>
                                    </p>
                                </div>
                            @elseif( $gacha->published_at )
                                <div class="card-body bg-success text-center text-white">
                                    <h5 class="m-0">公開予約中</h5>
                                    <div >{{
                                        \Carbon\Carbon::parse($gacha->published_at)->format('Y/m/d H:i').'公開予定'
                                    }}</div>
                                </div>
                            @else
                                <div class="card-body bg-secondary text-center text-white">
                                    <h3>非公開</h3>
                                </div>
                            @endif
                        </a>


                        <div class="dropdown position-absolute top-0 end-0" style="z-index:100;">
                            <button class="btn border bg-white rounded-circle" type="button"
                            id="dropdownMenuButton{{ $gacha->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $gacha->id }}"  style="z-index:100;">
                                <li><a class="dropdown-item"
                                href="{{ route('admin.gacha.show',$gacha) }}"
                                >詳細情報を見る</a></li>
                                <li><a class="dropdown-item"
                                href="{{ route('admin.gacha.edit',$gacha) }}"
                                >編集する</a></li>
                                <li><form action="{{route('admin.gacha.copy', $gacha)}}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"
                                    >コピーする</button>
                                </form></li>

                                <li><button type="button" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{'delete'.$gacha->id}}"
                                class="dropdown-item"
                                >削除する</button></li>
                            </ul>
                        </div>

                    </div>
                </div>
            @endforeach
        </section>

    </div>


    <!--削除モーダル-->
    <div class="" style="height: 0;">
        @foreach ($gachas as $gacha)
            <!-- Modal -->
            <form action="{{ route('admin.gacha.destroy', $gacha) }}" method="post">
                @csrf
                @method('DELETE')

                <delete-modal-component
                index_key="{{'delete'.$gacha->id}}"
                icon="bi-trash"
                func_btn_type="submit"
                button_class="invisible">
                    <div>
                        <span class="fw-bold">『{{$gacha->name}}』</span>を削除します。
                        <br />よろしいですか？
                    </div>
                </delete-modal-component>
            </form>
        @endforeach
    </div>

@endsection
