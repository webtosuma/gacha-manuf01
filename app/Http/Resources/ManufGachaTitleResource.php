<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseResource;
/*
| =============================================
|  Manufacturer用　ガチャタイトル リソース
| =============================================
*/
class ManufGachaTitleResource extends BaseResource
{
    public function toArray($request)
    {
        # 公開判定
        $publishedStatus = $this->getPublishedStatus(
            $this->published_start_at,
            $this->published_end_at
        );

        return [

            /* =========================
             * 基本情報
             * ========================= */

                /* 基本情報 */
                'id'    => $this->id,

                'category_id'  => $this->category_id,
                'name'         => $this->name,         //名称
                'image_samune' => $this->image_samune, //サムネ画像
                'price'        => $this->price,        //価格(税込み)
                'code'         => $this->code,         //認証コード


                /* 日時系 */
                'estimated_shipping_at' => $this->estimated_shipping_at, //発送予定日時
                'sales_start_at'        => $this->sales_start_at,        //販売開始日時
                'sales_end_at'          => $this->sales_end_at,          //販売終了日時
                'published_start_at'    => $this->published_start_at,    //公開開始日時
                'published_end_at'      => $this->published_end_at,      //公開終了日時


                /* 詳細情報 */
                'description'     => $this->description,     //説明文
                'set_contents'    => $this->set_contents,    //セット内容
                'prize_size'      => $this->prize_size,      //商品サイズ
                'prize_materials' => $this->prize_materials, //商品素材
                'age_range'       => $this->age_range,       //対象年齢
                'copy_right'      => $this->copy_right,      //コピーライト

                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,



            /* =========================
             * リレーション
             * ========================= */

                'category' => $this->whenLoaded('category', function () {
                    return [
                        'id'        => $this->category->id,
                        'name'      => $this->category->name,
                        'code_name' => $this->category->code_name,
                    ];
                }),

                // 'prizes' => ManufGachaTitlePrizeResource::collection(
                //     $this->whenLoaded('title_prizes')
                // ),

                // 'machines' => ManufGachaTitleMachineResource::collection(
                //     $this->whenLoaded('machines')
                // ),

                'machines' => [],

            /* =========================
             * 画像系
             * ========================= */

                # 画像比率
                'ratio'              => config('app.gacha_card_ratio'),

                # 画像ファイルパス
                'image_samune_path'  => $this->getImagePath($this->image_samune),

                # スライド画像
                'slide_images' => [
                    $this->getImagePath($this->image_samune),
                    $this->getImagePath($this->image_samune),
                    $this->getImagePath($this->image_samune),
                ],


                # [画像パス]ガチャマシーンHead
                'img_path_card_head' => $this->getImgPathCardHead(),

                # [画像パス]ガチャマシーンBody
                'img_path_card_body' => $this->getImgPathCardBody(),


            /* =========================
             * テキスト系
             * ========================= */

                # ストレージ保存された文章（説明文）
                'description_text'  => $this->getStorageText($this->description),

                # ストレージ保存された文章（セット内容）
                'set_contents_text' => $this->getStorageText($this->set_contents),


            /* =========================
             * 公開状態
             * ========================= */

                # 公開判定
                'published_status' => $publishedStatus,

                # 公開中かどうか
                'is_published'     => $publishedStatus === 1,

                # NEWの表示
                'is_new' => $this->published_start_at
                ? $this->published_start_at->isAfter( now()->copy()->subWeek() )
                : false,

                # 発送予定ラベル
                'estimated_shipping_label' => $this->estimated_shipping_at
                ? $this->estimated_shipping_at->format('n') . '月' . (
                    $this->estimated_shipping_at->day <= 10 ? '上旬' :
                    ($this->estimated_shipping_at->day <= 20 ? '中旬' : '下旬')
                ) . '発送予定'
                : null,



            /* =========================
             * ルート
             * ========================= */

                # [ルーティング]ガチャタイトル詳細 r_show
                'r_show' => route('manuf.gacha_title', [
                    'category_code' => $this->category?->code_name,
                    'title_code'    => $this->code,
                ]),

                # [ルーティング]カテゴリー
                'r_category' => route('manuf.search', [
                    'category_id' => $this->category_id,
                ] ),

        ];
    }
}
