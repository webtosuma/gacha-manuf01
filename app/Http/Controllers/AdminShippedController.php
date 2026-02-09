<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UserShipped;
use App\Models\Prize;
use Illuminate\Support\Facades\Mail;
/*
| =============================================
|  Admin 発送受付 コントローラー
| =============================================
*/
class AdminShippedController extends Controller
{
    /**
     * 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $state_id = $request->state_id;
        return view('admin.shipped.index', compact('state_id') );
    }




    /**
     * 詳細
     *
     * @param  UserShipped $user_shipped
     * @return \Illuminate\Http\Response
     */
    public function show(UserShipped $user_shipped)
    {
        # 発送ポイント
        $shipped_point = - (int) $user_shipped->point_history->value;

        # お届け先アドレス
        $user_address = $user_shipped->user_address;

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = $user_shipped->user_prizes;

        # 発送する商品:種類別($shipped_prizes)
        $id_array = $user_prizes->pluck('prize_id')->toArray();
        $shipped_prizes = Prize::withTrashed()->find( $id_array );//カードの重複除去
        foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
            $shipped_prize->count = array_count_values( $id_array )[ $shipped_prize->id ] ?? 0;
        }

        # 発送企業情報の配列
        $shipping_companies = $user_shipped->shipping_companies;


        return view('admin.shipped.show',compact(
            'user_shipped','shipped_point','user_address','user_prizes','shipped_prizes',
            'shipping_companies',
        ));
    }



    /**
     * 発送処理
     *
     * @param Request $request　
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request)
    {
        $ids = $request->ids;
        $user_shippeds = UserShipped ::find($ids);
        // dd($user_shippeds);

        # 発送情報の更新
        foreach ( $user_shippeds as $user_shipped )
        {
            # 追跡コードの登録
            if( $request->tracking_code && $request->shipping_company_id )
            {
                $traking_columns = ['tracking_code', 'shipping_company_id'];
                $user_shipped->update( $request->only($traking_columns) );
            }

            # 発送完了の登録
            $user_shipped->update([
                'state_id' => 21,//発送状況:'発送済み'
                'shipment_at'=>now(), //発送日時
            ]);
        }

        # ユーザーへのメール送信
        self::SendEmail($user_shipped);

        return redirect()->route('admin.shipped')
        ->with(['alert-success'=>'発送通知を送信しました']);
    }



        /** ユーザーへのメール送信 */
        public function SendEmail($user_shipped)
        {
            # ユーザー情報
            $user = $user_shipped->user;

            # 発送する商品:種類別($shipped_prizes)
            $user_prizes = $user_shipped->user_prizes;
            $id_array = $user_prizes->pluck('prize_id')->toArray();
            $shipped_prizes = Prize::find( $id_array );//カードの重複除去
            foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
                $shipped_prize->count = array_count_values( $id_array )[ $shipped_prize->id ] ?? 0;
            }

            # 変数の保存
            $inputs = compact('user','user_shipped','shipped_prizes');

            // return view('emails.user_shipped_send',$inputs);
            Mail::to( $user->email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs'  => $inputs, //入力変数
                'view'    => 'emails.user_shipped.send' , //テンプレート
                'subject' => 'ご注文の商品が発送されました', //件名
            ]) );
        }



    /**
     * 追跡コードの更新
     *
     * @param Request $request　
     * @param  UserShipped $user_shipped
     * @return \Illuminate\Http\Response
    */
    public function update_trackingcode(Request $request, UserShipped $user_shipped)
    {
        # パラメーターの確認
        if( ! ($request->tracking_code && $request->shipping_company_id) )
        {
            return back()->with([
                'alert-danger'=>'追跡コード・発送企業の両方の入力が必要です。',
                'icon'=>'bi-exclamation-circle'
            ]);
        }

        # 追跡コードの登録
        $traking_columns = ['tracking_code', 'shipping_company_id'];
        $user_shipped->update( $request->only($traking_columns) );

        return redirect()->route('admin.shipped.show',$user_shipped)
        ->with(['alert-warning'=>'追跡コードを更新しました']);
    }



    /**
     * 発送キャンセル
     *
     * @param Request $request　
     * @return \Illuminate\Http\Response
     */
    public function cancell( Request $request)
    {
        $ids = $request->ids;
        $user_shippeds = UserShipped ::find($ids);

        # 発送情報の更新
        foreach ( $user_shippeds as $user_shipped )
        {
            $point_history = $user_shipped->point_history;
            AdminUserPointHistoryController::DeletePrizeShippedHistory($point_history);
        }


        return redirect()->route('admin.shipped')
        ->with(['alert-success'=>'発送申請をキャンセルしました']);
    }



    /**
     * CSVファイルのダウンロード
     *
     * @param Request $request　
     * @return \Illuminate\Http\Response
     */
    public function dl_csv(Request $request)
    {
        # 発送申請：発送待ち
        $user_shippeds = UserShipped::forUserAdmin($request)
        ->find($request->ids);


        # CSVデータ作成
        $data_array = [];
        $header = [
            'お届け先郵便番号','お届け先氏名','お届け先敬称',
            'お届け先住所1行目','お届け先住所2行目','お届け先住所3行目','お届け先住所4行目',
            '内容品','備考','発送商品',
        ];
        $header = self::convertArrayToSJIS($header);
        $data_array[] = implode(',',$header);

        foreach ($user_shippeds as $user_shipped) {

            # 発送商品情報($prizes_string)
            $prizes_string = '';
            $user_prizes = $user_shipped->user_prizes;
            $id_array = $user_prizes->pluck('prize_id')->toArray();
            $shipped_prizes = Prize::find( $id_array );//カードの重複除去
            foreach ($shipped_prizes as $shipped_prize) {
                $count = array_count_values($id_array)[$shipped_prize->id] ?? 0;
                $prizes_string .= "[{$shipped_prize->code}]{$shipped_prize->name} ×{$count}点　";
            }

            # お届け先アドレス
            $user_address = $user_shipped->user_address;

            $data = [
                $user_address->postal_code, //お届け先郵便番号
                $user_address->name,        //お届け先氏名
                $user_address->name.'　様',  //お届け先敬称
                $user_address->todohuken,   //お届け先住所1行目
                $user_address->shikuchoson, //お届け先住所2行目
                $user_address->number,      //お届け先住所3行目
                '',                         //お届け先住所4行目
                '*商品内容',                 //内容品
                $user_address->size,       //希望スニーカーサイズ
                $prizes_string,            //発送商品
            ];

            #UTF-8にエンコード
            $data = self::convertArrayToSJIS($data);

            # カンマに変換
            $data_array[] = implode(',',$data);
        }
        // dd($data_array);


        # 一覧テキストの保存
        $contents = implode("\n",$data_array);     //改行文章に変換し、変数に保存
        $path = 'upload/shippe/Waiting/csv/data.csv';//ファイルパス
        Storage::put($path,$contents);

        # 一覧テキストのダウンロード
        return Storage::download($path,'発送受付一覧.csv');

    }

        /** UTF-8からSJISにフォーマット */
        public static function convertArrayToSJIS($data)
        {
            array_walk_recursive($data, function (&$value) {
                $value = mb_convert_encoding($value, 'SJIS', 'UTF-8');
            });

            return $data;
        }



}
