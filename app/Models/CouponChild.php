<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  クーポン (1回限定クーポンコード)　モデル
| =============================================
*/

class CouponChild extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用

    public $timestamps = true;
    protected $fillable = [
        'code'     ,//コード
        'coupon_id',//対象ユーザーのID
        'user_id'  ,//対象ユーザーのID
        'is_done'  ,//利用済みか否か
    ];


    /* 重複しないコードの生成 */
    public static function CreateCode()
    {
        $code = ''; $n =16;
        while ( !$code ) {
            $str = Str::random($n);
            $model = self::where('code', $str )->first();//重複チェック
            $code = !$model ? $str : '';
            $n ++;
        }
        return $code;
    }



    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    |
    |
    */


        /**
         * Couponモデル リレーション
         * @return \App\Models\Coupon
        */
        public function coupon(){
            return $this->belongsTo(Coupon::class)
            ->withTrashed();//削除済みも含む
        }




}
