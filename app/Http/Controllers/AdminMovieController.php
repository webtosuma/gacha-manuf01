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
        $movie = new Movie();

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
        # 入力データの加工
        $inputs = self::processingInputs( $request, $movie=null );

        # DBデータの新規登録
        $movie = new Movie( $inputs );
        $movie->save();

        # 操作ログの更新
        AdminLogController::createLog( 'movie.create', $movie->id );

        $request->session()->regenerateToken();// 二重送信防止


        # リダイレクト
        return redirect()->route('admin.movie', $movie)
        ->with(['alert-primary'=>'演出動画を新規登録しました。']);
    }



    /**
     * 編集
     *
     * @param  \App\Models\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
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

        # 操作ログの更新
        AdminLogController::createLog( 'movie.edit', $movie->id );

        $request->session()->regenerateToken();// 二重送信防止


        # リダイレクト
        return redirect()->route('admin.movie', $movie)
        ->with(['alert-warning'=>'演出動画を更新しました。']);
    }



    /**
     * 削除
     *
     * @param  \App\Models\Movie $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy( Movie $movie )
    {
        # ストレージファイルの削除
        Method::deleteStorageFile($movie->pc_storage);
        Method::deleteStorageFile($movie->mobile_storage);

        $movie->delete();

        # 操作ログの更新
        AdminLogController::createLog( 'movie.delete', $movie->id );


        # リダイレクト
        return redirect()->route('admin.movie')
        ->with(['alert-danger'=>'演出動画情報を削除しました。']);
    }




    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Movie $movie //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $movie=null )
    {
        $inputs = [
            'name'       => $request->name ,    //出動画名の更新
            'pc_storage' => '',
        ];


        # PCモバイル動画の更新
        if( $request->youtube_url )
        {
            $inputs['mobile_storage'] = $request->youtube_url;
        }
        elseif( $request->mobile_storage || $request->mobile_storage_dalete )
        {
            # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/movie/mobile_storage/';             //保存先ディレクトリ
            $request_file    = $request->file('mobile_storage');     //画像のリクエスト
            $old_image_path  = $movie ? $movie->mobile_storage : null; //更新前の画像パス
            $image_dalete    = $request->mobile_storage_dalete;      //画像を削除するか否か

            $inputs['mobile_storage'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete) ?? '';
        }

        return $inputs;
    }
}
