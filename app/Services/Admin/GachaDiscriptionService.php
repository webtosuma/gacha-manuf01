<?php

namespace App\Services\Admin;
use Illuminate\Support\Facades\DB;
use App\Models\Gacha;
use App\Services\StorageService;
/*
| =============================================
|  Admin ガチャの商品説明 サービス 
| =============================================
*/
class GachaDiscriptionService
{
    public function __construct(
        protected StorageService $storage,
    ){}


    /**
     * 更新処理
    */
    public function update( $request, Gacha $gacha): Gacha
    {
        return DB::transaction(function () use ($request, $gacha) {

            $discriptions = $gacha->discriptions;
            foreach ($discriptions as $num => $discription) {

                # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
                $sorce = isset($request->sorces[$num]) ? urldecode( $request->sorces[$num] ) : null;

                # ストレージ更新の処理（商品説明）sorce
                $old_text = $discription->sorce;  //更新前のファイルパステキスト
                // $new_text = $request->sorces[$num];             //新しい入力テキスト
                $new_text = $sorce;             //新しい入力テキスト
                $dir = 'upload/discription/sorce/';      //保存先ディレクトリ
                $sorce = $this->storage->uploadText($dir, $new_text, $old_text);



                # ストレージ画像ファイルの更新（イメージ画像）image
                $key = 'gri'.$discription->gacha_rank_id; //識別キー gri100

                $dir = 'upload/discription/image';                  //保存先ディレクトリ
                $request_file    = $request->file( $key.'-image' );  //画像のリクエスト
                $old_image_path  = $discription->image;              //更新前の画像パス
                $image_dalete    = $request[$key.'-image_dalete'];   //画像を削除するか否か

                $image = $this->storage->uploadImage(
                    $dir, $request_file, $old_image_path, $image_dalete
                );


                # 更新内容の保存
                $inputs = compact('sorce','image',);
                $discription->update($inputs);

            }

            return $gacha;

        });
    }
}