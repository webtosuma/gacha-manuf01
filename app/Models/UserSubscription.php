<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
/*
| =============================================
|  ユーザー契約中サブスクプラン　モデル
| =============================================
*/
class UserSubscription extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'user_id',        //ユーザー　リレーション
        'subscription_id',//サブスクプランとのリレーション（PointSail）
    ];


    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * USERモデル リレーション
         * @return \App\Models\User
        */
        public function user(){
            return $this->belongsTo(User::class)
            ->withTrashed();//削除済みも含む
        }


        /**
         * PointSailモデル (サブスクプラン)リレーション
         * @return \App\Models\User
        */
        public function subscription(){
            return $this->belongsTo(PointSail::class, 'subscription_id')
            ->withTrashed();//削除済みも含む
        }




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * サブスク継続期間　elapsed_date
         *
         * @return String
        */
        public function getElapsedDateAttribute()
        {
            $now = Carbon::now();
            $created = $this->created_at;

            $years = $created->diffInYears($now);
            $months = $created->copy()->addYears($years)->diffInMonths($now);
            $days = $created->copy()->addYears($years)->addMonths($months)->diffInDays($now);

            return sprintf('%d年 %dヶ月 %d日', $years, $months, $days);
        }
}
