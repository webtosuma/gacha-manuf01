<?php

namespace App\Services\Gacha;
use Illuminate\Support\Facades\DB;
use App\Models\Gacha;
use App\Models\GachaPrize;
use App\Models\UserGachaHistory;
/*
| =============================================
|  ガチャ PLAY：抽選ロジック サービス
| =============================================
*/
class PlayDrawService
{
    /** サービスの登録 */
    public function __construct(
        # 特殊なガチャランク 抽選ロジック
        protected PlayDrawWinService $winService,
        # 特殊なガチャランク
        protected SpecialRankService $specialRankService,
    ){}



    /**
     * 　抽選実行(index)
     *
     * @param  UserGachaHistory $history
     * @return Array $randReminingGPIdArray　//当選したガチャ商品IDの配列
    */
    public function index( UserGachaHistory $history): array
    {        
        return DB::transaction(function () use ($history)
        {
            # ガチャ情報
            $gacha = $history->gacha;

            # 抽選回数
            $play_count = $history->play_count;

            # ガチャの通算利用回数
            $done_num = $gacha->played_count - $play_count;

            
            # ガチャ商品情報（特殊な当選は除く）
            $g_prizes = GachaPrize::where('gacha_id', $gacha->id)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');


            # 残数記録用のガチャ商品( 通常の当選:配列 )
            $g_prizes_nomal = $g_prizes->whereNotIn('gacha_rank_id',/* 特殊なガチャランクを除く */
                array_values( $this->specialRankService->getList() )//IDのみを配列取得
            )->map(function ($p) {
                return [
                    'id' => $p->id,
                    'remaining_count' => $p->remaining_count,
                ];
            })->toArray();


            # 抽選結果
            $result = []; //当選したガチャ商品ID配列 $gacha_prize->id
            for ($i=0; $i < $play_count; $i++) 
            { 
                # 今回でのガチャの利用回数
                $num = $done_num + ($i+1);

                # 当選の種類を判定
                $rank_key = $this->detectWiType($gacha,$num);
                
                # 当選商品の決定
                list($new_array,$g_prize_id)
                = $this->winService->index(
                    $history,
                    $rank_key,
                    $g_prizes_nomal,
                );
                $g_prizes_nomal = $new_array;
                $result[]       = $g_prize_id;
            }

            
            # ユーザー商品の登録・ガチャ商品の残数更新
            foreach ($result as $g_prize_id) {
                $g_prize = $g_prizes->firstWhere('id', $g_prize_id);

                $this->winService->createUserPrize($history, $g_prize);
            }


            # 加工用配列の変更をDBに保存
            foreach ($g_prizes_nomal as $item) 
            {
                $g_prize = $g_prizes->firstWhere('id', $item['id']);

                $g_prize->update([
                    'remaining_count' => $item['remaining_count']
                ]);                
            }


            # 売り切れ処理
            $gacha->refresh();
            if ($gacha->remaining_count < 1) {
                $gacha->update([
                    'sold_out_at' => now(),
                    'is_sold_out' => 1,
                ]);
            }
            

            return $result;

        });/*end DB transaction*/
    }



    /**
     * 当選の種類を判定
     * @param Gacha   $gacha
     * @param Integer $num //利用中のガチャの利用回数
     * @return String //
    */
    public function detectWiType( $gacha, $num ) :?string
    {
        # 特殊なランク一覧(優先順位順)
        $ranks = $this->specialRankService->getList();

        # 特殊なランク当選の判定
        $bool = false; $rank_key = null;
        foreach ( $ranks as $rank_key => $rank_id) 
        {
            switch( $rank_key )
            {
                # ラストワン
                case 'lastone':
                    $bool = $num == $this->specialRankService->hitNumLastone($gacha);
                    break;

                # キリ番
                case 'kiri':
                case 'user_kiri'://個人
                case 'secret_kiri'://シークレット

                    $bool = in_array( $num, 
                        $this->specialRankService->hitNumsKiri($gacha, $rank_key) 
                    ); break;

                # ゾロ目
                case 'zoro':
                case 'user_zoro'://個人

                    $bool = in_array( $num, 
                        $this->specialRankService->hitNumsZoro($gacha, $rank_key) 
                    ); break;

                # ピタリ賞
                case 'pita':
                case 'ss_pita':
                case 's_pita':
                case 'a_pita':
                case 'user_pita'://個人
                case 'secret_pita'://シークレット

                    $bool = in_array( $num, 
                        $this->specialRankService->hitNumsPita($gacha, $rank_key) 
                    ); break;
            }

            if( $bool ){ break; }
        }   


        return $bool 
         ? $rank_key //特殊な当選ランク　
         : null  //通常の当選
        ;
    }



    
}