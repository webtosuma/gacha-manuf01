<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Casts\Attribute;//オブジェクト化
use App\Http\Requests\AdminTextRequest;
use App\Http\Requests\AdminTextMetaRequest;
use App\Http\Requests\AdminTextSbgLicenseRequest;
use App\Models\Text;
/*
| =============================================
|  Admin　文書設定 コントローラー
| =============================================
*/
class AdminTextController extends Controller
{
    /* 文書の種類 */
    protected $text_types = [
        #フッターメニュー
        ['type'=>'trems'          ,'multiple'=>true ,'textarea_rows'=>20, 'label'=>'利用規約'],
        ['type'=>'privacy_policy' ,'multiple'=>true ,'textarea_rows'=>20, 'label'=>'プライバシーポリシー'],
        ['type'=>'tradelaw'       ,'multiple'=>true ,'textarea_rows'=>20, 'label'=>'特定商取引法に基づく表記'],

        ['type'=>'guide'          ,'multiple'=>false,'textarea_rows'=>20, 'label'=>'利用ガイド'],
        ['type'=>'sbg_license'    ,'multiple'=>false,'textarea_rows'=>6,  'label'=>'古物商営業許可'],

        ['type'=>'meta'           ,'multiple'=>false,'textarea_rows'=>null, 'label'=>'メタ情報'],
        ['type'=>'note'           ,'multiple'=>false,'textarea_rows'=>20,   'label'=>'商品購入に関する注意文'],
        ['type'=>'email_signature','multiple'=>false,'textarea_rows'=>6,    'label'=>'メール署名'],
    ];




    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # 文書の種類
        $text_types = $this->text_types;

        return view('admin.text.index', compact('text_types'));
    }



    /**
     * 編集
     *
     * @param  String $type
     * @return \Illuminate\Http\Response
     */
    public function edit($type)
    {
        # 文書の種類情報取得
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404

        # 特殊なreturn
        switch ($type) {
            # メタ情報
            case 'meta':
                return self::edit_meta($text_type);
                break;


            # 古物営業許可
            case 'sbg_license':
                return self::edit_sbg_license($text_type);
                break;

        }

        # 通常
        return self::edit_index( $type, $text_type );

    }


        /* 通常 */
        public function edit_index( $type, $text_type )
        {
            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();
            $text = $text ?? new Text(['type'=>$type]);

            #文書データにデータ追加
            foreach ($text_type as $key => $value){ $text[$key] = $value; }

            return view('admin.text.edit', compact('text','text_type'));
        }



        /* メタ情報編集 */
        public function edit_meta($text_type)
        {
            $types = ['meta_title','meta_description','meta_keyword'];


            # データ取得
            $metas = \App\Models\Text::getMeta();
            $text_bodys = [];
            foreach ($metas as $key => $body) {
                $text_bodys['meta_'.$key] = $body;
            }


            return view('admin.text.edit', compact('text_bodys','text_type') );
        }



        /* 古物営業許可 */
        public function edit_sbg_license($text_type)
        {
            $types = ['license_number','license_commission','license_name',];
            foreach ($types as $type) {
                $text = Text::where('type',$type)->orderByDesc('id')->first();
                $text_bodys[$type] = $text ? $text->body : '';
            }
            // dd($text_bodys);

            return view('admin.text.edit', compact('text_bodys','text_type') );
        }



    /**
     * 更新
     *
     * @param  AdminTextRequest $request
     * @param  String $type
     * @return \Illuminate\Http\Response
     */
    public function update( AdminTextRequest $request, $type )
    {
        # 文書の種類情報取得
        $text_type = collect($this->text_types)->firstWhere('type', $type);
        if (!$text_type) { abort(404); }// 見つからなければ 404


        # 文書データの取得
        $text = Text::where('type',$type)->orderByDesc('id')->first();

        # 入力データの加工
        $inputs = self::processingInputs( $request, $type, $text );

        # DBデータの更新・新規登録
        if( $text ){
            $text->update( $inputs );
        }else{
            $text = new Text($inputs);
            $text->save();
        }

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $text->type .'.update', $text->id );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.text')
        ->with(['alert-warning'=> $text_type['label'].'を更新しました。']);
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



    /**
     * メタ情報 更新
     *
     * @param  AdminTextMetaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function meta_update( AdminTextMetaRequest $request )
    {

        // dd($request->all());
        # 更新パラメーター
        $bodys = $request->only(
            'meta_title','meta_description','meta_keyword'
        );

        foreach ($bodys as $type => $body)
        {
            # デコード処理（絵文字対策）・改行除去
            $body = urldecode( $body );
            $body = preg_replace("/\r\n|\r|\n/", '', $body); // 改行削除
            $body = preg_replace('/\s+/', '', $body);      // 空白・空行削除

            # 保存データ
            $inputs = [
                'type' => $type,
                'body' => $body
            ];

            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();

            # DBデータの更新・新規登録
            if( $text ){
                $text->update( $inputs );
            }else{
                $text = new Text($inputs);
                $text->save();
            }
        }

        # 更新キーワード
        $type='meta';
        $type_label='メタ情報';

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $type .'.update' );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.text')
        ->with(['alert-warning'=> $type_label.'を更新しました。']);

    }



    /**
     * 古物商営業許可 更新
     *
     * @param  AdminTextSbgLicenseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function sbg_license_update( AdminTextSbgLicenseRequest $request )
    {

        # 更新パラメーター
        $bodys = $request->only(
            'license_number','license_commission','license_name',
        );

        foreach ($bodys as $type => $body)
        {
            # デコード処理（絵文字対策）・改行除去
            $body = $type=='license_name' ? urldecode( $body ) : $body;//法人(個人)名称 のみ

            # 保存データ
            $inputs = [
                'type' => $type,
                'body' => $body
            ];

            # 文書データの取得
            $text = Text::where('type',$type)->orderByDesc('id')->first();

            # DBデータの更新・新規登録
            if( $text ){
                $text->update( $inputs );
            }else{
                $text = new Text($inputs);
                $text->save();
            }
        }

        # 更新キーワード
        $type='license_update';
        $type_label='古物商営業許可';

        # 操作ログの更新
        AdminLogController::createLog( 'text.'. $type .'.update' );

        # 二重送信防止
        $request->session()->regenerateToken();


        # リダイレクト
        return redirect()->route('admin.text')
        ->with(['alert-warning'=> $type_label.'を更新しました。']);

    }

    
}
