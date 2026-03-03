<?php
namespace App\Models\Gacha;

use App\Models\GachaCategory;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
use App\Models\GachaRankMovie;
use App\Models\PointSail;
use App\Models\UserGachaHistory;
use App\Models\User;
use App\Models\SponsorAd;
/*
| =============================================
|  ガチャ　モデル [ リレーション Trait]
| =============================================
*/
trait Relations
{
    /**
     * GachaCategoryモデル リレーション
     * @return \App\Models\GachaCategory
    */
    public function category(){
        return $this->belongsTo(GachaCategory::class, 'category_id')
        ->withTrashed();
    }



    /**
     * GachaDiscriptionモデル リレーション discriptions
     * @return \App\Models\GachaDiscription
    */
    public function getDiscriptionsAttribute()
    {
        # ガチャランク配列の取得
        $discriptions_ranks = GachaDiscription::gacha_ranks();

        $array = [];
        foreach ($discriptions_ranks as $gacha_rank_id => $discriptions_rank)
        {
            $gacha_discription = GachaDiscription::where('gacha_id',$this->id)
            ->where('gacha_rank_id',$gacha_rank_id)
            ->first();

            if( $gacha_discription ){ $array[] = $gacha_discription; }
        }

        return $array;
    }


    /**
     * GachaPrizeモデル リレーション
     * @return \App\Models\GachaPrize
    */
    public function g_prizes()
    {
        return $this->hasMany(GachaPrize::class,'gacha_id')
        ->has('prize')
        ->orderBy('gacha_rank_id','asc'); //ランク順
    }


    /**
     * GachaRankMovieモデル リレーション
     * @return \App\Models\GachaRankMovie
    */
    public function g_rank_movies()
    {
        return $this->hasMany(GachaRankMovie::class,'gacha_id');
    }


    /**
     * SponsorAdモデル リレーション (sponsorAd)
     * @return \App\Models\SponsorAd
    */
    public function sponsor_ad()
    {
        return $this->hasOne(SponsorAd::class,'gacha_id');
    }


    /**
     * SponsorAdモデル リレーション (sponsorAd)
     * @return \App\Models\SponsorAd
    */
    public function sponsor_ads()
    {
        return $this->hasMany(SponsorAd::class,'gacha_id');
    }


    /**
     * UserGachaHistoryモデル リレーション
     * @return \App\Models\UserGachaHistory
    */
    public function user_gacha_histories()
    {
        return $this->hasMany(UserGachaHistory::class);
    }


    /**
     * PointSailモデル (サブスクプラン)リレーション
     * @return \App\Models\User
    */
    public function subscription(){
        //ガチャ一覧に、サブスク終了時でも表示可能
        return $this->belongsTo(PointSail::class, 'subscription_id')
        ->withTrashed();//削除済みも含む
    }


}
