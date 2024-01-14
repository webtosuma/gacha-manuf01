<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\AdminPrizeRequest;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\Prize;
use App\Models\PrizeRank;

/*
| =============================================
|  サイト管理者　商品 コントローラー
| =============================================
*/
class AdminPrizeController extends Controller
{
    /**
     * 一覧
     *
     * @param Integer $category_id
     * @return \Illuminate\Http\Response
     */
    public function index($category_id='')
    {
        return view('admin.prize.index', compact('category_id'));
    }




    /**
     * 新規作成
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        # カテゴリーIDの指定
        $category_id = $request->gacha_category_id ? $request->gacha_category_id :'';

        # カテゴリーデータ
        $categories = GachaCategory::all();

        # 評価ランクデータ
        $ranks = PrizeRank::all();

        # 新規作成モデル
        $prize = new Prize([
            'category_id'=>$category_id,
            'point'=>0,
        ]);

        return view('admin.prize.create', compact('prize','categories','ranks') );
    }



    /**
     * 登録
     *
     * @param \App\Http\Requests\AdminPrizeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminPrizeRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );


        # DBデータの新規登録
        $prize = new \App\Models\Prize( $inputs, $prize=null );
        $prize->save();
        $request->session()->regenerateToken();// 二重送信防止


        $category_id = $request->category_id;
        return redirect()->route('admin.prize', $category_id)
        ->with(['alert-primary'=>'商品情報を更新しました']);
    }



    /**
     * 編集
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Prize;
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Prize $prize)
    {
        # カテゴリーIDの指定
        $category_id = $request->gacha_category_id ? $request->gacha_category_id :'';

        # カテゴリーデータ
        $categories = GachaCategory::all();

        # 評価ランクデータ
        $ranks = PrizeRank::all();

        return view('admin.prize.edit', compact('prize','categories','ranks') );
    }




    /**
     * 更新
     *
     * @param \App\Http\Requests\AdminPrizeRequest $request
     * @param \App\Models\Prize;
     * @return \Illuminate\Http\Response
     */
    public function update(AdminPrizeRequest $request, Prize $prize)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $prize );

        # DBデータの更新
        $prize->update($inputs);
        $request->session()->regenerateToken();// 二重送信防止



        $category_id = $request->category_id;
        return redirect()->route('admin.prize', $category_id)
        ->with(['alert-warning'=>'商品情報を更新しました']);

    }


    /** 削除 => AdminApiPrizeController　 */



    /**
     * CSVファイルのダウンロード
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function download_csv(Request $request)
    {
        # 商品情報の取得 (AdminApiPrizeControllerメソッド)
        $prizes = AdminApiPrizeController::getPrizes($request);

        $data_array = [];
        $header = ['カテゴリー名','商品コード','商品名','ランク','交換ポイント','更新日時'];
        $header = self::convertArrayToSJIS($header);
        $data_array[] = implode(',',$header);

        foreach ($prizes as $prize) {
            $data = [
                $prize->category->name, //カテゴリー名
                $prize->code,       //商品コード
                $prize->name,       //商品名
                $prize->rank->name, //ランク
                $prize->point,      //交換ポイント
                $prize->updated_at->format('Y-m-d H:i:s'), //更新日時
            ];

            #UTF-8にエンコード
            $data = self::convertArrayToSJIS($data);

            # カンマに変換
            $data_array[] = implode(',',$data);
        }
        // dd($data_array);


        # パスワード一覧テキストの保存
        $contents = implode("\n",$data_array);     //改行文章に変換し、変数に保存
        $path = 'upload/prize/csv/data.csv';//ファイルパス
        Storage::put($path,$contents);

        # パスワード一覧テキストのダウンロード
        return Storage::download($path,'cardFesta登録商品一覧.csv');
    }


        /** UTF-8からSJISにフォーマット */
        public static function convertArrayToSJIS($data)
        {
            array_walk_recursive($data, function (&$value) {
                // $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                $value = mb_convert_encoding($value, 'SJIS', 'UTF-8');
            });

            return $data;
        }



        /**
         * 入力データの加工 self::processingInputs( $request )
         *
         * @param \Illuminate\Http\Request $request
         * @param \App\Models\Prize $prize //新規登録のとき===null
         * @return Array
         */
        public static function processingInputs( $request, $prize=null )
        {
            $inputs = $request->only(
                'category_id',  //リレーション
                'code',   //商品コード
                'name',   //名前
                'image',  //画像
                'rank_id',//ランクID
                'point',  //交換ポイント値
            );

            # ポイント更新記録
                //新規登録
                if( !($prize && $prize->point == $request->point) ){
                    $inputs['point_updated_at'] = now();
                }

            # ストレージ画像ファイルの更新（イメージ画像）
                $dir = 'upload/prize/image/';             //保存先ディレクトリ
                $request_file    = $request->file('image');     //画像のリクエスト
                $old_image_path  = $prize? $prize->image: null; //更新前の画像パス
                $image_dalete    = $request->image_dalete;      //画像を削除するか否か
                $copy_image_puth = $request->copy_image_puth;       //コピー用画像パス

                $inputs['image'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);
            //

            return $inputs;
        }
}
