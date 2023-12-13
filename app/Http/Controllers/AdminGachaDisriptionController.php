<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\Prize;
/*
| =============================================
|  サイト管理者 ガチャの商品説明 コントローラー
| =============================================
*/
class AdminGachaDisriptionController extends Controller
{
    /**
     * 編集
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gacha $gacha)
    {
        return view('admin.gacha.discription.edit', compact('gacha'));
    }



    /**
     * 更新
     *
     * @param \Illuminate\Http\Request $request
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gacha $gacha)
    {
        # ランク別詳細情報
        $discriptions = $gacha->discriptions;
        foreach ($discriptions as $num => $discription) {

            # ストレージ更新の処理（商品説明）sorce
            $old_text = $discription->sorce;  //更新前のファイルパステキスト
            $new_text = $request->sorces[$num];             //新しい入力テキスト
            $dir = 'upload/discription/sorce/';      //保存先ディレクトリ
            $sorce = Method::uploadStorageText($dir, $new_text, $old_text);



            # ストレージ画像ファイルの更新（イメージ画像）image
            $key = 'gri'.$discription->gacha_rank_id; //識別キー gri100

            $dir = 'upload/discription/image/';                  //保存先ディレクトリ
            $request_file    = $request->file( $key.'-image' );  //画像のリクエスト
            $old_image_path  = $discription->image;              //更新前の画像パス
            $image_dalete    = $request[$key.'-image_dalete'];   //画像を削除するか否か

            $image = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete);


            # 更新内容の保存
            $inputs = compact('sorce','image',);
            $discription->update($inputs);

            // dd($inputs);
        }


        # リダイレクト
        return redirect()->route('admin.gacha.discription.edit',$gacha)
        ->with(['alert-warning'=>'ガチャの商品説明を更新しました']);
    }

}
