<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GachaRankMovie;
use App\Models\Movie;
use App\Models\Gacha;
/*
| =============================================
|  ガチャ・ランク別　演出動画　シーダー
| =============================================
*/
class GachaRankMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gachas = Gacha::all();
        foreach ($gachas as $gacha) {
            self::create($gacha);
        }
    }


    /**
     * １つのガチャのカード登録
     *
     * @return void
     */
    public function create( $gacha )
    {
        $data_array = [
            ['rank'=>'ss', 'gacha_rank_id'=>'100', 'movie_ids'=>[1,2,3,],],
            ['rank'=>'s',  'gacha_rank_id'=>'200', 'movie_ids'=>[1,2,3,],],
            ['rank'=>'a',  'gacha_rank_id'=>'300', 'movie_ids'=>[1,2,3,],],

            ['rank'=>'b',  'gacha_rank_id'=>'400', 'movie_ids'=>[4,5,6,],],
            ['rank'=>'c',  'gacha_rank_id'=>'500', 'movie_ids'=>[7,8,9,],],
            ['rank'=>'d',  'gacha_rank_id'=>'600', 'movie_ids'=>[10,11,12,],],

            ['rank'=>'キリ番',    'gacha_rank_id'=>'310', 'movie_ids'=>[1,2,3,],],
            ['rank'=>'ゾロ目',    'gacha_rank_id'=>'320', 'movie_ids'=>[1,2,3,],],
            ['rank'=>'ラストワン', 'gacha_rank_id'=>'10', 'movie_ids'=>[1,2,3,],],
        ];
        foreach ($data_array as $data) {

            $gacha_rank_id = $data['gacha_rank_id'];

            $movie_ids = $data['movie_ids'];
            $movies = Movie::find($movie_ids);


            foreach ($movies as $movie) {
                $gacha_rank_movie = new GachaRankMovie([
                    'gacha_id'     =>$gacha->id,//ガチャリレーション
                    'movie_id'     =>$movie->id,//演出動画リレーション
                    'gacha_rank_id'=>$gacha_rank_id,//ランクID
                ]);
                $gacha_rank_movie->save();
            }
        }



    }
}
