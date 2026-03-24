<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitlePrize;
use App\Models\ManufGachaTitleImage;
use App\Models\ManufGachaTitleMachine;
use App\Models\ManufGachaTitleMovie;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaRankMovie;
use App\Models\GachaPrize;
use App\Models\Prize;
/*
| =============================================
|  Manufacturer用　シーダー
| =============================================
*/
class ManufGachaTitleSeeder extends Seeder
{
    public function run(): void
    {
        # カテゴリー
        $categories = GachaCategory::all();
        foreach ($categories as $category)
        {


            // ManufGachaTitle::factory()->count(10)->create();
            $title_names = [
                'ぽつんといちご大福 マスコットフィギュア',
                'セサミストリート フラットポーチ',
                'ケアベア ぬいぐるみバッジ',
                'クリスタルレオパ マスコットフィギュア',
                'こびとづかん3 スクエアポーチ',
                '銭湯アーミー マスコットフィギュア 銭湯イエローver.',
            ];
            foreach ($title_names as $num => $title_name)
            {
                #1. ガチャタイトル
                $gacha_title = ManufGachaTitle::factory()->create([
                    'category_id'  => $category,
                    'name'         => $title_name,
                    'image_samune' => self::CopyImage( sprintf('sample/manuf/gacha_title/%02d.jpg', $num ), ),
                ]);


                #2. タイトル商品登録
                for ($num=0; $num <5 ; $num++)
                {
                    $title_prize = ManufGachaTitlePrize::factory()->create([
                        'manuf_gacha_title_id' => $gacha_title,
                        'prize_id' => Prize::factory()->create([
                            'image' => self::CopyImage( sprintf('sample/manuf/prize/%02d.jpg', $num ), ),
                        ]),
                        'order'=> $num,
                    ]);
                }


                #3. タイトル紹介画像登録
                for ($num=0; $num <2 ; $num++)
                {
                    $title_image = ManufGachaTitleImage::factory()->create([
                        'manuf_gacha_title_id' => $gacha_title,
                        'path' => self::CopyImage( sprintf('sample/manuf/discription/%02d.png', $num ) ),
                    ]);
                }


                #4. 筐体登録
                for ($num=1; $num <=5 ; $num++)
                {
                    ### ガチャマシーン
                    $machine = ManufGachaTitleMachine::factory()->create([
                        'manuf_gacha_title_id' => $gacha_title,

                        'gacha_id' => Gacha::factory()->create([
                            'category_id'    => $category,
                            'name'           => sprintf('SAMPLEガチャ%02d', $num ),
                            'image'          => self::CopyImage( $gacha_title->image_samune ),
                            'one_play_point' => 0,//1回PLAYポイント数
                        ]),

                    ]);
                    $gacha = $machine->gacha;


                    /* ガチャランク */
                    $gacha_ranks = GachaDiscription::gacha_ranks();//ランク情報
                    foreach ($gacha_ranks as $gacha_rank_id => $label)
                    {
                        ### ガチャ詳細情報
                        $gacha_discription = new GachaDiscription([
                            'gacha_id'      => $gacha->id, //ガチャリレーション
                            'gacha_rank_id' => $gacha_rank_id,//ランクID
                        ]);
                        $gacha_discription->save();

                        ### ガチャ演出動画
                        $gacha_rank_movie = new GachaRankMovie([
                            'gacha_id'     => $gacha->id,
                            'movie_id'     => 1,
                            'gacha_rank_id'=>$gacha_rank_id,//ランクID
                        ]);
                        $gacha_rank_movie->save();


                        ### ガチャ商品登録
                        $title_prizes = $gacha_title->title_prizes;
                        foreach ($title_prizes as $title_prize)
                        {
                            $prize = $title_prize->prize;

                            # 該当ランクでない時は、登録をスキップ
                            if($gacha_rank_id!=$prize->rank_id){ continue; }

                            $gacha_prize = new GachaPrize([
                                'gacha_id'        => $gacha->id,
                                'prize_id'        => $prize->id, //景品リレーション
                                'gacha_rank_id'   => $gacha_rank_id, //ランクID
                                'max_count'       => 100, //景品総数
                                'remaining_count' => 100, //景品残数
                            ]);
                            $gacha_prize->save();
                        }

                    }




                }/*  end for( 筐体登録 )*/



            }/* end foreach( $title_names ) */



        }/* end foreach( $categories ) */



    }



    /* 画像ファイルのコピー */
    public function CopyImage($originalPath): String
    {
        # 拡張子を取得
        $extension = pathinfo($originalPath, PATHINFO_EXTENSION);

        # 新しいファイル名を生成（ユニーク）
        $newFileName = Str::random(20) . '.' . $extension;

        # 保存先パス
        $newPath = 'sample_upload/' . $newFileName;

        # コピー処理
        Storage::copy($originalPath, $newPath);

        # 新しいファイル名を取得
        return $newPath;
    }


}
