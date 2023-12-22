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
        // dd( $request->all() );

        # 入力データの加工
        $inputs = [
            'name'=> $request->name,
            'pc_storage'    => '',
            'mobile_storage'=> '',
        ];

        # DBデータの新規登録
        $movie = new Movie( $inputs );
        $movie->save();


        # 返信メッセージ
        return redirect()->route('admin.movie.edit',$movie)
        ->with(['alert-primary'=>'新規登録する演出動画名を登録しました。']);
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
        // dd($request->all());

        $inputs = [];

        # PC用動画の更新
        if( $request->pc ){

            # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/movie/pc_storage/';             //保存先ディレクトリ
            $request_file    = $request->file('pc_storage');     //画像のリクエスト
            $old_image_path  = $movie->pc_storage; //更新前の画像パス
            $image_dalete    = $request->pc_storage_dalete;      //画像を削除するか否か

            $inputs['pc_storage'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete) ?? '';

            $message = 'PC用動画を更新しました。';
        }

        # PCモバイル動画の更新
        elseif( $request->mobile ){

            # ストレージ画像ファイルの更新（イメージ画像）
            $dir = 'upload/movie/mobile_storage/';             //保存先ディレクトリ
            $request_file    = $request->file('mobile_storage');     //画像のリクエスト
            $old_image_path  = $movie->mobile_storage; //更新前の画像パス
            $image_dalete    = $request->mobile_storage_dalete;      //画像を削除するか否か

            $inputs['mobile_storage'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete) ?? '';

            $message = 'モバイル用動画を更新しました。';
        }

        # 演出動画名の更新
        elseif( $request->name ){
            $inputs['name'] = $request->name;
            $message = '演出動画名を更新しました。';
        }



        # DBデータの更新
        $movie->update( $inputs );


        # リダイレクト
        return redirect()->route('admin.movie.edit', $movie)
        ->with(['alert-warning'=>$message]);
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


}
