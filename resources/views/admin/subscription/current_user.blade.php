@extends('admin.layouts.app')


@section('title',$subscription->sub_label.'-契約ユーザー一覧')


@section('meta') @php
$active_key = 'subscription';
$active_submenu = true;
@endphp @endsection


@section('script')
 <!-- フォームのページ離脱防止アラート -->
 <script src="{{asset('js/page_exit_prevention_alert.js')}}"></script>
@endsection


@section('content')
    <div class="container mb-4">


        {{-- パンくずリスト --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"
                >{{ 'Top' }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.subscription') }}"
                >{{ 'サブスク管理' }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"
                >{{ $subscription->sub_label.'-契約ユーザー一覧' }}</li>
            </ol>
        </nav>

        {{-- <h2 class="mb- py-3 border-bottom">{{ $subscription->sub_label.'-契約ユーザー一覧' }}</h2> --}}

        <a href="{{route('admin.subscription')}}"
        class="btn my-3 border rounded-pill"
        ><i class="bi bi-arrow-left-short"></i>戻る</a>


        <section class="mb-4">
            <div class="row align-items-center">
                <div class="col text-center">
                    @if( $subscription->sub_image_path )
                        <ratio-image-component
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $subscription->sub_label }}"
                        url="{{$subscription->sub_image_path}}"
                        style_class="ratio bg-body {{config('app.gacha_card_ratio')}} rounded-4"
                        ></ratio-image-component>
                    @else
                        <div class="ratio bg-body {{config('app.gacha_card_ratio')}}">
                            <div class="d-flex flex-column align-items-centerr justify-content-center h-100">
                                <div class="fs-4 fw-bold">{{ $subscription->sub_label }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-12 col-md-8">
                    <div class="">
                        <div class="card-body bg-white pb-0">
                            <div class="d-flex align-items-center justify-content-center gap-2 mb- fw-bold">

                                <!--販売価格-->
                                <span>{{ $subscription->sub_billing_cycle }}</span>
                                <h3 class="m-0 fw-bold fs-">{{ number_format( $subscription->price ) }}</h3>
                                <span>円(税込)</span>

                            </div>
                            <div class="d-flex align-items-end justify-content-center gap-1 mb-3 fw-bold">

                                <!--付与ポイント-->
                                <span>毎回更新時に</span>
                                <h5 class="m-0 fw-bold fs-5 text-info">{{ number_format( $subscription->value ) }}</h5>
                                <span>ptを付与</span>

                            </div>
                            <!--説明文-->
                            <div class="d-flex justify-content-center">
                                <p class="text-start fw-bold m-0">
                                    {!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $subscription->sub_description_text) )!!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <h3 class="fs-6 fw-bold">{{'契約ユーザー一覧' }}</h3>
        <section>
            <section class="card card-body bg-white mb-3 overflow-auto">
                <table class="table bg-white my-3" style="min-width:680px;">
                    <thead>
                        <tr class="bg-white text-center">
                            <th scope="col" style="width:4rem;">ID</th>
                            <th scope="col">アカウント</th>
                            <th scope="col">契約開始</th>
                            <th scope="col">継続期間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user_subscriptions as $user_subscription)
                            @php $user = $user_subscription->user @endphp
                            <tr class="bg-white text-center">
                                <td>{{$user->id}}</td>
                                <td><a href="{{route('admin.user.show',$user)}}">{{$user->name}}</a></td>
                                <td>{{$user_subscription->created_at->format('Y/m/d')}}~</td>
                                <td class="fs-5 text-success">{{ $user_subscription->elapsed_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </section>

    </div>
@endsection
