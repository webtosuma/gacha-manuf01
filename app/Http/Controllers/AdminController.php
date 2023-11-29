<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
/**
 * =========================================
 *  サイト管理者　コントローラー
 * =========================================
*/
class AdminController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        # 権限がなければ、一覧表示不可
        $admin = Auth::user()->admin;
        if( !$admin->master ){
            return redirect()->route('admin.register.edit',$admin);
        }

        //全管理者情報の取得
        $admins = Admin::all();


        return view('admin.register.index', compact( 'admins' ) );
    }





    /**
     * 登録
     *
     * @param \App\Http\Requests\AdminRequest $request (バリデーション)
     * @return \Illuminate\View\View
    */
    public function store(AdminRequest $request)
    {
        // ユーザー情報の保存
        $user = new User([
            'name'   => $request->name,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);
        $user->save();
        $admin = new Admin([
            'user_id'=> $user->id,
            'master' => $request->master ? 1 : 0
        ]);
        $admin->save();
        $request->session()->regenerateToken();

        # 管理者一覧へリダイレクト
        return redirect()->route('admin.register')
        ->with('alert-success','管理者アカウントを新規登録しました。');
    }





    /**
     * 編集ページ
     *
     * @param Admin $admin
     * @return \Illuminate\View\View
    */
    public function edit(Admin $admin)
    {
        $edit_admin = $admin;
        return view( 'admin.register.edit',compact('edit_admin') );
    }




    /**
     * 更新
     *
     * @param \App\Http\Requests\AdminRequest $request (バリデーション)
     * @param Admin $admin
     * @return \Illuminate\View\View
    */
    public function update(AdminRequest $request, Admin $admin)
    {
        $user  = $admin->user;

        # 管理者情報更新
        if( isset($request->name) )
        {
            $user->update([
                'name'   => $request->name,
                'email' => $request->email,
            ]);
            $request->session()->regenerateToken();

            return redirect()->route('admin.register.edit', $request->admin_id )
            ->with('alert-success','管理者情報を更新しました。');
        }


        # パスワード更新
        if( isset($request->password) )
        {
            $user->update([
                'password' => Hash::make( $request->password ),
            ]);
            $request->session()->regenerateToken();

            return redirect()->route('admin.register.edit', $request->admin_id )
            ->with('alert-success','パスワードを更新しました。');
        }


        # その他設定更新(スイッチによる設定の更新)
        if( isset( $request['form-switch'] ) )
        {
            $admin->update([
                'get_mail' => isset( $request->get_mail ) ? 1 :0 ,
                'master' => isset( $request->master ) ? 1 :0,
            ]);
            $request->session()->regenerateToken();


            return redirect()->route('admin.register.edit', $request->admin_id )
            ->with('alert-success','その他設定を更新しました。');
        }



        return redirect()->route('admin.register.edit', $request->admin_id );
    }




    /**
     * 削除
     *
     * @param \Illuminate\Http\Request $request
     * @param Admin $admin
     * @return \Illuminate\View\View
    */
    public function destroy(Request $request, Admin $admin)
    {
        # 管理者の削除
        $admin->delete();


        # リダイレクト
        return redirect()->route('admin.register')
        ->with('alert-danger', '管理者情報を1件削除しました。');
    }




}
