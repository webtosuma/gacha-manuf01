<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminSpnsorRequest;
use App\Models\Sponsor;
use App\Models\User;
use App\Models\UserAddress;
/*
|--------------------------------------------------------------------------
| Admin スポンサー　コントローラー
|--------------------------------------------------------------------------
*/
class AdminSponsorController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # 販売用ポイント情報取得
        $sponsors = Sponsor::orderByDesc('id')->get();

        return view('admin.sponsor.index',compact('sponsors'));
    }




    /**
     * 表示
     *
     * @param  Sponsor $sponsor
     * @return \Illuminate\Http\Response
     */
    public function show( Sponsor $sponsor )
    {
        return view('admin.sponsor.show', compact('sponsor'));
    }





    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sponsor = new Sponsor();

        $user = new User();

        $user_address = new UserAddress();

        return view('admin.sponsor.create', compact('user','user_address','sponsor'));
    }



    /**
     * 登録
     *
     * @param AdminSpnsorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminSpnsorRequest $request)
    {
        # 入力データの加工
        list($user_inputs,$user_address_inputs) = self::processingInputs( $request );


        # Userテーブルの登録
        $user = new User($user_inputs);
        $user->save();

        # UserAddressテーブルの登録
        $user_address_inputs['user_id'] = $user->id;
        $user_address = new UserAddress( $user_address_inputs);
        $user_address->save();

        # Sponsorテーブルの保存
        $sponsor = new Sponsor(['user_id' => $user->id]);
        $sponsor->save();


        # 返信メッセージ
        return redirect()->route('admin.sponsor')
        ->with(['alert-dark'=>'スポンサー情報を新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  Sponsor $sponsor
     * @return \Illuminate\Http\Response
     */
    public function edit(Sponsor $sponsor)
    {
        $user = $sponsor->user;
        $user_address = $sponsor->address;


        return view('admin.sponsor.edit', compact('user','user_address','sponsor'));
    }




    /**
     * 更新
     *
     * @param AdminSpnsorRequest  $request
     * @param Sponsor $sponsor
     * @return \Illuminate\Http\Response
     */
    public function update(AdminSpnsorRequest  $request, Sponsor $sponsor)
    {
        # 入力データの加工
        list($user_inputs,$user_address_inputs) = self::processingInputs( $request, $sponsor );


        # Userテーブルの更新
        $user = $sponsor->user;
        $user->update($user_inputs);

        # UserAddressテーブルの更新
        $user_address = $sponsor->address;
        $user_address->update( $user_address_inputs);


        # リダイレクト
        return redirect()->route('admin.sponsor')
        ->with(['alert-warning'=>'スポンサー情報を更新しました。']);
    }




    /**
     * 削除
     *
     * @param Sponsor $sponsor
     * @return \Illuminate\Http\Response
     */
    public function destroy( Sponsor $sponsor )
    {
        $sponsor->user->delete();
        $sponsor->delete();

        # リダイレクト
        return redirect()->route('admin.sponsor')
        ->with(['alert-danger'=>'スポンサー情報を削除しました。']);
    }



    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sponser $sponser //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $sponser=null )
    {
        # userテーブル情報
        $user_inputs = [
            'name'     => $request->user_name,
            'email'    => $request->user_email,
        ];
        if( !$sponser ){ $user_inputs['password'] = Hash::make($request->password); }


        # 住所情報
        $user_address_inputs = [
            // 'user_id'     =>$user->id,//リレーションID
            'name'        =>$request->user_name,       //宛名
            'tell'        =>$request->user_address_tell,       //電話番号
            'postal_code' =>$request->user_address_postal_code,//'郵便番号'
            'todohuken'   =>$request->user_address_todohuken,  //'住所-都道府県'
            'shikuchoson' =>$request->user_address_shikuchoson,//'住所-市町村'
            'number'      =>$request->user_address_number,     //'住所-番地'
        ];

        return [ $user_inputs, $user_address_inputs ];
    }
}
