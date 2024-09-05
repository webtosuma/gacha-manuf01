<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UserShipped;
use App\Models\Prize;
use Illuminate\Support\Facades\Mail;
/*
| =============================================
|  サイト管理者 発送待ち コントローラー
| =============================================
*/
class AdminShippedWaitingController extends Controller
{
    /** 発送状況ID */
    public function StateId(){
        return 11 ;// '発送待ち',
    }

    /** ページネーション数 */
    public function pagenate_count(){ return 20 ;}


    /**
     * 一覧
     *
     * @param Request $request　
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # 発送状況ID
        $state_id = self::StateId();

        # 発送申請：発送待ち
        $shippeds = UserShipped::where('state_id', $state_id)->get();

        $paginate_shippeds = UserShipped::where('state_id', $state_id)
        ->paginate( $this->pagenate_count() );//ページネーション

        # ページ
        $page = $request->page;


        return view('admin.shipped.waiting.index', compact(
            'shippeds','paginate_shippeds','page'
        ) );
    }



    /**
     * 詳細
     *
     * @param  \App\Models\UserShipped $user_shipped
     * @return \Illuminate\Http\Response
     */
    public function show( UserShipped $user_shipped )
    {
        # 発送待ちでなければ、完了へリダイレクト
        $state_id = self::StateId();
        if( $user_shipped->state_id != $state_id ){
            return redirect()->route('admin.shipped.send.show',$user_shipped);
        }

        # 発送ポイント
        $shipped_point = - (int) $user_shipped->point_history->value;

        # お届け先アドレス
        $user_address = $user_shipped->user_address;

        # 発送するユーザー商品を取得/データチェック
        $user_prizes = $user_shipped->user_prizes;

        # 発送する商品:種類別($shipped_prizes)
        $id_array = $user_prizes->pluck('prize_id')->toArray();
        $shipped_prizes = Prize::find( $id_array );//カードの重複除去
        foreach ($shipped_prizes as $shipped_prize) {//カードの重複枚数保存
            $shipped_prize->count = array_count_values( $id_array )[ $shipped_prize->id ] ?? 0;
        }

        return view('admin.shipped.waiting.show', compact(
            'user_shipped','shipped_point','user_address','user_prizes','shipped_prizes'
        ) );
    }


    /**
     * 発送処理
     *
     * @param  \App\Models\UserShipped $user_shipped
     * @return \Illuminate\Http\Response
     */
    public function update( UserShipped $user_shipped )
    {
        # 発送情報の更新
        $user_shipped->update([
            'state_id' => 21,//発送状況:'発送済み'
            'shipment_at'=>now(), //発送日時
        ]);


        # ユーザーへのメール送信
        self::SendEmail($user_shipped);


        return redirect()->route('admin.shipped.send.show',$user_shipped)
        ->with('alert-warning','発送通知を送信しました');
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
            'view'    => 'emails.user_shipped_send' , //テンプレート
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
        # 発送状況ID
        $state_id = self::StateId();

        # 発送申請：発送待ち
        $paginate_shippeds = UserShipped::where('state_id', $state_id)
        ->paginate( $this->pagenate_count() );//ページネーション


        # CSVデータ作成
        $data_array = [];
        $header = [
            'お届け先郵便番号','お届け先氏名','お届け先敬称',
            'お届け先住所1行目','お届け先住所2行目','お届け先住所3行目','お届け先住所4行目',
            '内容品'];
        $header = self::convertArrayToSJIS($header);
        $data_array[] = implode(',',$header);

        foreach ($paginate_shippeds as $user_shipped) {

            # お届け先アドレス
            $user_address = $user_shipped->user_address;

            $data = [
                $user_address->postal_code, //お届け先郵便番号
                $user_address->name.'　様',  //お届け先氏名
                $user_address->name.'　様',  //お届け先敬称
                $user_address->todohuken,   //お届け先住所1行目
                $user_address->shikuchoson, //お届け先住所2行目
                $user_address->number,      //お届け先住所3行目
                '', //お届け先住所4行目
                'ホビー・カード', //内容品
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
