<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\StoreKeep;
use App\Models\StoreHistory;
/*
| =============================================
|  ストアーAdmin 発送受付 コントローラー
| =============================================
*/
class AdminStoreShippedController extends Controller
{
    /**
     * 表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('store_admin.shipped.index');
    }




    /**
     * 詳細
     *
     * @param  StoreHistory $store_history
     * @return \Illuminate\Http\Response
     */
    public function show(StoreHistory $store_history)
    {
        return view('store_admin.shipped.show',compact(
            'store_history'
        ));
    }



    /**
     * 発送処理
     *
     * @param  StoreHistory $store_history
     * @return \Illuminate\Http\Response
     */
    public function update( StoreHistory $store_history )
    {
        # 発送情報の更新
        $store_history->update([
            'state_id' => 21,//発送状況:'発送済み'
            'shipment_at'=>now(), //発送日時
        ]);


        # ユーザーへのメール送信
        self::SendEmail($store_history);


        return redirect()->route('admin.store.shipped')
        ->with(['alert-success'=>'発送通知を送信しました']);
    }



        /** ユーザーへのメール送信 */
        public function SendEmail($store_history)
        {
            # ユーザー情報
            $user = $store_history->user;

            # 発送する商品:種類別($shipped_prizes)
            $store_keeps = $store_history->store_keeps;

            # 変数の保存
            $inputs = compact('user','store_history','store_keeps');

            // return view('emails.user_shipped_send',$inputs);
            Mail::to( $user->email ) //宛先
            ->send(new \App\Mail\SendHtmlMailMailable([
                'inputs'  => $inputs, //入力変数
                'view'    => 'emails.store_shipped.user' , //テンプレート
                'subject' => 'ご注文の商品が発送されました', //件名
            ]) );
        }



    /**
     * CSVファイルのダウンロード
     *
     * @param Request $request　
     * @return \Illuminate\Http\Response
     */
    public function dl_csv(Request $request)
    {
        // dd($request->all());

        # 商品履歴
        $store_histories  = StoreHistory::forUserAdmin($request)
        ->find($request->ids);

        # CSVデータ作成
        $data_array = [];
        $header = [
            'お届け先郵便番号','お届け先氏名','お届け先敬称',
            'お届け先住所1行目','お届け先住所2行目','お届け先住所3行目','お届け先住所4行目',
            '内容品','発送商品',
        ];
        $header = self::convertArrayToSJIS($header);
        $data_array[] = implode(',',$header);

        foreach ($store_histories as $store_history) {

            # 発送商品情報($prizes_string)
            $string = '';
            $store_keeps = $store_history->store_keeps;

            foreach ($store_keeps as $store_keep) {
                $string .= "[{$store_keep->store_item->code}]{$store_keep->store_item->name} ×{$store_keep->count}点　";
            }

            # お届け先アドレス
            $address = $store_history->address;

            $data = [
                $address->postal_code, //お届け先郵便番号
                $address->name.'　様',  //お届け先氏名
                $address->name.'　様',  //お届け先敬称
                $address->todohuken,   //お届け先住所1行目
                $address->shikuchoson, //お届け先住所2行目
                $address->number,      //お届け先住所3行目
                '',                    //お届け先住所4行目
                '',                    //内容品
                $string,             //発送商品
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




    /**
     * API 一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function api_index(Request $request)
    {
        # 購入済み商品(store_histories)
        $user = Auth::user();
        $store_histories  = StoreHistory::forUserAdmin($request)
        // ->where('user_id',$user->id)
        ->paginate(10);

        foreach ($store_histories as $store_history) {
            $store_history->sumItemsCount = $store_history->sumItemsCount();  //購入するカート商品の還元ポイント
        }

        # 合計数
        $total_count = $request->page ? null
        : StoreHistory::forUserAdmin($request)->count();

        # 月データ(初回の読み込み時のみ)
        $months = !$request->page ? self::getMonts($request->state_id) : null;

        # 状態
        $states = StoreHistory::states();

        # 入力値
        $inputs = $request->all();


        return response()->json( compact(
            'store_histories','total_count','months','states','inputs'
        ) );
    }



        /**
         * 月データリスト（getMonths)
         * @return Array
        */
        public function getMonts($state_id)
        {
            $user = Auth::user();

            return StoreHistory::selectRaw('DATE_FORMAT(done_at, "%Y-%m") as format, COUNT(*) as total')
            // ->where('user_id',$user->id)//ログインユーザーのデータのみ
            ->where('done_at','<>',null)//購入済みの商品のみ
            ->where('state_id',$state_id)//発送待ち・済み
            ->groupBy('format')
            ->orderBy('format', 'desc')
            ->get()
            ->map(function ($item) {
                // 月のフォーマットを「Y年n月」に変換
                $formattedMonth = date('Y年n月', strtotime($item->format . '-01'));
                $date_stanp = date('Y/m/d', strtotime($item->format . '-01'));
                return [
                    'format'     => $formattedMonth.'（'.$item->total.'）',
                    'date_stanp' => $date_stanp,
                    'total'      => $item->total
                ];
            });
        }

}
