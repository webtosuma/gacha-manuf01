<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRankHistory;
use App\Models\PointHistory;
use App\Models\TicketHistory;

/*
| =============================================
|  会員ランク履歴 コントローラー
| =============================================
*/
class UserRankHistoryController extends Controller
{
    /**
     * 履歴一覧
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        # 販売用チケット情報取得
        $user_rank_histories = UserRankHistory::where('user_id',$user->id)
        ->orderByDesc('created_at')
        ->get();

        return view('user_rank_history.index',compact('user_rank_histories'));
    }




    /**
     * 会員ランクの総合的な更新処理
     *
     * @param  User $user
     * @return Void
     */
    public static function CreateUserRankHistory( $user )
    {
        # 会員ランク履歴が一つもないとき(新規ユーザー)
        if( !$user->desc_first_rank )
        {
            self::CreateBignnerRankHistory($user);
        }


        # 月初(1日)の会員ランクが未更新のとき(月初・未更新など)
        if( !$user->now_rank )
        {
            # 月初の日付オブジェクトの生成(直近の会員ランク履歴の翌月から)
            $format = $user->desc_first_rank->created_at->copy()->addMonth()->format('Y-m-01');
            $month = Carbon::parse($format);


            // dd($month->format('Y-m-d H:i:s'));
            ## ランク履歴の無い月を繰り返し
            while ( $month->format('Ym') != now()->copy()->addMonth()->format('Ym'))
            {
                ## 直近の会員ランク履歴
                $desc_first_rank = $user->desc_first_rank;
                // dd($desc_first_rank->created_at->format('Y-m-d'));

                ##a. 昇格
                self::CreateRankUpHistory( $user, $month, $desc_first_rank );

                ##b. 維持
                self::CreateNowrankHistory( $user, $month, $desc_first_rank );

                ##c. 降格
                self::CreateRankRankDownHistory( $user, $month, $desc_first_rank );


                ## ボーナス付与
                $desc_first_rank = $user->desc_first_rank;//更新された直近の会員ランク履歴
                self::CreateBonusHistory( $user, $month, $desc_first_rank );


                $month->addMonth();//翌月に更新
            }
        }



        # ガチャの後にランクアップしたとき(ガチャのあと)
        if( self::CreateRankUpHistory( $user, now(), $user->now_rank ))
        {

        }
    }




    /**
     * 会員ランクが一つもない=>会員登録の日付でビギナーランクを登録
     *
     * @param  User $user
     * @return Void
     */
    public static function CreateBignnerRankHistory($user)
    {
        $user_rank_history = new UserRankHistory([
            'user_id'    => $user->id,
            'rank_id'    => 0,
        ]);
        $user_rank_history->created_at = $user->created_at;
        $user_rank_history->updated_at = $user->created_at;
        $user_rank_history->save();
    }




    /**
     * a.昇格=>昇格のpt消費数をクリアしているとき・マスターランクではないとき
     *
     * @param  User   $user
     * @param  Carbon $date
     * @param  UserRankHistory $desc_first_rank //直近の会員ランク
     * @return Bool
     */
    public static function CreateRankUpHistory( $user, $date, $desc_first_rank )
    {

        # 変数の定義
        $max_rank_id   = UserRankHistory::MaxRankId(); //会員ランクの最高ランクID
        $next_rank     = $desc_first_rank->next_rank;//次の昇格後の会員ランク情報
        $month_pt_count = self::GetMonthPtCount($user, $desc_first_rank->created_at); //指定年月のpt消費数
        // $month_pt_count = self::GetMonthPtCount($user, $date); //指定年月のpt消費数

        if(
            $next_rank->rankup_pt_count <= $month_pt_count //*昇格のpt消費数をクリアしているとき
            && $desc_first_rank->rank_id != $max_rank_id //*マスターランクでは無いとき
        ){
            # ランクアップする会員ランク情報($up_rank)
            $user_ranks = UserRankHistory::UserRanks();
            $up_rank_id = $next_rank->rank_id;
            foreach ($user_ranks as $rank_id => $user_rank) {//月のpt消費数をクリアしている会員ランクIDを検索
                $up_rank_id =  $user_rank['rankup_ptcount']<=$month_pt_count ? $rank_id : $up_rank_id ;
            }
            $up_rank = new UserRankHistory(['rank_id'=>$up_rank_id]);


            # 会員ランク履歴の登録
            $user_rank_history = new UserRankHistory([
                'user_id'  => $user->id,
                'rank_id'  => $up_rank_id,
                'pt_count' => $month_pt_count,//ポイント消費数
            ]);
            $user_rank_history->created_at = $date;
            $user_rank_history->updated_at = $date;
            $user_rank_history->save();

            return true;
        }
        return false;
    }



        /**
         * 指定年月のpt消費数
         *
         * @param  User   $user
         * @param  Carbon $month
         * @return Integer
         */
        public static function GetMonthPtCount($user, $month)
        {

            $query = PointHistory::query();

                $query->where('user_id',$user->id)
                ->whereYear( 'created_at',$month)
                ->whereMonth('created_at',$month)
                ->where('reason_id',21);//入出理由:ガチャPLAY

            return abs( $query->sum('value') );
        }




    /**
     * b.維持=>現ランクのpt消費数の50%をクリアしているとき・マスターランクのとき
     *
     * @param  User   $user
     * @param  Carbon $date
     * @param  UserRankHistory $user_rank_history
     * @return Bool
     */
    public static function CreateNowrankHistory( $user, $date, $desc_first_rank )
    {
        # 変数の定義
        $max_rank_id   = UserRankHistory::MaxRankId(); //会員ランクの最高ランクID
        $next_rank     = $desc_first_rank->next_rank;//次の昇格後の会員ランク情報
        $month_pt_count = self::GetMonthPtCount($user, $desc_first_rank->created_at); //指定年月のpt消費数
        // $month_pt_count = self::GetMonthPtCount($user, $date); //指定年月のpt消費数

        if( (
            $next_rank->rankup_pt_count <= $month_pt_count //*昇格のpt消費数をクリアしているとき
            && $desc_first_rank->rank_id == $max_rank_id //*マスターランクのとき
        ) or (
            $next_rank->rankup_pt_count > $month_pt_count //*昇格のpt消費数をクリアしていない
            && $desc_first_rank->rankup_pt_count * 0.50 <= $month_pt_count //*現ランクのpt消費数の50%をクリアしているとき
        ) or(
            $desc_first_rank->rankup_pt_count * 0.50 > $month_pt_count //*現ランクのpt消費数の50%をクリアしていない
            && $desc_first_rank->rank_id == 0 //*ビギナーランクのとき
        ) ){

            $user_rank_history = new UserRankHistory([
                'user_id'  => $user->id,
                'rank_id'  => $desc_first_rank->rank_id,
                'pt_count' => $month_pt_count,//ポイント消費数
            ]);
            $user_rank_history->created_at = $date;
            $user_rank_history->updated_at = $date;
            $user_rank_history->save();

            return true;
        }
        return false;

    }



    /**
     * c.降格=>現ランクのpt消費数の50%をクリアしていない・ビギナーランクではない
     *
     * @param  User   $user
     * @param  Carbon $date
     * @param  UserRankHistory $user_rank_history
     * @return Bool
     */
    public static function CreateRankRankDownHistory( $user, $date, $desc_first_rank )
    {
        # 変数の定義
        $max_rank_id = UserRankHistory::MaxRankId(); //会員ランクの最高ランクID
        $down_rank   = $desc_first_rank-> down_rank;//次の昇格後の会員ランク情報
        $month_pt_count = self::GetMonthPtCount($user, $desc_first_rank->created_at); //指定年月のpt消費数
        // $month_pt_count = self::GetMonthPtCount($user, $date); //指定年月のpt消費数

        if(
            $desc_first_rank->rankup_pt_count * 0.50 > $month_pt_count //*現ランクのpt消費数の50%をクリアしていない
            && $desc_first_rank->rank_id != 0 //*ビギナーランクではないとき
        ){

            $user_rank_history = new UserRankHistory([
                'user_id'  => $user->id,
                'rank_id'  => $down_rank->rank_id,
                'pt_count' => $month_pt_count,//ポイント消費数
            ]);
            $user_rank_history->created_at = $date;
            $user_rank_history->updated_at = $date;
            $user_rank_history->save();

            return true;
        }
        return false;
    }



    /**
     * c.降格=>現ランクのpt消費数の50%をクリアしていない・ビギナーランクではない
     *
     * @param  User   $user
     * @param  Carbon $date
     * @param  UserRankHistory $user_rank_history
     * @return Bool
     */
    public static function CreateBonusHistory( $user, $date, $desc_first_rank )
    {
        # ポイント履歴の登録
        if( $desc_first_rank->point_bonus > 0 )
        {
            $point_history = new PointHistory([
                'user_id'   => $user->id,
                'value'     => $desc_first_rank->point_bonus,
                'reason_id' => 15, //入出理由ID
            ]);
            $point_history->created_at = $date;
            $point_history->updated_at = $date;
            $point_history->save();
        }


        # チケット履歴の登録
        if( $desc_first_rank->ticket_bonus > 0 )
        {
            $ticket_history = new TicketHistory([
                'user_id'   => $user->id,
                'value'     => $desc_first_rank->ticket_bonus,
                'reason_id' => 15, //入出理由ID
            ]);
            $ticket_history->created_at = $date;
            $ticket_history->updated_at = $date;
            $ticket_history->save();
        }
    }
}
