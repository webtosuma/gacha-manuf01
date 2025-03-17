<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
/**
 * =========================================
 *  Admin お問い合わせ　コントローラー
 * =========================================
 */
class AdminContactController extends Controller
{
    /**
     * 一覧
    */
    public function index()
    {
        // $type_texts= Method::getStorageObjData( self::storagePath() );
        // $type_texts = array_values($type_texts);//配列に変換
        // $type_texts = is_array($type_texts) ? $type_texts : self::defaultData();//デフォルト値
        // dd($type_texts);

        return view('admin.contact.index');
    }



    /**
     * お問い合わせ一覧情報の発行API(admin_list)
     *
     * @param \Illuminate\Http\Request $request
     * @return JSON
    */
    public function admin_list(Request $request)
    {
        # 一括処理
        self::apiBulkDelete($request);          //一括削除処理
        self::apiBulkUpdateResponsed($request); //一括お問い合わせの種類変更
        self::apiBulkUpdateTypetext($request);  //一括お問い合わせの種類変更
        self::apiBulkDelete($request);          //一括お問い合わせ削除

        self::apiTypeDelete($request);//フォルダの削除


        # データリスト
        $contacts = Contact::paginateDataList($request);

        # 月別データ
        $months  = Contact::getMonthList();

        # フォルダの種類(type_texts)をストレージより取得
        $type_texts= Method::getStorageObjData( self::storagePath() );
        $type_texts = $type_texts ? array_values($type_texts) : null;//配列に変換
        $type_texts = is_array($type_texts) ? $type_texts : self::defaultData();//デフォルト値

        # フォルダの種類(デフォルト値)
        $type_texts_defaults  = ['退会','ゴミ箱'];

        # 未対応カウント($not_res_conts)
        $not_res_conts = [
            '受信箱' => Contact::where('type_text',null)->where('responsed',0)->get()->count(),
        ];
        foreach ([ ...$type_texts, ...$type_texts_defaults ] as $type_text) {
            $not_res_conts[$type_text] =
            Contact::where('type_text',$type_text)->where('responsed',0)->get()->count();
        }


        # JSONを返す(報告一覧データ)
        return response()->json( compact(
            'contacts','months','type_texts','type_texts_defaults','not_res_conts'
        ));
    }


        /* ストレージ保存パス */
        public static function storagePath(){ return 'upload/contact/type_texts.json'; }

        /* フォルダの種類(type_texts)デフォルトデータ */
        public static function  defaultData(){ return [ ]; }

        /** 一括お問い合わせの種類変更 */
        public function apiBulkUpdateResponsed($request)
        {
            if( ! $request->bulk_update_responsed ){ return ; }

            $responsed = $request->bulk_update_responsed_value=='未対応'?0:1;
            $contacts = Contact::whereIn('id',$request->contact_ids)->get();
            foreach ($contacts as $contact) {
                $contact->update(['responsed' => $responsed ]);
            }
        }


        /** 一括お問い合わせの種類変更 */
        public function apiBulkUpdateTypetext($request)
        {
            if( ! $request->bulk_update_typetext ){ return ; }

            $type_text = $request->bulk_update_typetext_value;
            $contacts = Contact::whereIn('id',$request->contact_ids)->get();
            foreach ($contacts as $contact) {
                $contact->update(['type_text' => $type_text ]);
            }
        }


        /** 一括削除処理 */
        public function apiBulkDelete($request)
        {
            if( ! $request->bulk_delete ){ return ; }
            $contacts = Contact::whereIn('id',$request->contact_ids)->get();
            foreach ($contacts as $contact) { $contact->delete(); }
        }


        /* フォルダの削除 */
        public function apiTypeDelete($request)
        {
            if( ! $request->delete_type_text ){ return ; }

            # 指定のフォルダ名
            $delete_type_text = $request->delete_type_text;

            # お問い合わせ情報の削除
            $contacts = Contact::where('type_text',$delete_type_text)->get();
            foreach ($contacts as $contact) { $contact->delete(); }

            # フォルダの種類(type_texts)をストレージより取得
            $type_texts = Method::getStorageObjData( self::storagePath() );
            $type_texts = $type_texts ? array_values($type_texts) : null;//配列に変換
            $type_texts = is_array($type_texts) ? $type_texts : self::defaultData();//デフォルト値

            # 値を除外して、連想配列データ(Object)をストレージ保存
            $result = array_filter($type_texts, function ($item) use ($delete_type_text) {
                return $item !== $delete_type_text;
            });
            $put = Method::putStorageObjData( $this->storagePath(), $result );
        }



    /* end admin_list */


    /**
     * お問い合わせ[対応済変更]API(admin_responsed)
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contact $contact
     * @return JSON
    */
    public function admin_responsed(Request $request, Contact $contact)
    {
        # 報告の更新
        $contact->update(['responsed' => $request->responsed ]);


        # JSONを返す
        return response()->json(['comment' => 'ok']);
    }




    /**
     * お問い合わせ[削除]API(admin_destroy)
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contact $contact
     * @return JSON
    */
    public function admin_destroy(Request $request, Contact $contact)
    {
        # 削除
        $contact->delete();


        # JSONを返す(報告一覧データ)
        return response()->json(['data_list' => Contact::dataList() ]);
    }



    /**
     * お問い合わせ[フォルダの新規作成]API(admin_type_create)
     *
     * @param \Illuminate\Http\Request $request
     * @return JSON
    */
    public function admin_type_create(Request $request)
    {
        # フォルダの種類(type_texts)をストレージより取得
        $type_texts = Method::getStorageObjData( self::storagePath() );
        $type_texts = $type_texts ? array_values($type_texts) : null;//配列に変換
        $type_texts = is_array($type_texts) ? $type_texts : self::defaultData();//デフォルト値

        # 新しいフォルダを追加
        if($request->new_type_text){
            $type_texts[] = $request->new_type_text;
        }

        # 連想配列データ(Object)をストレージ保存
        $put = Method::putStorageObjData( $this->storagePath(), $type_texts );


        # JSONを返す(報告一覧データ)
        return response()->json( compact( 'type_texts' ));
    }



}
