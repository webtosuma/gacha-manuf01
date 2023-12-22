<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminMovieRequest;
use App\Models\Movie;
/*
| =============================================
|  サイト管理者 演出動画 コントローラー
| =============================================
*/
class AdminMovieController extends Controller
{

    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # 販売用ポイント情報取得
        $movies = Movie::all();

        return view('admin.movie.index',compact('movies'));
    }



    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $movie = new Movie([
            'price' => 0,
            'value' => 0,
            'is_published' => 0,
        ]);

        return view('admin.movie.create', compact('movie'));
    }



    /**
     * 登録
     *
     * @param  \Illuminate\Http\AdminMovieRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminMovieRequest $request)
    {
        dd( $request->all() );

        # 入力データの加工
        $inputs = self::processingInputs( $request );

        # DBデータの新規登録
        $movie = new Movie( $inputs );
        $movie->save();


        # 返信メッセージ
        return redirect()->route('admin.movie')
        ->with(['alert-primary'=>'演出動画を新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(PointSail $movie)
    {
        return view('admin.movie.edit', compact('movie'));
    }



    /**
     * 更新
     *
     * @param  \Illuminate\Http\AdminMovieRequest  $request
     * @param  \App\Models\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function update(AdminMovieRequest $request, Movie $movie)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request, $movie );


        # DBデータの更新
        $movie->update( $inputs );


        # リダイレクト
        return redirect()->route('admin.movie')
        ->with(['alert-warning'=>'演出動画情報を更新しました。']);
    }



    /**
     * 削除
     *
     * @param  \App\Models\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy( Movie $movie )
    {
        $movie->delete();

        # リダイレクト
        return redirect()->route('admin.movie')
        ->with(['alert-danger'=>'演出動画情報を削除しました。']);
    }



    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Movie $pointSail //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $pointSail=null )
    {
        $inputs = $request->only(
            'value'       ,// '付与ポイント数',
            'price'       ,// 'ポイント販売価格',
            'is_published',// '公開設定',
        );

        # お得分の計算
        $service = $request->value - $request->price;
        $inputs['service'] = $service > 0? $service : 0 ;

        return $inputs;
    }
}
