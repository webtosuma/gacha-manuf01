<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
/**
 * =========================================
 *  お問い合わせ　コントローラー
 * =========================================
*/
class ContactController extends Controller
{
    /**
     * バリデーションチェック
     *
     * @param \App\Http\Requests\ContactRequest $request
     * @return JSON
    */
    public function validation(ContactRequest $request)
    {
        return response()->json(['message'=>'OK','data'=>$request->all()]);
    }



    /**
     * お問い合わせ[完了]API(completion_api)
     *
     * @param \Illuminate\Http\Request $request
     * @return JSON
    */
    public function completion(Request $request)
    {
        // return response()->json(['message'=>'OK']);

        # 入力内容
        $inputs = $request->only([
            'name',      //氏名
            'company',   //会社名
            'email',     //メール
            'tell',      //電話番号
            'body',      //本文
            'type_text', //お問い合わせの種類
        ]);



        # text入力値が150文字以上の時、ストレージへファイル保存する
        $dir = 'upload/contact/';      //保存先ディレクトリ
        $new_text = $inputs['body'];  //新しい入力テキスト
        $inputs['body'] = Method::uploadStorageText($dir, $new_text);


        # DB保存
        $contact = new Contact( $inputs );
        $contact->save();
        // 二重送信防止
        $request->session()->regenerateToken();


        # 顧客へメールの自動送信
        SendMailController::ContactCompletion( $request );

        # JSONを返す
        return response()->json(['comment'=>'ok']);

    }




    /**
     * お問い合わせ一覧情報の発行API(admin_list)
     *
     * @param \Illuminate\Http\Request $request
     * @return JSON
    */
    public function admin_list(Request $request)
    {
        # データリスト
        $contacts = Contact::dataList();

        # JSONを返す(報告一覧データ)
        return response()->json( $contacts);
    }




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

}
