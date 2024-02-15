<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminInfomationRequest;
use App\Models\Infomation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendEmailInfomationJob;
/*
| =============================================
|  サイト管理者　お知らせ コントローラー
| =============================================
*/
class AdminInfomationController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # 非公開か否か
        $anpublished = $request->anpublished ? 1 : 0;

        // dd($request->all());

        $query = Infomation::query();

            if($anpublished){
                $query->where('published_at',null);
            }else{
                $query->where('published_at','<>',null);
            }

        $infomations = $query->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->paginate(20);


        return view('admin.infomation.index', compact('infomations','anpublished'));
    }



    /**
     * 表示
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function show( Infomation $infomation )
    {
        return view('admin.infomation.show', compact('infomation'));
    }



    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $infomation = new Infomation([
            'is_slide' => 0,
        ]);

        return view('admin.infomation.create', compact('infomation'));
    }



    /**
     * 登録
     *
     * @param  \Illuminate\Http\AdminInfomationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminInfomationRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $infomation = new Infomation( $inputs );
        $infomation->save();


        # 返信メッセージ
        return redirect()->route('admin.infomation')
        ->with(['alert-primary'=>'お知らせ情報を新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function edit(Infomation $infomation)
    {
        return view('admin.infomation.edit', compact('infomation'));
    }



    /**
     * 更新
     *
     * @param  \Illuminate\Http\AdminInfomationRequest  $request
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function update(AdminInfomationRequest  $request, Infomation $infomation)
    {
        // dd($request->all());
        # 入力データの加工
        $inputs = self::processingInputs( $request, $infomation );

        // dd($inputs);
        # DBデータの更新
        $infomation->update( $inputs );


        # リダイレクト
        return redirect()->route('admin.infomation')
        ->with(['alert-warning'=>'お知らせ情報を更新しました。']);
    }



    /**
     * 削除
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Infomation $infomation)
    {
        $infomation->delete();


        # リダイレクト
        return redirect()->route('admin.infomation')
        ->with(['alert-danger'=>'お知らせ情報を削除しました。']);
    }



    /**
     * メール・プレビュー
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function email(Infomation $infomation)
    {
        return view('admin.infomation.email', compact('infomation'));
    }




    /**
     * メール・一括送信
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function email_post(Infomation $infomation)
    {
        # メール送信日の登録
        $infomation->update(['send_email_at'=>now()]);


        # メール受信設定ユーザーの取得
        $users = User::where('get_email',1)->get();

        # test用
        // $users = User::where('email','t.sakai@tosuma.ltd')->get();


        foreach ($users as $user) {

            SendEmailInfomationJob::dispatch($user,$infomation)
            ->delay(now()->addMinutes(1));

        }


        # リダイレクト
        return redirect()->route('admin.infomation')
        ->with(['alert-success'=>'『'.$infomation->title.'』を一括メール送信しました。']);
    }



    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gacha $gacha //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $infomation=null )
    {
        $inputs = $request->only(
            'title',       //題名
            'body' ,       //本文
            'image',       //画像
            'is_slide',    //スライドの表示有無
            'published_at',//公開日時
        );

        # スライドの表示有無
            $inputs['is_slide'] = $request->is_slide==true ;


        # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
            $inputs['title'] = urldecode($inputs['title']);
            $inputs['body']  = urldecode($inputs['body']) ;

            # ストレージ更新の処理（商品説明）body
            $old_text = $infomation? $infomation->body: null;  //更新前のファイルパステキスト
            $new_text = $inputs['body'];             //新しい入力テキスト
            $dir = 'upload/infomation/body/';      //保存先ディレクトリ
            $inputs['body'] = Method::uploadStorageText($dir, $new_text, $old_text);


        # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/infomation/image/';             //保存先ディレクトリ
            $request_file    = $request->file('image');     //画像のリクエスト
            $old_image_path  = $infomation? $infomation->image: null; //更新前の画像パス
            $image_dalete    = $request->image_dalete;      //画像を削除するか否か
            $copy_image_puth = $request->copy_image_puth;       //コピー用画像パス

            $inputs['image'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete, $copy_image_puth);


        # 公開設定
            $published_at = $infomation? $infomation->published_at :NULL;
            $is_published = $infomation? $infomation->is_published :NULL;//公開中か否か

            // 公開[1](前回が「公開」でないとき)
            if( $request->is_published==1 && !$is_published ){
                $published_at = now()->format('Y-m-d H:i:00');
            }
            // 公開予約[2]
            else if( $request->is_published==2 ){
                $published_at = str_replace('T',' ', $request->published_at );
            }
            // 非公開[0]
            else if( $request->is_published==0 ){
                $published_at = NULL;
            }

            $inputs['published_at'] = $published_at;
        //


        return $inputs;
    }
}
