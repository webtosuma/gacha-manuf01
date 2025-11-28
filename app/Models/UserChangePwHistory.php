<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
/*
| =============================================
|  ユーザー　パスワード変更履歴　モデル
| =============================================
*/
class UserChangePwHistory extends Model
{
    use HasFactory;
    public $timestamps = true;


    protected $fillable = [
        'user_id', //リレーションID
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
            ->withTrashed(); // withTrashed() メソッドを追加
        }


    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * パスワード変更制限スコープ UserChangePwHistory::hasTodayCount($email)
         * @param String $email
         * @return Boolean
        */
        public function scopeHasTodayCount($query,$email)
        {
            # n が false（false, null, 0 も含む）なら無条件で false を返す
            $n = config('app.change_password_limit',false);

            if (!$n) { return false; }

            # ユーザーID
            $user_id = User::where('email', $email)->value('id');

            # 今日のデータ件数
            $count = $query->where('user_id',$user_id)
            ->whereDate('created_at', Carbon::today())->count();

            # 今日の件数が n 件以上かどうか
            return $count >= $n;
        }
}

