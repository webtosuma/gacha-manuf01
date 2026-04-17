<?php

namespace App\Services\Admin;

use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\GachaDiscription;
use App\Services\StorageService;
use Illuminate\Support\Facades\DB;
/*
| =============================================
|  Admin : ガチャ サービス
| =============================================
*/
class GachaService
{
    protected $storage;

    public function __construct(
        StorageService $storage,
    ){
        $this->storage = $storage;
    }


    /**
     * 新規登録
     *
     * @param \Illuminate\Http\Request $request
     * @return Gacha
     */
    public function store($request): Gacha
    {
        return DB::transaction(function () use ($request) {

            # 入力データの加工
            $inputs = $this->processingInputs($request, null);

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


            return $gacha;

        });
    }


    /**
     * 削除処理
     *
     * @param Request $request
     * @param Gacha $gacha
     * @return Void
     */
    public function delete( $request, $gacha ): void
    {
        DB::transaction(function () use ($gacha) {

            # 削除用のデータ整理
            $gacha->is_meter = 0;//メーター非表示
            $gacha->is_slide = 0;//スライド非表示
            $gacha->published_at=null;//非公開
            $gacha->save();


            # DBデータの論理削除
            $gacha->delete();

            # リレーション関係にないガチャ商品の削除
            $delete_gacha_prizes = GachaPrize::doesntHave('gacha')->get();
            foreach ($delete_gacha_prizes as $gacha_prize) { $gacha_prize->delete(); }

        });
    }


    

    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gacha $gacha //新規登録のとき===null
     * @return Array
     */
    public function processingInputs($request, $gacha=null ): array
    {
        $inputs = [
            'category_id'     => $request['category_id']    ,//リレーション
            'name'            => $request['name']           ,//名前
            'type'            => $request['type']           ,//ガチャの種類
            'one_play_point'  => $request['one_play_point'] ,//1回PLAYポイント数
            'image'           => $request['image']          ,//イメージ画像
            'is_meter'        => $request['is_meter']       ,//残数メーターの表示有無
            'is_slide'        => $request['is_slide']       ,//スライドの表示有無
            'user_rank_id'    => $request['user_rank_id']   ,//会員ランクの指定
            'min_time'        => $request['min_time']       ,// 表示時間下限　2024/04/17追加
            'max_time'        => $request['max_time']       ,// 表示時間上限　2024/04/17追加
            'subscription_id' => $request['subscription_id'],//サブスクプランID(PointSail) 2025/03/23追加
            'resume'          => $request['resume']         ,//説明文 　　　　　　          2025/10/09追加
        ];

        # 会員ランク空文字''=>nullに変換
        $inputs['user_rank_id'] = $inputs['user_rank_id']==''? null: $inputs['user_rank_id'];


        # 表示時間の日を跨ぐか否か
        $inputs['is_over_date'] = $inputs['min_time'] > $inputs['max_time'];

        # アクセスキー(新規作成のみ)
        if( $gacha == null ){ $inputs['key'] = \Illuminate\Support\Str::random(16); }

        # デコード
        foreach (['name', 'resume'] as $param) {
            if (isset($inputs[$param])) {
                $inputs[$param] = urldecode($inputs[$param]);
            }
        }

        # ストレージ更新の処理（説明文）resume
            $old_text = $gacha? $gacha->resume: null;  //更新前のファイルパステキスト
            $new_text = $inputs['resume'];             //新しい入力テキスト
            $dir = 'upload/gacha/resume/';      //保存先ディレクトリ
            $inputs['resume'] = $this->storage->uploadText($dir, $new_text, $old_text);


        # ストレージ画像ファイルの更新（イメージ画像）
            $param = 'image';
            $dir = 'upload/gacha/'.$param;                   //保存先ディレクトリ
            $request_file    = $request->file($param);       //画像のリクエスト
            $old_image_path  = $gacha? $gacha->image: null;  //更新前の画像パス
            // $image_dalete    = $request[$param.'_dalete'];      //画像を削除するか否か
            $image_dalete    = null;                         //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;    //コピー用画像パス

            $inputs[$param] = $this->storage->uploadImage(
                $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth
            );

        # 限定回数の登録(ガチャの種類が'n_time','n_oneday'のとき)
            $inputs['type_n_count'] = $request->type_n_count ?? 1;



        # イベントガチャ
        if( $inputs['type'] == 'event' )
        {
            ## イベントガチャの内容に更新
            $update = [
                'one_play_point' => 0,      //1回PLAYポイント数
                'is_meter'       => 0,      //残数メーターの表示有無
                'is_slide'       => 0,      //スライドの表示有無
                'user_rank_id'   => null,   //会員ランクの指定
                'subscription_id'=> false,  //サブスクプランID(PointSail) 2025/03/23追加
            ];

            $inputs = array_replace($inputs, $update);
        }

        return $inputs;

    }


}
