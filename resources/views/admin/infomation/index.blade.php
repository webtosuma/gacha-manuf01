@extends('admin.layouts.app')


@section('title','お知らせ')


@section('meta') @php
$active_key = 'infomation';
$active_submenu = true;
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">お知らせ</li>
            </ol>
        </nav>



        <h2 class="mb-5 py-3 border-bottom">お知らせ</h2>

        <section class="mx-auto px-3" style="max-width:900px;">


            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('admin.infomation.create') }}"
                class="btn btn-primary text-white shadow">
                <i class="bi bi-plus-lg"></i>
                {{'新規登録'}}
                </a>



                <a href="{{ route('admin.infomation') }}"
                class="btn border rounded-pill
                @if($anpublished==0) disabled text-white btn-success  @endif">
                {{'公開中'}}
                </a>

                <a href="{{ route('admin.infomation',['anpublished'=>1]) }}"
                class="btn border rounded-pill
                    @if($anpublished==1) disabled text-white btn-danger @endif">
                {{'非公開'}}
                </a>

            </div>

            <div class="list-group "
            style="background:rgb(255, 255, 255, .7);">
                @forelse ($infomations as $infomation)
                    <div class="list-group-item border- pozition-relative">
                        <div class="row mx- align-items-center py-2 g-2">
                            <div class="col-auto">
                                @if( $infomation->is_published )
                                    <!--公開-->
                                    <span class="badge rounded-pill bg-success">{{ '公開中' }}</span>
                                @elseif( $infomation->published_at > now() )
                                    <!--公開予約-->
                                    <span class="badge rounded-pill bg-warning">{{ '予約中' }}</span>
                                @else
                                    <!--非公開-->
                                    <span class="badge rounded-pill bg-danger">{{ '非公開' }}</span>
                                @endif
                            </div>
                            <div class="col-auto" style="width: 5rem;">
                                @if( $infomation->is_slide )
                                    <div class="d-inline-block px-2 py-1 bg-light form-text">スライド</div>
                                @endif
                            </div>
                            <div class="col-12 col-md">
                                <a href="{{ route('admin.infomation.show',$infomation) }}" class="border-bottom border-primary">

                                    {{ $infomation->title }}

                                </a>
                            </div>
                            <div class="col col-md-auto text-secondary">
                                <div class="">
                                    <!--登録日-->
                                    <i class="bi bi-pencil-fill"></i>
                                    {{ $infomation->created_at->format('Y.m.d') }}
                                </div>
                                <div class="">
                                    <!--公開日-->
                                    <i class="bi bi-eye"></i>
                                    {{ $infomation->published_at? $infomation->published_at->format('Y.m.d') : '----.--.--' }}
                                </div>
                                <div class="">
                                    <!--メール送信日-->
                                    <i class="bi bi-envelope"></i>
                                    {{ $infomation->send_email_at? $infomation->send_email_at->format('Y.m.d') : '----.--.--' }}
                                </div>

                            </div>
                            @if( $infomation->image_path )
                                <div class="col-auto" style="width:3rem;">
                                    <ratio-image-component
                                    url="{{ $infomation->image_path }}" style_class="ratio ratio-1x1 w-100 rounded"
                                    ></ratio-image-component>
                                </div>
                            @endif
                            <div class="col-12 col-md-auto">
                                <div class="row g-1 justify-content-end">

                                    <div class="col-auto">
                                        <!--編集ボタン-->
                                        <a href="{{ route('admin.infomation.edit',$infomation) }}"
                                        class="btn btn-sm btn-light border "
                                        ><i class="bi bi-pencil-fill"></i></a>
                                    </div>
                                    <div class="col-auto">
                                        <!--メール送信ボタン-->
                                        <a href="{{route('admin.infomation.email',$infomation)}}"
                                        class="btn btn-sm btn-light border "
                                        ><i class="bi bi-envelope"></i></a>
                                    </div>

                                    <div class="col-auto">

                                        <!--削除モーダル-->
                                        <form action="{{ route('admin.infomation.destroy', $infomation) }}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <delete-modal-component
                                            index_key="{{'delete'.$infomation->id}}"
                                            icon="bi-trash"
                                            func_btn_type="submit"
                                            button_class="btn btn-sm btn-light border ">
                                                <div>
                                                    <span class="fw-bold">『{{$infomation->title}}』</span>を削除します。
                                                    <br />よろしいですか？
                                                </div>
                                            </delete-modal-component>
                                        </form>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                @empty
                    <div class="list-group-item border-0 pozition-relative">
                        <div class="">
                            * お知らせはありません
                        </div>
                    </div>
                @endforelse
            </div>


        </section>

        <!-- ページネーション -->
        <div class="d-flex justify-content-start mt-3">
            {{ $infomations->links('vendor.pagination.bootstrap-4') }}
        </div>

    </div>
@endsection
