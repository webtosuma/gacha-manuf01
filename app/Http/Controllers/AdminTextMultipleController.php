<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminTextMultipleRequest;
use App\Models\Text;
/*
| =============================================
|  Admin　文書設定 複数登録 コントローラー
| =============================================
*/
class AdminTextMultipleController extends Controller
{
    /* 複数登録文書の種類 */
    protected $text_types = [

        ['type'=>'trems'          ,'multiple'=>true ,'textarea_rows'=>20, 'label'=>'利用規約'],
        ['type'=>'privacy_policy' ,'multiple'=>true ,'textarea_rows'=>20, 'label'=>'プライバシーポリシー'],
        ['type'=>'tradelaw'       ,'multiple'=>true ,'textarea_rows'=>20, 'label'=>'特定商取引法に基づく表記'],

    ];


    /**
     * 一覧
     * @param  String $type //文書の種類
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        # 文書の種類情報取得
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404

        # 文書データの取得
        $texts = Text::where('type',$type)->orderByDesc('enactmented_at')->get();
        // dd($texts->toArray());

        return view('admin.text.multiple.index', compact('texts','text_type'));
    }



    /**
     * 新規作成
     *
     * @param  String $type //文書の種類
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        # 文書の種類情報取得
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404

        # 新規の文書データ
        $text = new Text([
            'type'           => $type, //文書の種類
            'enactmented_at' => null,  //制定日
        ]);

        #文書データにデータ追加
        foreach ($text_type as $key => $value){ $text[$key] = $value; }
        // dd($text->toArray());


        return view('admin.text.multiple.create', compact('text','text_type'));
    }




    /**
     * 登録
     *
     * @param  AdminTextMultipleRequest $request
     * @param  String $type //文書の種類
     * @return \Illuminate\Http\Response
     */
    public function store(AdminTextMultipleRequest $request, $type)
    {
        # 文書の種類情報取得
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404

        # 入力データの加工
        $inputs = self::processingInputs( $request, $type, $text=null );
        // dd($inputs);

        # DBデータの新規登録
        $text = new Text( $inputs );
        $text->save();

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $text->type .'.create', $text->id );

        $request->session()->regenerateToken();// 二重送信防止


        # リダイレクト
        return redirect()->route('admin.text.multiple',$type)
        ->with(['alert-primary'=>$text_type['label'].'を新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  String $type //文書の種類
     * @param  Text   $text
     * @return \Illuminate\Http\Response
     */
    public function edit($type, Text $text)
    {
        # 文書の種類情報取得
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404

        #文書データにデータ追加
        foreach ($text_type as $key => $value){ $text[$key] = $value; }
        // dd($text->toArray());


        return view('admin.text.multiple.edit', compact('text','text_type'));
    }




    /**
     * 更新
     *
     * @param  AdminTextMultipleRequest $request
     * @param  String $type //文書の種類
     * @param  Text   $text
     * @return \Illuminate\Http\Response
     */
    public function update(AdminTextMultipleRequest $request, $type, Text $text )
    {
        # 文書の種類情報取得
        $type = $text->type;
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404

        # 入力データの加工
        $inputs = self::processingInputs( $request, $type, $text );

        # DBデータの更新
        $text->update( $inputs );

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $text->type .'.update', $text->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.text.multiple',$type)
        ->with(['alert-warning'=> $text->enactmented_at_format.'の'.$text_type['label'].'を更新しました。']);
    }



    /**
     * 削除
     *
     * @param  String $type //文書の種類
     * @param  Text   $text
     * @return \Illuminate\Http\Response
     */
    public function destroy( $type, Text $text )
    {
        # 文書の種類情報取得
        $type = $text->type;
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404

        # 削除
        $text_id = $text->id;
        $text->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $text->type .'.delete', $text_id );


        # リダイレクト
        return redirect()->route('admin.text.multiple',$type)
        ->with(['alert-danger'=> $text_type['label'].'を削除しました。']);
    }


        /**
         * 入力データの加工 self::processingInputs( $request )
         *
         * @param \Illuminate\Http\Request $request
         * @param  String $type //文書の種類
         * @param \App\Models\Text $text //新規登録のとき===null
         * @return Array
         */
        public function processingInputs( $request, $type, $text=null )
        {
            $inputs = $request->only(
                'body',          //本文
                'enactmented_at',//制定日
            );

            # 文書の種類
            $inputs['type'] = $type;

            # エンコードコンポーネント入力情報のデコード処理（絵文字対策）
            $inputs['body']  = urldecode($inputs['body']) ;

            # ストレージ更新の処理（本文）body
            $old_text = $text? $text->body: null;        //更新前のファイルパステキスト
            $new_text = $inputs['body'];                 //新しい入力テキスト
            $dir = 'upload/' .$type. '/body/'; //保存先ディレクトリ
            $inputs['body'] = Method::uploadStorageText($dir, $new_text, $old_text);


            return $inputs;
        }

    /* */
}
