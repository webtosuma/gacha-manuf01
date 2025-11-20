<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
/*
| =============================================
|  アクセスログ　モデル
| =============================================
*/
class AccessLog extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用
    public $timestamps = true;


    protected $fillable = [
        'user_id'    ,
        'path'       ,//アクセスURL
        'ip'         ,//IPアドレス
        'user_agent' ,//ユーザーエージェント
    ];


    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [
        'user_agent_text',   // ストレージ保存された文章（ユーザーエージェント）
        'created_at_format', //作成日時フォーマット
        'r_admin_user_show', //[ルーテイング]ユーザー詳細 　
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
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ストレージ保存された文章（ユーザーエージェント） user_agent_text　
         * @return String
         */
        public function getUserAgentTextAttribute()
        {
            // パスから改行を取り除く
            $text = $this->user_agent;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }


        /**
         * 作成日時フォーマット created_at_format
         * @return String
        */
        public function getCreatedAtFormatAttribute()
        {
            return $this->created_at->format('Y/m/d H:i:s');
        }


        /**
         * [ルーテイング]ユーザー詳細 r_admin_user_show　
         * @return String
        */
        public function getRAdminUserShowAttribute()
        {
            return $this->user ? route('admin.user.show',$this->user->id) : null ;
        }

    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * Admin用　EC発送受付数 ->forAdmin()
         * @param Request $request
         * @return Integer
        */
        public function scopeForAdmin($query,$request)
        {
            ## 日付の絞り込み
            if($request->date)
            {
                $query->whereDate('created_at', $request->date)->get();
            }

            # ユーザー絞り込み
            if($request->user_id)
            {
                $query->where('user_id', $request->user_id);
            }

            # IP絞り込み
            if($request->ip)
            {
                $query->where('ip', $request->ip);
            }

            # ユーザーアクセス
            if($request->user_agent)
            {
                $query->where('user_agent', $request->user_agent);
            }

            # リレーション
            $query->with('user');//ユーザー

            # 並び順
            $query->orderByDesc('created_at');
        }

    /*~*/
}
