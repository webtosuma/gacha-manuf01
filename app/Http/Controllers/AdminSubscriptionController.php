<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminPointSailRequest;
use App\Models\PointSail;
/*
| =============================================
|  サイト管理者 サブスクプラン コントローラー
| =============================================
*/
class AdminSubscriptionController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # サブスクプラン
        $subscriptions = PointSail::where('is_subscription',true)//サブスクのみ
        ->orderByDesc('is_published')//公開中のみ上
        ->orderByDesc('value','asc')//ポイントが高い順
        ->get();

        # 管理者メニューの表示
        $admin_menu = true;

        return view('admin.subscription.index',compact('subscriptions','admin_menu'));
    }




    /**
     * 契約中ユーザー
     *
     * @param  \App\Models\PointSail $subscription
     * @return \Illuminate\Http\Response
     */
    public function current_user(PointSail $subscription)
    {
        # 契約中
        $user_subscriptions = $subscription->user_subscriptions;

        return view('admin.subscription.current_user',compact('user_subscriptions','subscription'));
    }



    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscription = new PointSail([
            'price'       => 0,
            'value'       => 0,
            'is_published'=> 0,
            'sub_billing_cycle' => '月額',
        ]);

        # 請求サイクル選択肢
        $billing_cycles = PointSail::SubscriptionBillingCycles();

        return view('admin.subscription.create', compact('subscription','billing_cycles'));
    }



    /**
     * 登録
     *
     * @param  \Illuminate\Http\AdminPointSailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminPointSailRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $subscription = new PointSail( $inputs );
        $subscription->save();


        # 返信メッセージ
        return redirect()->route('admin.subscription')
        ->with(['alert-primary'=>'販売ポイントを新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\PointSail $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(PointSail $subscription)
    {
        # サブスクでない商品の表示不可
        if( !$subscription->is_subscription){ return \App::abort(404); }

        # 請求サイクル選択肢
        $billing_cycles = PointSail::SubscriptionBillingCycles();

        return view('admin.subscription.edit', compact('subscription','billing_cycles'));
    }



    /**
     * 更新
     *
     * @param  \Illuminate\Http\AdminPointSailRequest  $request
     * @param  \App\Models\PointSail $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(AdminPointSailRequest $request, PointSail $subscription)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $subscription );

        # 非公開のとき、関連するサブスクガチャを非公開
        if( ! $inputs['is_published'] )
        {
            foreach ($subscription->sub_gachas as $sub_gacha){
                $sub_gacha->update(['published_at'=>null]);
            }
        }

        # DBデータの更新
        $subscription->update( $inputs );


        # リダイレクト
        return redirect()->route('admin.subscription')
        ->with(['alert-warning'=>'販売ポイント情報を更新しました。']);
    }



    /**
     * 削除
     *
     * @param  \App\Models\PointSail $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy( PointSail $subscription )
    {
        # 非公開のとき、関連するサブスクガチャを非公開
        foreach ($subscription->sub_gachas as $sub_gacha){
            $sub_gacha->update([
                'published_at'   =>null,//非公開
                'subscription_id'=>null,//通常ガチャ
            ]);
        }

        $subscription->delete();

        # リダイレクト
        return redirect()->route('admin.subscription')
        ->with(['alert-danger'=>'販売ポイント情報を削除しました。']);
    }



    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PointSail $subscription //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $subscription=null )
    {
        $inputs = $request->only(
            'sub_label'         , //セブスク用-見出し (2025/03/2追加)
            'sub_description'   , //サブスク用-説明文 (2025/03/2追加)
            'sub_image'         , //サブスク用-画像   (2025/03/2追加)
            'sub_billing_cycle' , //セブスク用-請求期間 (2025/03/2追加)

            'value'             , // '付与ポイント数',
            'price'             , // 'ポイント販売価格',
            'is_published'      , // '公開設定',
            'stripe_id'         , //Stipeの商品ID
        );

        # サブスクリプション
        $inputs['is_subscription'] = true;

        # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
            $inputs['sub_label'] = urldecode($inputs['sub_label']);
            $inputs['sub_description']  = urldecode($inputs['sub_description']) ;

        # ストレージ更新の処理（商品説明）sub_description
            $old_text = $subscription? $subscription->sub_description: null;  //更新前のファイルパステキスト
            $new_text = $inputs['sub_description'];       //新しい入力テキスト
            $dir = 'upload/infomation/sub_description/';  //保存先ディレクトリ
            $inputs['sub_description'] = Method::uploadStorageText($dir, $new_text, $old_text);


        # ストレージ画像ファイルの更新（イメージ画像）sub_image
            $dir = 'upload/infomation/sub_image/';             //保存先ディレクトリ
            $request_file    = $request->file('sub_image');     //画像のリクエスト
            $old_image_path  = $subscription? $subscription->sub_image: null; //更新前の画像パス
            $image_dalete    = $request->sub_image_dalete;      //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;       //コピー用画像パス

            $inputs['sub_image'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);

        # お得分の計算
        $service = $request->value - $request->price;
        $inputs['service'] = $service > 0? $service : 0 ;

        return $inputs;
    }
}
