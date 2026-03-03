<?php
namespace App\Models\Gacha;

use Illuminate\Support\Facades\Auth;
use App\Models\Text;
use App\Models\GachaPrize;
use App\Models\UserGachaHistory;
use App\Models\UserRankHistory;
use \App\Http\Controllers\GachaPlayCreateUserPrizeMethod as GPCUPMethod;
/*
| =============================================
|  ガチャ　モデル [ 会員ランク Trait]
| =============================================
*/
trait UserRanks
{
    /**
     * 会員ランク user_rank
     * @return String
    */
    public function getUserRankAttribute()
    {
        return new UserRankHistory(['rank_id'=>$this->user_rank_id]);
    }


    /**
     * ユーザーランクの商品登録があるか have_user_rank
     * @return String
    */
    public function getHaveUserRankAttribute()
    {
        $gacha_prizes = GachaPrize::where('gacha_id',$this->id)
        ->whereIn('gacha_rank_id', [
            GPCUPMethod::GachaRankIdUserPita(),//ガチャランクID 個人ピタリ賞
            GPCUPMethod::GachaRankIdUserKiri(),//ガチャランクID キリ番
            GPCUPMethod::GachaRankIdUserZoro(),//ガチャランクID ゾロ目
        ])->get();

        return Auth::check() && $this->is_published ? $gacha_prizes->count() > 0 : false;
    }



    /**
     * 利用できるユーザーランクガチャではない dont_auth_user_rank
     * @return String
    */
    public function getDontAuthUserRankAttribute()
    {
        # ログインアカウント
        $user = Auth::check() ? Auth::user() : null;

        # 会員ランクID
        $user_rank_id = $user && $user->now_rank ? $user->now_rank->rank_id : null;

        # 会員ランク限定ガチャの利用設定
        $play_gacha_settings =  config('u_rank_ticket.u_rank_settings.play_gacha',false);
        switch ($play_gacha_settings)
        {
            case 'only_rank':  //会員ランクガチャのみ利用可能
                if( isset($this->user_rank_id) && $this->user_rank_id!=$user_rank_id){ return true; }
                break;

            case 'under_rank': //会員ランク以下のガチャ全て利用可能
                if( isset($this->user_rank_id) && $this->user_rank_id>$user_rank_id){ return true; }
                break;

        }


        return false;
    }



    /**
     * ユーザーランク限定ガチャラベル user_rank_label
     * @return String
    */
    public function getUserRankLabelAttribute()
    {
        return $this->user_rank && $this->user_rank->label
        ? $this->user_rank->label.'会員' : null;
    }



    /**
     * [画像]会員ランク限定 img_path_user_rank
     * @return String
    */
    public function getImgPathUserRankAttribute()
    {
        $text_model = new Text();

        return $this->user_rank_id!=''
        && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
        ? $this->user_rank->image_path : null;
    }


}
