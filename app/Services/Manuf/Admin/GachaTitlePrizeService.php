<?php

namespace App\Services\Manuf\Admin;

use App\Models\ManufGachaTitlePrize;
use App\Models\Prize;
use App\Services\StorageService;
use Illuminate\Support\Facades\DB;
/*
| =============================================
|  Manufacturer : ガチャタイトル商品 サービス
| =============================================
*/
class GachaTitlePrizeService
{
    protected $storage;

    public function __construct(StorageService $storage)
    {
        $this->storage = $storage;
    }



    /**
     * 新規登録
     *
     * @param Request $request
     * @param ManufGachaTitle $gacha_title
     * @return ManufGachaTitlePrize
     */
    public function store($request, $gacha_title): ManufGachaTitlePrize
    {
        return DB::transaction(function () use ($request, $gacha_title) {

            # Prize作成
            $prizeInputs = $this->processingInputs($request, null);
            $prizeInputs['category_id']=$gacha_title->category_id;
            $prizeInputs['point_updated_at']=now();
            $prize = Prize::create($prizeInputs);

            # TitlePrize作成
            $title_prize = ManufGachaTitlePrize::create([
                'manuf_gacha_title_id' => $gacha_title->id,
                'prize_id'             => $prize->id,
                'published_at' => $request->is_published ? now() : null,

            ]);

            return $title_prize;
        });
    }



    /**
     * 更新処理
     *
     * @param Request $request
     * @param ManufGachaTitlePrize $title_prize
     * @return ManufGachaTitlePrize
     */
    public function update($request, ManufGachaTitlePrize $title_prize): ManufGachaTitlePrize
    {
        return DB::transaction(function () use ($request, $title_prize) {

            $prize = $title_prize->prize;

            # Prize更新
            $prizeInputs = $this->processingInputs($request, $prize);
            $prize->update($prizeInputs);

            # 公開制御
            $title_prize->update([
                'published_at' => $request->is_published ? now() : null,
            ]);

            return $title_prize;
        });
    }



    /**
     * 削除処理
     *
     * @param Request $request
     * @param ManufGachaTitlePrize $title_prize
     * @return Void
     */
    public function delete( $request, $title_prize ): void
    {
        DB::transaction(function () use ($title_prize) {
            $title_prize->delete();
        });
    }



    /**
     * コピー処理
     * @param  ManufGachaTitle $gacha_title
     * @param ManufGachaTitlePrize $title_prize
     * @return Void
     */
    public function copy(ManufGachaTitlePrize $title_prize, $gacha_title): ManufGachaTitlePrize
    {
        return DB::transaction(function () use ($title_prize, $gacha_title) {

            $prize = $title_prize->prize;

            # 説明文コピー
            $new_discription = null;
            if ($prize->discription){
                $dir  = 'upload/prize/discription/';
                $path = $prize->discription;
                $new_discription = $this->storage->copyFile($dir, $path);
            }

            # 画像コピー
            $new_image = null;
            if ($prize->image) {
                $dir  = 'upload/prize/image/';
                $path = $prize->image;
                $new_image = $this->storage->copyFile($dir, $path);
            }


            # Prizeコピー
            $newPrize = $prize->replicate();
            $newPrize->code = Prize::CreateCode(); // 重複回避
            $newPrize->discription = $new_discription ?? $prize->discription;
            $newPrize->image       = $new_image ?? $prize->image;
            $newPrize->save();


            # TitlePrizeコピー
            $newTitlePrize = ManufGachaTitlePrize::create([
                'manuf_gacha_title_id' => $gacha_title->id,
                'prize_id'             => $newPrize->id,
                'order'                => $title_prize->order,
                'published_at'         => null, // コピーは下書き
            ]);

            return $newTitlePrize;
        });
    }



    /**
     * 入力加工
     */
    public function processingInputs($request, $prize = null): array
    {
        $inputs = $request->only(
            'name',
            'code',
            'rank_id',
            'discription',
        );

        # デコード
        foreach (['name', 'discription'] as $param) {
            if (isset($inputs[$param])) {
                $inputs[$param] = urldecode($inputs[$param]);
            }
        }

        # テキスト保存
        if (isset($inputs['discription'])) {
            $dir = 'upload/prize/discription';
            $inputs['discription'] = $this->storage->uploadText(
                $dir,
                $inputs['discription'],
                $prize?->discription
            );
        }

        # 画像処理
        $dir = 'upload/prize/image';
        $file = $request->file('image');
        $old = $prize?->image;

        $inputs['image'] = $this->storage->uploadImage(
            $dir,
            $file,
            $old,
            null,
            $request->copy_image_path ?? null
        );

        return $inputs;

    }
}
