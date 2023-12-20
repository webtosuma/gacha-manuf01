@extends('admin.layouts.app')


@section('title','管理者')


@section('meta') @php
$active_key = 'register';
@endphp @endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                    >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">管理者</li>
            </ol>
        </nav>



        <h2 class="my-5 py-3 border-bottom">管理者</h2>

        <section>
            <!-- 管理者登録(管理者編集権限者のみ) -->
            @if ( Auth::user()->admin->master )
            <div>
                <!--新規登録-->
                <a href="{{ route('admin.register.create') }}"
                class="btn btn-primary text-white mb-3">
                    <i class="bi bi-person"></i>
                    <i class="bi bi-plus"></i>
                </a>
            </div>
            @endif

            <div class="card w-100 border-bottom-0 overflow-auto">
                <table class="table mb-0"  style="min-width: 900px">
                    <tbody>
                        <tr class="bg-light">
                            <th scope="col">名前</th>
                            <th scope="col">メールアドレス</th>
                            <th scope="col">メール受取設定</th>
                            <th scope="col">管理者修正権限</th>
                            @if ( Auth::user()->admin->master )<th scope="col" style="width:6rem;"></th>@endif<!-- 修正/削除 -->
                        </tr>

                        @foreach ($admins as $admin)
                        <tr>
                            <th scope="row">{{ $admin->name }}</th>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->get_mail ? '受取り' : '---' }}</td>
                            <td>{{ $admin->master ? 'あり' : '---' }}</td>
                            @if (Auth::user()->admin->master)<td class="d-flex gap-2">

                                <!--編集ボタン-->
                                <a href="{{ route('admin.register.edit', $admin->id) }}"
                                class="btn btn-sm btn-light border "
                                ><i class="bi bi-pencil-fill"></i></a>


                                @if ( Auth::user()->admin->id !== $admin->id )
                                    <!--削除モーダル-->
                                    <form action="{{ route('admin.register.destroy', $admin) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <delete-modal-component
                                        index_key="{{'delete'.$admin->id}}"
                                        icon="bi-trash"
                                        func_btn_type="submit"
                                        button_class="btn btn-sm btn-light border ">
                                            <div>この管理者アカウントを削除します。<br />よろしいですか？</div>
                                        </delete-modal-component>

                                    </form>
                                @endif

                            </td>@endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </section>

    </div>
@endsection
