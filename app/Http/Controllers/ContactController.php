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
     * 一覧
    */
    public function index()
    {
        return view('footer_menu.contact.index');
    }



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




}
