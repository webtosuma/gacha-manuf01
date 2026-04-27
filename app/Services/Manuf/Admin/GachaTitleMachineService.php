<?php

namespace App\Services\Manuf\Admin;

use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitleMachine;
use App\Services\StorageService;
use App\Services\Admin\GachaService;
use Illuminate\Support\Facades\DB;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル 筺体 サービス 
| =============================================
*/
class GachaTitleMachineService
{
    protected $storage;
    protected $gachaService;

    public function __construct(
        StorageService $storage,
        GachaService   $gachaService,
    ){
        $this->storage      = $storage;
        $this->gachaService = $gachaService;
    }



    /**
     * 新規登録
     *
     * @param Request $request
     * @param ManufGachaTitle $gacha_title
     * @return ManufGachaTitleMachine
     */
    public function store($request, $gacha_title): ManufGachaTitleMachine
    {
        return DB::transaction(function () use ( $request, $gacha_title ) {

            # 入力データの加工
            $inputs = $this->processingInputs($request, $gacha_title, null);

            # DBデータの新規登録
            $gacha = Gacha::create($inputs);

            # 詳細情報(discriptions)の登録
            $gacha_ranks = GachaDiscription::gacha_ranks();//ランク情報
            foreach ($gacha_ranks as $gacha_rank_id => $label)
            {
                $gacha_discription = GachaDiscription::create([
                    'gacha_id'      => $gacha->id, //ガチャリレーション
                    'gacha_rank_id' => $gacha_rank_id,//ランクID
                ]);
            }

            # 筐体の登録
            $machine = ManufGachaTitleMachine::create([
                'manuf_gacha_title_id' => $gacha_title->id,
                'gacha_id'             => $gacha->id,
            ]);


            return $machine ;

        });
    }


    /**
     * 更新処理
     *
     * @param Request $request
     * @param ManufGachaTitleMachine $machine
     * @return ManufGachaTitleMachine
     */
    public function update($request, $machine): ManufGachaTitleMachine
    {
        return DB::transaction(function () use ($request, $machine) {

            # 変数定義
            $gacha_title = $machine->gacha_title;
            $gacha       = $machine->gacha;

            # 入力データの加工
            $inputs = $this->processingInputs($request, $gacha_title, $gacha);

            # 更新
            $gacha->update($inputs);


            return $machine;

        });
    }



    /**
     * 削除処理
     *
     * @param Request $request
     * @param ManufGachaTitleMachine $machine
     * @return Void
     */
    public function delete( $request, $machine ): void
    {
        DB::transaction(function () use ( $request, $machine ) {

            # gachaの理論削除
            $gacha = $machine->gacha;
            $this->gachaService->delete( $request, $gacha );

            # 筐体の論理削除
            $machine->delete();

        });
    }



    /**
     * コピー処理
     * @param ManufGachaTitleMachine $machine
     * @param ManufGachaTitle $gacha_title
     * @return Void
     */
    public function copy($machine, $gacha_title): ManufGachaTitleMachine
    {
        return DB::transaction(function () use ($machine, $gacha_title) {

            # gachaデータのコピー
            $gacha = $machine->gacha;
            $copy_gacha = $gacha->replicate();
            $copy_gacha->save();

            # 詳細情報(discriptions)の登録
            $gacha_ranks = GachaDiscription::gacha_ranks();//ランク情報
            foreach ($gacha_ranks as $gacha_rank_id => $label)
            {
                $gacha_discription = GachaDiscription::create([
                    'gacha_id'      => $gacha->id, //ガチャリレーション
                    'gacha_rank_id' => $gacha_rank_id,//ランクID
                ]);
            }

            # 筐体の登録
            $machine = ManufGachaTitleMachine::create([
                'manuf_gacha_title_id' => $gacha_title->id,
                'gacha_id'             => $copy_gacha->id,
            ]);

            return $machine;
        });
    }



    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param Request $request
     * @param ManufGachaTitle $gacha_title
     * @param \App\Models\Gacha $gacha //新規登録のとき===null
     * @return Array
     */
    public function processingInputs($request, $gacha_title, $gacha=null ): array
    {
        // dd($request->all());
        $inputs = $this->gachaService->processingInputs($request, $gacha);

        # Manufガチャ用に加工
        $update = [
            'category_id'     => $gacha_title->category->id,
            'one_play_point'  => 0   ,//1回PLAYポイント数
            'image'           => $gacha_title->image_samune,//イメージ画像
            'is_meter'        => 0   ,//残数メーターの表示有無
            'is_slide'        => 0   ,//スライドの表示有無
            'user_rank_id'    => null,//会員ランクの指定
            'subscription_id' => null,//サブスクプランID(PointSail) 2025/03/23追加
            'published_at'    => $request->is_published ? now() :null,
        ];

        # 上書き
        return array_replace($inputs, $update);
    }




}


