<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
/*
|--------------------------------------------------------------------------
| Admin クーポン API コントローラーa
|--------------------------------------------------------------------------
*/
class AdminApiCouponController extends Controller
{
    /**
     * 一覧API
     *
     * @param \Illuminate\Http\Request $request
     * @return JSON
    */
    public function index(Request $request)
    {
        # 一括処理
        // self::apiBulkDelete($request);//一括削除処理


        # データリスト
        $query = Coupon::query();

            # キーワード(タイトル・コード)検索
            $query->keyWordSearch($request);

            # クーポン配布方法(is_use_code)
            if( in_array( $request->is_use_code,[0,1] ) ){
                $query->where('is_use_code',$request->is_use_code);
            }

            # サービス
            switch ($request->service)
            {
                case 'prize':/*商品*/
                    $query->where('prize_id','<>',null);
                    break;

                case 'point':/*ポイント*/
                    $query->where('prize_id',null);
                    break;
                //
            }

            # 利用回数の種類
            switch ($request->user_type)
            {
                case 'user':/*おひとり様回数*/
                    $query->where('user_type','user');
                    break;

                case 'all_user':/*先着回数*/
                    $query->where('user_type','all_user');
                    break;

                case 'no_count':/*回数制限なし*/
                    $query->where('count',0);
                    break;
                //
            }

            # 有効期限
            switch ($request->is_expiration)
            {
                case 0:/*なし*/
                    $query->where('expiration_at',null);
                    break;

                case 1:/*有効期限前*/
                    $query->where('expiration_at','>=',now());
                    break;

                case 2:/*有効期限前*/
                    $query->where('expiration_at','<' ,now());
                    break;

            }

            ## 公開設定
            switch ($request->is_published)
            {
                case 1://公開中
                $query->where('published_at','<=', now());
                break;

                case 2://予約
                $query->where('published_at','>', now());
                break;

                case 3://未公開
                $query->where('published_at',null);
                break;

            }


            # 新しい順
            $query->orderByDesc('published_at');
            $query->orderByDesc('created_at');

            # リレーション
            $query->with('children');

        $coupons = $query->paginate(10);



        # JSONを返す
        return response()->json( compact(
            'coupons',
            // 'months','type_texts','type_texts_defaults','not_res_conts'
        ));

    }


}
