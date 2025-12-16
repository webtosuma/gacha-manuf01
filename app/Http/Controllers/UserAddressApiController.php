<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserAddressApiRequest;
use App\Models\UserAddress;
/*
| =============================================
|  ユーザーアドレス コントローラー 
| =============================================
*/
class UserAddressApiController extends Controller
{
    /**
     * 一覧取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index(  )
    {
        $user = Auth::user();
        return response()->json($user->addresses);
    }



    /**
     * 新規登録
     *
     * @param  \App\Http\Requests\UserAddressApiRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserAddressApiRequest $request)
    {
        $user = Auth::user();


        # パラメーター
        $inputs = [
            'user_id'     =>$user->id,//リレーションID
            'name'        =>$request->name,       //宛名
            'tell'        =>$request->tell,       //電話番号
            'postal_code' =>$request->postal_code,//'郵便番号'
            'todohuken'   =>$request->todohuken,  //'住所-都道府県'
            'shikuchoson' =>$request->shikuchoson,//'住所-市町村'
            'number'      =>$request->number,     //'住所-番地'
            'is_default'  => 1,//デフォルトの送信先か否か
            'size'        =>$request->size,       //靴のサイズ
            'email'       =>$request->email,      //メールアドレス 2025/12/02追加
            'remarks'     =>$request->remarks,    //備考欄 2025/12/02追加,
        ];


        # エンコード入力情報のデコード処理（絵文字対策）
        $inputs['name']    = urldecode($inputs['name']) ;
        $inputs['remarks'] = urldecode($inputs['remarks']) ;


        # text入力値が150文字以上の時、ストレージへファイル保存する
        $dir = 'upload/user/address';      //保存先ディレクトリ
        $new_text = $inputs['remarks'];  //新しい入力テキスト
        $inputs['remarks'] = Method::uploadStorageText($dir, $new_text);


        #新規登録
        $address = new UserAddress($inputs);
        $address->save();


        # 「デフォルト送信先」の変更
        $default_address_id = $address->id;
        self::UpdateDeffaultAddress( $default_address_id );


        return response()->json( $address );
    }



    /**
     * 削除
     *
     * @param  App\Models\UserAddress $user_address
     * @return \Illuminate\Http\Response
     */
    public function destroy( UserAddress $user_address )
    {
        $user_address->delete();
        return  response()->json(['message'=>'delete OK!']);
    }


    /**「デフォルト送信先」の変更 */
    public static function UpdateDeffaultAddress( $default_address_id )
    {
        $user = Auth::user();
        foreach( $user->addresses as $address ){

            //デフォルトアドレスに登録
            if( $address->id == $default_address_id ){
                $address->is_default = 1;
                $address->save();
            }
            //デフォルトアドレスから除外
            else{
                $address->is_default = 0;
                $address->save();
            }
        }
    }


}
