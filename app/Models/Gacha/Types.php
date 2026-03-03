<?php
namespace App\Models\Gacha;

use Illuminate\Support\Facades\Auth;
use App\Models\Text;
use App\Models\UserGachaHistory;
use App\Models\UserRankHistory;
/*
| =============================================
|  ガチャ　モデル [ ガチャの種類 Trait]
| =============================================
*/
trait types
{
    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * ガチャの種類等のラベルテキスト表示有無 is_type_label_text
         * @return Bool
        */
        public function getIsTypeLabelTextAttribute() : Bool
        {
            $text_model = new Text();
            return $text_model->gacha_settings_type_label_text;
        }


        
        /**
         * ガチャの種類ラベル type_label
         * @return String
        */
        public function getTypeLabelAttribute()
        {
            # n回限定
            switch ($this->type) {
                case 'n_time':
                return $this->type_n_count.'回限定'; break;

                case 'n_time_no_custom':
                return $this->type_n_count.'回限定'; break;

                case 'n_oneday':
                return '1日'.$this->type_n_count.'回限定'; break;

                case 'n_oneday_no_custom':
                return '1日'.$this->type_n_count.'回限定'; break;
            }

            # 通常・カスタムボタンを含まない
            return !in_array( $this->type, ['nomal','no_custom'] )
            ? $this->types()[$this->type] : null;
        }


        /**
         * ガチャの種類ラベル(Admin用) type_label_admin
         * @return String
        */
        public function getTypeLabelAdminAttribute()
        {
            switch ($this->type) {
                case 'n_time':
                return $this->type_n_count.'回限定'; break;

                case 'n_time_no_custom':
                return $this->type_n_count.'回限定(カスタム・100連ボタンなし)'; break;

                case 'n_oneday':
                return '1日'.$this->type_n_count.'回限定'; break;

                case 'n_oneday_no_custom':
                return '1日'.$this->type_n_count.'回限定(カスタム・100連ボタンなし)'; break;
            }

            return $this->types()[$this->type];//ガチャの種類;
        }


        /**
         * 1日1回をプレイしたか？ played_only_oneday
         * @return String
        */
        public function getPlayedOnlyOnedayAttribute()
        {
            $user_id = Auth::check() ? Auth::user()->id : 0;

            // 今日の日付を取得
            $today = \Carbon\Carbon::today();

            $bool = UserGachaHistory::where('user_id',$user_id)
            // ->where('created_at', '>', \Carbon\Carbon::parse($this->updated_prizes_at) )//ガチャ商品更新より後の履歴
            ->where('gacha_id',$this->id)
            ->whereDate('created_at', $today)
            ->first();

            return $bool ? true : false ;
        }


    /*
    |--------------------------------------------------------------------------
    | アクセサー(n回限定・1日n回限定)
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * [n回限定・1日n回限定] ログインユーザーがガチャを回した数 type_n_played_count
         * @return Integer
        */
        public function getTypeNPlayedCountAttribute() : Int
        {
            # n回限定ガチャ
            $bool = !in_array( $this->type,[
                'n_time', 'n_oneday',
                'n_time_no_custom','n_oneday_no_custom',
            ]);
            if($bool){ return 0; }

            $today   = today();
            $user_id = Auth::check() ? Auth::user()->id : 0 ;


            $query = UserGachaHistory::query();

                $query->where('user_id', $user_id);
                $query->where('gacha_id',$this->id);
                ## 1日⚪︎回限定
                if( in_array( $this->type,[ 'n_oneday', 'n_oneday_no_custom', ]) )
                {
                    $query->whereDate('created_at',$today);
                }

            return $query->sum('play_count');
        }




        /**
         * [n回限定・1日n回限定] 残り回数 type_n_remaining_count
         * @return String
        */
        public function getTypeNRemainingCountAttribute() : Int
        {
            # n回限定ガチャ
            $bool = !in_array( $this->type,[
                'n_time', 'n_oneday',
                'n_time_no_custom','n_oneday_no_custom',
            ]);
            if($bool){ return 0; }

            # n回限定の残数
            $type_n_remaining_count = $this->type_n_count - $this->type_n_played_count;


            # n回限定の残数 or ガチャの残数
            return ($type_n_remaining_count < $this->remaining_count) ? $type_n_remaining_count : $this->remaining_count;
        }



        /**
         * [n回限定・1日n回限定] 残り回数ラベル type_n_remaining_count_label
         * @return String
        */
        public function getTypeNRemainingCountLabelAttribute() : ? String
        {
            # n回限定ガチャ
            $bool = !in_array( $this->type,[
                'n_time', 'n_oneday',
                'n_time_no_custom','n_oneday_no_custom',
            ]);
            if($bool){ return null; }

            # 最大値（限定回数）
            $max_count = $this->type_n_count;

            $l_head = in_array( $this->type,[ 'n_oneday', 'n_oneday_no_custom', ])
            ? '本日残り ' : '限定残り ';
            $text = $l_head . (string)$this->type_n_remaining_count . '/' . (string)$max_count ;


            return $text;
        }




    /*
    |--------------------------------------------------------------------------
    | アクセサー(画像パス)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 画像パス　1回or10回限定 img_path_one_chance
         * @return String
        */
        public function getImgPathOneChanceAttribute()
        {
            $text_model = new Text();

            return $this->type=='one_chance'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/one_chance.png' ) : null;
        }

        /**
         * 画像パス　一回限定 img_path_one_time
         * @return String
        */
        public function getImgPathOneTimeAttribute()
        {
            $text_model = new Text();

            return $this->type=='one_time'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/one_time.png' ) : null;
        }

        /**
         * 画像パス　1日一回限定 img_path_only_oneday
         * @return String
        */
        public function getImgPathOnlyOnedayAttribute()
        {
            $text_model = new Text();

            return $this->type=='only_oneday'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/only_oneday.png' ) : null;
        }

        /**
         * 画像パス　新規会委員限定 img_path_only_new_user
         * @return String
        */
        public function getImgPathOnlyNewUserAttribute()
        {
            $text_model = new Text();

            return $this->type=='only_new_user'
            && $text_model->gacha_settings_type_label_image//限定ガチャのラベル表示設定
            ? asset( 'storage/site/image/gacha_type/only_new_user.png' ) : null;
        }



    /**/
}
