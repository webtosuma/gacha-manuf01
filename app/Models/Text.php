<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
/*
| =============================================
|  サイト内テキスト保存 モデル
| =============================================
*/
class Text extends Model
{
    use HasFactory;
    use SoftDeletes; //論理削除の利用
    public $timestamps = true;


    protected $fillable = [
        'type',          //種類
        'body',          //本文
        'enactmented_at',//制定日・改訂日
    ];

    /** Carbonオブジェクトとして利用 */
    protected $casts = [
        'enactmented_a'  => 'date'
    ];

    /**
     * アクセサーをJSONに含める
     */
    protected $appends = [
        'body_text',   //ストレージ保存された文章（本文）
    ];


    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 画像ファイルパス image_path
         * @return String
        */
        public function getImagePathAttribute()
        {
            $text = $this->body;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return $path && Storage::exists($path) ?
            asset( 'storage/'.$path ) :  null;
        }



        /**
         * ストレージ保存された文章（本文） body_text
         * @return String
         */
        public function getBodyTextAttribute()
        {
            # デフォルトデータ (商品購入に関する注意文)
            if( !$this->body && $this->type=='note' ){
                return self::getNote();
            }


            // パスから改行を取り除く
            $text = $this->body;
            $path = str_replace(["\r\n", "\r", "\n"], '', $text);

            return Storage::exists($path) ? Storage::get($path) : $text;
        }


        /**
         * 制定日・改訂日フォーマット enactmented_at_format
         * @return String
         */
        public function getEnactmentedAtFormatAttribute()
        {
            return Carbon::parse($this->enactmented_at)->format('Y年m月d日');
        }


    /*
    |--------------------------------------------------------------------------
    | メソッド(ユーザーページ利用)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 利用ガイド　getGuide()
         * @return String
         */
        public static function getGuide()
        {
            $key = 'guide';
            return Text::where('type',$key)->pluck('body')->first() ?? null;
        }


        /**
         * 古物営業許可　getSbgLicense()
         * @return String
         */
        public static function getSbgLicense()
        {
            # データ取得
            $data = [];
            $keys = ['license_number','license_commission','license_name',];
            foreach ($keys as $key ) {
                $data[$key] = Text::where('type',$key)->pluck('body')->first() ?? null;
            }

            # 取得データの存在チェック
            $exists = true;
            foreach ($keys as $key) { if (!$data[$key]) { $exists = false; break; } }

            # 定型分
            $body = <<<__EOT__
            古物商営業許可
            第{$data['license_number']}号
            {$data['license_commission']}
            {$data['license_name']}
            __EOT__;


            return $exists ? $body : null ;
        }



        /**
         * メタ情報　getMeta()
         * @return String
         */
        public static function getMeta()
        {
            # デフォルト値
            $defaults = [
                'meta_title'       => config('app.name')."|オンラインガチャを24時間365日楽しめる！ ",
                'meta_description' =>  "オンラインガチャなら".config('app.name')."! 高確率、爆アドガチャを多数ご用意。24時間365日楽しめます。 ",
                'meta_keyword'     => "オンラインガチャ,ガチャ, ",
                'meta_image'       => asset('storage/site/image/logo.png'),
            ];

            # データ取得
            $data = [];
            foreach ($defaults as $key => $default ) {
                $replace_key = str_replace('meta_', '', $key);
                $data[$replace_key] = Text::where('type',$key)->pluck('body')->first() ?? null;
                $data[$replace_key] = $data[$replace_key] ?? $defaults[$key];
            }


            return $data ;
        }




        /**
         * 商品購入に関する注意文　getNote()
         * @return String
         */
        public static function getNote()
        {
            # デフォルト値
            $r_contact = route('contact');
            $default = <<<__EOT__
            商品について
            ・商品の画像はイメージです。商品の状態を表すものではありません。
            ・商品には一部、傷ありが出ることがあります。
            ・お客様のご都合による、商品の交換・返金はできません。

            発送について
            ・商品の発送までには、3〜12日ほどかかります。
            ・発送時期に関する個別のお問い合わせにはお答えできません。
            ・ご入力いただいた住所の変更はできません。お間違えの無い様にご入力ください。
            ・商品をお受け取りいただけなかった場合は、お客様ご自身で運送会社へお問い合わせください。
            ・再配送の際は、配送料をお客様にご負担いただきますのでご了承ください。

            その他
            ・アクセスが集中した場合、一時的にアクセスを制限させていただく場合がございます。しばらくお時間をおいてからアクセスしてください。

            お問い合わせについて
            ・お問い合わせはこちら
            {$r_contact}
            __EOT__;


            $key = 'note';
            $text = Text::where('type',$key)->first();
            return $text ? $text->body_text : $default;
        }



        /**
         * メール署名　getEmailSignature()
         * @return String
         */
        public static function getEmailSignature()
        {
            $company_name = config('app.company_name');

            $default = <<<__EOT__
            発行・配信元
            {$company_name}
            __EOT__;

            $key = 'email_signature';
            $text = Text::where('type',$key)->first();
            return $text ? $text->body_text : $default;
        }


        /**
         * メール署名(頭)　getEmailSignatureHead()
         * @return String
         */
        public static function getEmailSignatureHead()
        {
            $app_name     =  env('APP_NAME');
            $company_name = config('app.company_name');
            $r_contact    = route('contact');


            return <<<__EOT__
            このメールは≪{$app_name}≫の会員登録お手続きをされた方に自動送信しています。
            このメールに心当たりのない場合や、ご不明な点がある場合は、下記お問い合わせ先へご連絡ください。
            {$r_contact}
            （このメールへの返信はできません）


            __EOT__;
        }



        /**
         * 会員ランク　getUserRank()
         * @return String
         */
        public static function getUserRank()
        {
            # デフォルト値
            $defaults = [
                'user_rank_title'  => '会員ランク制度',
                'user_rank_img01'  => Null,
                'user_rank_body01' => Null,
                'user_rank_img02'  => Null,
                'user_rank_body02' => Null,
            ];

            # データ取得
            $data = [];
            foreach ($defaults as $key => $default )
            {
                $text = Text::where('type',$key)->first() ?? null;

                if (str_contains($key, 'img')){
                    $data[$key] = $text ? $text->image_path : $defaults[$key];
                }else{
                    $data[$key] = $text ? $text->body_text : $defaults[$key];
                }
            }


            return $data ;
        }



    /*
    |--------------------------------------------------------------------------
    | アクセサー(ガチャ設定)
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * 限定ガチャのラベル表示　gacha_settings_type_label_image
         * @return String
         */
        public function getGachaSettingsTypeLabelImageAttribute()
        {
            $type    = 'gacha_settings_type_label_image';//キー
            $default = false;//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();
            return $text ? ($text->body ? true : false) : $default;
        }



        /**
         * 限定ガチャのテキスト表示　gacha_settings_type_label_text
         * @return String
         */
        public function getGachaSettingsTypeLabelTextAttribute()
        {
            $type    = 'gacha_settings_type_label_text';//キー
            $default = true;//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();

            return $text ? ($text->body ? true : false) : $default;
        }



        /**
         * デフォルトの表示サイズ　gacha_settings_size
         * @return String
         */
        public function getGachaSettingsSizeAttribute()
        {
            $type    = 'gacha_settings_size';//キー
            $default = 'lg';//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();
            return  $text ? $text->body : $default;
        }



        /**
         * ガチャ販売機の画像を利用する　gacha_settings_card_image
         * @return String
         */
        public function getGachaSettingsCardImageAttribute()
        {
            $type    = 'gacha_settings_card_image';//キー
            $default = false;//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();
            return $text ? ($text->body ? true : false) : $default;
        }



        /**
         * ガチャ販売機の頭部画像　gacha_settings_card_image_head
         * @return String
         */
        public function getGachaSettingsCardImageHeadAttribute()
        {
            $type    = 'gacha_settings_card_image_head';//キー
            $default = $this->gacha_settings_card_image_head_default;//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();
            return  ( isset($text) && $text->body ) ? asset('storage/'.$text->body) : $default;
        }
        /**
         * ガチャ販売機の頭部画像(デフォルト)　gacha_settings_card_image_head_default
         * @return String
         */
        public function getGachaSettingsCardImageHeadDefaultAttribute()
        {
            return  asset('storage/site/image/gacha_mashīn/head.png');
        }



        /**
         * ガチャ販売機の本体画像　gacha_settings_card_image_body
         * @return String
         */
        public function getGachaSettingsCardImageBodyAttribute()
        {
            $type    = 'gacha_settings_card_image_body';//キー
            $default = $this->gacha_settings_card_image_body_default;//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();
            return  ( isset($text) && $text->body ) ? asset('storage/'.$text->body) : $default;
        }
        /**
         * ガチャ販売機の本体画像(デフォルト)　gacha_settings_card_image_body_default
         * @return String
         */
        public function getGachaSettingsCardImageBodyDefaultAttribute()
        {
            return  asset('storage/site/image/gacha_mashīn/body.png');
        }


        /**
         * ガチャの読み込み中動画の利用　gacha_settings_loading_movie
         * @return String
         */
        public function getGachaSettingsLoadingMovieAttribute()
        {
            $type    = 'gacha_settings_loading_movie';//キー
            $default = false;//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();
            return $text ? ($text->body ? true : false) : $default;
        }


        /**
         * ガチャの読み込み中動画パス　gacha_settings_loading_movie_path
         * @return String
         */
        public function getGachaSettingsLoadingMoviePathAttribute()
        {
            $type    = 'gacha_settings_loading_movie_path';//キー
            $default = $this->gacha_settings_loading_movie_path_default;//デフォルト値

            $text    = Text::where('type',$type)->orderByDesc('id')->first();
            return  ( isset($text) && $text->body ) ? asset('storage/'.$text->body) : $default;
        }
        /**
         * ガチャの読み込み中動画パス(デフォルト)　gacha_settings_loading_movie_path_default
         * @return String
         */
        public function getGachaSettingsLoadingMoviePathDefaultAttribute()
        {
            return  asset('storage/site/movie/gacha_loading.mp4');
        }



    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */
        /**
         * フッターメニュースコープ ->forType($type, $revision_date)->first();
         *
         * @param String $type //文書の種類
         * @param String $revision_date
        */
        public function scopeForType( $query, $type, $revision_date=null )
        {
            # 制定日・改訂日の指定
            if($revision_date){
                $query->where('enactmented_at',$revision_date)->orderByDesc('id');
            }

            # 文章の種類
            $query->where('type',$type);

            # 並び順
            $query->orderByDesc('enactmented_at')->orderByDesc('id');
        }


    /*~*/
}
