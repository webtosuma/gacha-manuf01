<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaRankMovie;
use App\Models\GachaPrize;
use App\Models\Movie;
use App\Models\Prize;
/*
| =============================================
|  ポケモンガチャ　シーダー　
| =============================================
*/
class GachaPokemonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # カテゴリー
        $category = GachaCategory::first();

        #　商品登録
        $count = 8;
        self::createPrize($category,$count);


        # ガチャ登録
        $count = 2;
        self::createGacha($category,$count);


    }



    /** 商品登録 */
    public function createPrize($category,$count)
    {
        for ($num=1; $num<=$count ; $num++)
        {
            $n = (8-$num)/2 > 0 ? floor( (8-$num)/2 ): 0;

            Prize::factory()->create([
                'category_id' => $category->id,
                'name'        => sprintf('テスト商品%02d', $num ),
                'point'       => 100 * (10**$n),
                'image'       => self::CopyImage( sprintf('sample/pokemon/prize/%01d.png', $num ), ),
                'rank_id'     => ceil($num / 2),
            ]);
        }
    }
    


    /** ガチャ登録 */
    public function createGacha($category,$count)
    {
        for ($num=1; $num<=$count ; $num++)
        {

            $gacha = Gacha::factory()->create([
                'category_id' => $category,
                'name'        => sprintf('SAMPLEガチャ%02d', $num ),
            ]);


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
                $movieIds = Movie::pluck('id')->toArray();
                $gacha_rank_movie = new GachaRankMovie([
                    'gacha_id'     => $gacha->id,
                    'movie_id'     => $movieIds[array_rand($movieIds)],
                    'gacha_rank_id'=>$gacha_rank_id,//ランクID
                ]);
                $gacha_rank_movie->save();


                ### ガチャ商品登録
                $prizes = Prize::all(); 
                foreach ($prizes as $prize)
                {
                    # 該当ランクでない時は、登録をスキップ
                    if($gacha_rank_id != $prize->rank->order){ continue; }

                    $max_count = $gacha_rank_id;

                    $gacha_prize = new GachaPrize([
                        'gacha_id'        => $gacha->id,
                        'prize_id'        => $prize->id, //景品リレーション
                        'gacha_rank_id'   => $gacha_rank_id, //ランクID
                        'max_count'       => $max_count, //景品総数
                        'remaining_count' => $max_count, //景品残数
                    ]);
                    $gacha_prize->save();
                }

            }
        

        }
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
