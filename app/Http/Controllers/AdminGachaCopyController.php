<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\GachaRankMovie;
/*
| =============================================
|  サイト管理者 ガチャ[コピー]  コントローラー
| =============================================
*/
class AdminGachaCopyController extends Controller
{
    /**
     * コピー
     *
     * @param  \App\Models\Gacha  $gacha
     * @return \Illuminate\Http\Response
     */
    public function index(Gacha $gacha)
    {

        // $discriptions  = $gacha->discriptions; //ガチャ詳細
        // $g_prizes      = $gacha->g_prizes;     //ガチャ商品
        // $g_rank_movies = $gacha->g_rank_movies;//ガチャ演出動画


        # 基本情報のコピー
        $copy_gacha = self::CopyGacha( $gacha );


        # ランク別詳細情報のコピー
        self::CopyDiscriptions( $gacha, $copy_gacha );


        # 登録商品のコピー
        self::CopyGPrizes( $gacha, $copy_gacha );


        # 登録演出動画のコピー
        self::CopyGRankMovies( $gacha, $copy_gacha );

        # 操作ログの更新
        AdminLogController::createLog( 'gacha.copy', $copy_gacha->id );

        # リダイレクト
        return redirect()->route('admin.gacha',$gacha->category->code_name)
        ->with(['alert-success'=>'「'.$gacha->name.'」ガチャのコピーを作成しました']);
    }



    /**
     * 基本情報のコピー
     * @return App\Models\Gacha $copy_gacha
     */
    public function CopyGacha( $gacha )
    {
        # 画像ファイルの複製
        $dir = 'upload/gacha/image/';
        $path = $gacha->image;
        $new_image_path = Method::copyStorageFile( $dir, $path );

        # ストレージの複製（説明文）resume
        $dir = 'upload/gacha/resume/';
        $path = $gacha->resume_text;
        $new_resume_path = Method::copyStorageFile( $dir, $path );

        # コピー情報の新規登録
        $copy_gacha = new Gacha([
            'category_id'    => $gacha['category_id'] ,   //リレーション
            'name'           => 'コピー'.$gacha['name'] ,  //名前
            'type'           => $gacha['type'] ,          //ガチャの種類
            'one_play_point' => $gacha['one_play_point'] ,//1回PLAYポイント数
            'image'          => $new_image_path,          //イメージ画像
            'resume'         => $new_resume_path,         //説明文
            'is_meter'       => $gacha['is_meter'],       //残数メーターの表示有無
            'is_slide'       => $gacha['is_slide'],       //スライドの表示有無
            'user_rank_id'   => $gacha['user_rank_id'],   //会員ランクの指定
            'min_time'       => $gacha['min_time'],       // 表示時間下限　2024/04/17追加
            'max_time'       => $gacha['max_time'],       // 表示時間上限　2024/04/17追加
            'is_over_date'   => $gacha['is_over_date'],   //日付を跨ぐか否か（min_time<=max_time:0）2024/04/17追加
            'subscription_id'=> $gacha['subscription_id'],  //サブスクプランID(PointSail) 2025/03/23追加
            'type_n_count'   => $gacha['type_n_count'],     // 限定回数(n回限定ガチャ用)   2026/01/27追加

            'published_at'   => NULL,                        //公開設定(非公開)
            'key'            => \Illuminate\Support\Str::random(16), //認証キー
            'sold_out_at'    => NULL,//売り切れ日時
            'is_sold_out'    => 0,//売り切れか否か
        ]);
        $copy_gacha->save();

        return $copy_gacha;
    }



    /**
     * ランク別詳細情報のコピー
     * @return Void
     */
    public function CopyDiscriptions( $gacha, $copy_gacha )
    {
        $discriptions  = $gacha->discriptions; //ガチャ詳細
        foreach ($discriptions as $discription)
        {


            # 画像ファイルの複製
            $dir = 'upload/discription/image/';
            $path = $discription->image;
            $new_image_path = Method::copyStorageFile( $dir, $path );

            # コピー情報の新規登録
            $copy_discription = new GachaDiscription([
                'gacha_id'      => $copy_gacha->id,//ガチャリレーション
                'image'         => $new_image_path,//画像
                'sorce'         => $discription['sorce'],//説明文
                'gacha_rank_id' => $discription['gacha_rank_id'],//ランクID
            ]);
            $copy_discription->save();


        }
    }



    /**
     * 登録商品のコピー
     * @return Void
     */
    public function CopyGPrizes( $gacha, $copy_gacha )
    {
        $g_prizes = $gacha->g_prizes;     //ガチャ商品
        foreach ($g_prizes as $g_prize)
        {
            $copy_g_prize = new GachaPrize([
                'gacha_id'       => $copy_gacha->id,           //ガチャリレーション
                'prize_id'       => $g_prize['prize_id'],      //商品リレーション
                'gacha_rank_id'  => $g_prize['gacha_rank_id'], //ランクID
                'max_count'      => $g_prize['max_count'],     //景品総数
                'remaining_count'=> $g_prize['max_count'],     //景品残数(初期値にリセット)
                'special_count'  => $g_prize['special_count'], //特別なランク専用数 2024/12/23追加　//default(NULL)
            ]);
            $copy_g_prize->save();
        }
    }



    /**
     * 登録演出動画のコピー
     * @return Void
     */
    public function CopyGRankMovies( $gacha, $copy_gacha )
    {
        $gr_movies = $gacha->g_rank_movies;//ガチャ演出動画
        foreach ($gr_movies as $gr_movie)
        {
            $copy_gr_movie = new GachaRankMovie([
                'gacha_id'      => $copy_gacha->id,           //ガチャリレーション
                'movie_id'      => $gr_movie['movie_id'],     //演出動画リレーション
                'gacha_rank_id' => $gr_movie['gacha_rank_id'],//ランクID
            ]);
            $copy_gr_movie->save();
        }
    }

}
