<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Text;
/*
| =============================================
|  共通利用  BASEリソース
| =============================================
*/
class BaseResource extends JsonResource
{

    /** 画像なしの時の画像 */
    public static function noImage(){ return asset( 'storage/site/image/no_image.jpg' );}

    /**
     * 画像パス生成
     */
    protected function getImagePath(?string $path): string
    {
        return $path && Storage::exists($path)
            ? asset('storage/'.$path)
            : self::noImage()
        ;
    }


    /**
     * ストレージテキスト取得
     */
    protected function getStorageText(?string $text): ?string
    {
        if (!$text) return null;

        $path = str_replace(["\r\n", "\r", "\n"], '', $text);

        return Storage::exists($path)
            ? Storage::get($path)
            : $text;
    }

    /**
     * 公開判定
     */
    protected function getPublishedStatus($start, $end): int
    {
        $now = now();

        # start_at,end_at があって、end_atの方が小さい値になってしまっているとき
        if ($start && $end && $start > $end) return 0;

        # 未入力
        if (!$start && !$end) { return 0; }

        # startが未入力
        if (!$start) { return 0; }

        # end_at があって、すでに終わっている場合 0
        if ($end && $now > $end) return 0;

        # start_at があって、まだ始まっていない場合 2
        if ($start && $now < $start) return 2;

        # ここまで来たら有効期間内（または片方nullで条件を満たす）
        return 1;
    }



    /**
     * [画像パス]ガチャマシーンHead img_path_card_head
     * @return String
    */
    public function getImgPathCardHead()
    {

        $text_model = new Text();

        return $text_model->gacha_settings_card_image
        ? $text_model->gacha_settings_card_image_head : null;
    }



    /**
     * [画像パス]ガチャマシーンBody img_path_card_body
     * @return String
    */
    public function getImgPathCardBody()
    {

        $text_model = new Text();

        return $text_model->gacha_settings_card_image
        ? $text_model->gacha_settings_card_image_body : null;
    }


}
