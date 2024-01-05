<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prize;
use App\Models\GachaCategory;
use App\Models\PrizeRank;
/*
| =============================================
|  商品情報 サイト管理者API コントローラー
| =============================================
*/
class AdminApiPrizeController extends Controller
{
    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Prize::query();

            # キーワード(key_words)から検索
            self::KeyWordSearch($request, $query);

            # カテゴリーの選択
            if(  $request->category_id ){
                $query->where('category_id', $request->category_id);
            }

            # 並び替え：コードネーム順
            if( $request->order_code ){
                $query->orderBy('code', $request->order_code);
            }

            # 並び替え：商品名順
            if( $request->order_name ){
                $query->orderBy('name', $request->order_name);
            }

            # 並び替え：ランク
            if( $request->order_rank_id ){
                $query->orderBy('rank_id', $request->order_rank_id);
            }

            # 絞り込み：ランク
            if( $request->where_rank_id ){
                $query->where('rank_id', $request->where_rank_id);
            }

            # 並び替え：ポイント
            if( $request->order_point ){
                $query->orderBy('point', $request->order_point);
            }

            # 並び替え：更新日
            if( $request->updated_at ){
                $query->orderBy('updated_at', $request->updated_at);
            }else{
                $query->orderByDesc('created_at');
            }

            # 指定したIDを含む
            if( $request->ids ){
                $query->whereIn('id', $request->ids);
            }
            # 指定したIDを除く
            if( $request->not_ids ){
                $query->whereNotIn('id', $request->not_ids);
            }

        $prizes = $query->with('rank')->get();

        # 画像パスの登録
        foreach ($prizes as $prize) {
            $prize->image_path = $prize->image_path;
            $prize->is_used = $prize->is_used;
        }





        # その他のデータ
        $prize_ranks = PrizeRank::all();//評価ランクデータ

        return response()->json( compact('prizes' ,'prize_ranks') );
    }



    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Prize;
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prize $prize)
    {
        # 入力データの加工
        $inputs = AdminPrizeController::processingInputs( $request, $prize );

        # DBデータの更新
        $prize->update($inputs);

        return response()->json(['prize'=>$prize,'requests'=>$inputs]);
    }




    /**
     * 削除
     *
     * @param \App\Models\Prize;
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prize $prize)
    {
        $prize->delete();
        return response()->json(['message'=>'delete OK!']);
    }




    /**
     * キーワード(key_words)から検索するメソッド holiday_summary
     *
     * @param \Illuminate\Http\Request $req
     * @param App\Models\Recruit::query $query
     * @return App\Models\Recruit::query
     */
    public function KeyWordSearch($req, $query)
    {
        #検索パラメータが存在するか
        if( !$req->has('key_words') ){ return; }


        #文字列を配列へ変換
        $key_words = self::ArrayConvertString( $req->key_words );


        #検索条件の絞り込み(全ての条件に該当するデータを検索:and)
        foreach ($key_words as $key_word) {

            $query->where(function($q) use ($key_word) {

                $q->where('code', 'like', '%' . $key_word . '%')
                ->orWhere('name', 'like', '%' . $key_word . '%')
                // ->orWhere('point'        , 'like', '%' . $key_word . '%') //職種
                ;

            });

        }

        // return $query;
    }




    /**
     * 文字列を配列へ変換
     *
     * @param  String $string
     * @return Array
     */
    public function ArrayConvertString($string)
    {
        $string = str_replace('　',' ',$string);
        $array  = explode(' ',$string);
        return $array;
    }

}
