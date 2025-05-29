<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
/*
 | ===================================
 |  お問い合わせ　
 | ===================================
 */
class Contact extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [

        'name',      //氏名
        'email',     //メール
        'tell',      //電話番号
        'body',      //本文
        'type_text', //お問い合わせの種類
        'responsed'  //対応済みか否か
    ];



    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * ストレージ保存された文章を含む'回答'($contact->storage_body)
     * @return String
     */
    public function getStorageBodyAttribute()
    {
        $text = $this->body;
        $path = str_replace(["\r\n", "\r", "\n"], '', $text);

        return Storage::exists($path) ? Storage::get($path) : $text;
    }


    /*
    |--------------------------------------------------------------------------
    | スコープ
    |--------------------------------------------------------------------------
    |
    |
    */

        /**
         * 一覧表示用データリスト（paginateDataList)
         * @return Array
        */
        public function scopePaginateDataList( $query, $request)
        {
            # 一覧データの取得

                # キーワード
                self::KeyWordSearch( $query, $request);

                ## 月の絞り込み
                if($request->month)
                {
                    $startDate = \Carbon\Carbon::parse($request->month)->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();

                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }

                ## お問い合わせの種類の絞り込み
                $query->where('type_text',$request->type_text);

                ## 対応状況
                switch ($request->responsed) {

                    case '対応済': $query->where('responsed', 1); break;

                    case '未対応': $query->where('responsed', 0); break;
                }



                $query->orderBy('created_at','desc');

            $contacts = $query->paginate(20);



            # データの加工
            for ($i=0; $i < $contacts->count(); $i++) {

                $contact = $contacts[$i];
                $contact->body_text = $contact->storage_body; //ストレージテキスト

            }

            return $contacts;
        }



        /**
         * 一覧表示用データリスト（getMonthList)
         * @return Array
        */
        public function scopeGetMonthList( $query )
        {
            return Contact::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as format, COUNT(*) as total')
            ->whereNotNull('created_at') // created_at が NULL のデータは除外
            ->where('created_at','<=', now())//公開中のみ
            ->groupBy('format')
            ->orderBy('format', 'desc')
            ->get()
            ->map(function ($item) {
                // 月のフォーマットを「Y年n月」に変換
                $formattedMonth = date('Y年n月', strtotime($item->format . '-01'));
                $date_stanp = date('Y/m/d', strtotime($item->format . '-01'));
                return [
                    'format'     => $formattedMonth.'（'.$item->total.'）',
                    'date_stanp' => $date_stanp,
                    'total'      => $item->total
                ];
            });
        }



        /**
         * キーワード(key_words)から検索するメソッド holiday_summary
         *
         * @param \Illuminate\Http\Request $req
         * @param App\Models\Recruit::query $query
         * @return App\Models\Recruit::query
         */
        public static function KeyWordSearch( $query, $req )
        {
            #検索パラメータが存在するか
            if( !$req->has('keyword') ){ return; }

            #文字列を配列へ変換
            $key_words = self::ArrayConvertString( $req->keyword );

            #検索条件の絞り込み(全ての条件に該当するデータを検索:and)
            foreach ($key_words as $key_word) {

                $query->where(function($q) use ($key_word) {

                    $q->where('email', 'like', '%' . $key_word . '%')
                    ->orWhere('name',  'like', '%' . $key_word . '%')
                    ;

                });

            }
        }

        /**
        * 文字列を配列へ変換
        *
        * @param  String $string
        * @return Array
        */
       public static function ArrayConvertString($string)
       {
           $string = str_replace('　',' ',$string);
           $array  = explode(' ',$string);
           return $array;
       }

}
