<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminSpnsorAdRequest;
use App\Models\Sponsor;
use App\Models\SponsorAd;
use App\Models\Gacha;
use App\Models\GachaCategory;
/*
|--------------------------------------------------------------------------
| Admin スポンサー　広告　コントローラー
|--------------------------------------------------------------------------
*/
class AdminSponsorAdController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # 販売用ポイント情報取得
        $sponsor_ads = SponsorAd::orderByDesc('id')->get();

        // dd($sponsor_ads[0]->movie_path);

        return view('admin.sponsor_ad.index',compact('sponsor_ads'));
    }






    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sponsor_ad = new SponsorAd();


        # スポンサー情報
        $sponsors = Sponsor::orderByDesc('id')->get();
        // # ガチャ情報
        // 'gachas'     => Gacha::orderBy('is_sold_out')->orderByDesc('published_at')->get(),

        # カテゴリー情報
        $categories = GachaCategory::orderBy('created_at')->get();
        foreach ($categories as $category) {
            $category->gachas = Gacha::where('category_id',$category->id)
            ->orderBy('is_sold_out')->orderByDesc('published_at')->get();
        }

        # セレクト情報
        $selects = compact('sponsors','categories');

        // dd($selects['sponsors'][0]->tell);
        return view('admin.sponsor_ad.create', compact('sponsor_ad','selects'));
    }




    /**
     * 登録
     *
     * @param AdminSpnsorlRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminSpnsorAdRequest $request)
    {
        # 入力データの加工
        $inputs = self::processingInputs( $request );


        # 広告情報の保存
        $sponsor_ad = new SponsorAd($inputs);
        $sponsor_ad->save();


        # 返信メッセージ
        return redirect()->route('admin.sponsor_ad')
        ->with(['alert-primary'=>'広告情報を新規登録しました。']);
    }




    /**
     * 入力データの加工 self::processingInputs( $request )
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sponser $sponser //新規登録のとき===null
     * @return Array
     */
    public function processingInputs( $request, $sponser_ad=null )
    {
        # userテーブル情報
        $inputs = [
            'title'      => $request['title'], //広告タイトル',
            'movie'      => $request['movie'], //動画パス',
            'gacha_id'   => $request['gacha_id'], //ガチャ',
            'sponsor_id' => $request['sponsor_id'], //スポンサー',
            'url'        => $request['url'], //
        ];

        # ストレージ画像ファイルの更新（イメージ画像）
        $dir = 'upload/sponser_ad/movie/';          //保存先ディレクトリ
        $request_file    = $request->file('movie'); //画像のリクエスト
        $old_image_path  = $sponser_ad ? $sponser_ad->movie : null;           //更新前の画像パス
        $image_dalete    = $request->movie_dalete;  //画像を削除するか否か
        $inputs['movie'] = Method::uploadStorageImage( $dir, $request_file, $old_image_path, $image_dalete) ?? '';


        return $inputs;
    }

}
