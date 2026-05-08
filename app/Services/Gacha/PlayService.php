<?php

namespace App\Services\Gacha;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\PointHistory;
use App\Models\UserGachaHistory;
use App\Models\GachaRankMovie;
use App\Models\Movie;
use App\Models\User;
use App\Exceptions\GachaException;
/*
| =============================================
|  ガチャ PLAY サービス
| =============================================
*/
class PlayService
{
    /** サービスの登録 */
    public function __construct(
        # 抽選サービス
        private PlayDrawService      $drawService,
        # 特殊なガチャランク
        protected SpecialRankService $specialRankService,
    ) {}


    /**
     * ガチャ実行
     * @param Request $request
     * @param Gacha   $gacha
     * @param String  $key
     */
    public function index($request, $gacha, $key): UserGachaHistory
    {
        return DB::transaction(function () use ($request, $gacha, $key) {

            # ガチャ情報取得(他のリクエストを待機)
            $gacha = Gacha::where('id', $gacha->id)
            ->where('key',$key)
            ->lockForUpdate()
            ->firstOrFail();//データなしの場合、404


            # ユーザー情報
            $user = $request->user();

            # プレイ数
            $play_count = (int)$request->play_count;


            # ポイント消費
            $point_history = $this->consumePoint( $user, $gacha, $play_count );


            # 履歴作成
            $history = $this->createHistory( $user, $gacha, $play_count, $point_history,);


            # 抽選
            $prize_ids = $this->drawService->index($history);


            # 最大ランク
            $max_rank = $this->getMaxRank($prize_ids);


            # 演出動画
            $history = $this->decideMovie($history, $max_rank);



            return $history;

        });
    }



    /**
     * ポイント消費
     * @param User  $user
     * @param Gacha $gacha
     * @param Int   $play_count
    */
    public function consumePoint( $user, $gacha, $play_count ): PointHistory
    {
        $point = (int)$gacha->one_play_point;
        $total_point = $play_count * $point;

        return PointHistory::create([
            'user_id'   => $user->id,
            'value'     => - $total_point,
            'reason_id' => config('const.point_reason.gacha_play', 21),
        ]);
    }



    /**
     * 履歴作成
     * @param User  $user
     * @param Gacha $gacha
     * @param Int   $play_count
     * @param PointHistory $point_history
    */
    public function createHistory( $user, $gacha, $play_count, $point_history,): UserGachaHistory
    {
        return UserGachaHistory::create([
            'user_id'    => $user->id,
            'gacha_id'   => $gacha->id,
            'play_count' => $play_count,
            'point_history_id' => $point_history->id,
        ]);
    }




    /**
     * 最大ランク
     */
    private function getMaxRank(array $ids): string
    {
        # 特殊なガチャランク優先
        $special_ranks = array_values( $this->specialRankService->getList() );

        $special_prize = GachaPrize::whereIn('id', $ids)
        ->whereIn('gacha_rank_id', $special_ranks)
        ->orderBy('gacha_rank_id', 'asc')
        ->first();
        if ($special_prize) { return $special_prize->gacha_rank_id; }


        return GachaPrize::whereIn('id', $ids)
        ->orderBy('gacha_rank_id', 'asc')
        ->value('gacha_rank_id');
    }

    

    /**
     * 動画決定
     * @param UserGachaHistory $history
     * @param String $max_rank
     */
    private function decideMovie($history, $max_rank): UserGachaHistory
    {
        $gacha = $history->gacha;

        # ガチャランクごとの演出動画
        $movieIds = GachaRankMovie::where('gacha_id', $gacha->id)
        ->where('gacha_rank_id', $max_rank)
        ->pluck('movie_id');

        $movies = Movie::whereIn('id', $movieIds)->get();

        # 再生動画
        $movie = $movies->isNotEmpty()//データが1件以上存在するか
        ? $movies->random()
        : Movie::inRandomOrder()->first()//指定の動画が無いとき、ランダムな動画を再生
        ;

        # 履歴に保存
        $history->refresh();
        $history->update([ 'movie_id' => $movie->id ]);
    
        return $history;
    }



}