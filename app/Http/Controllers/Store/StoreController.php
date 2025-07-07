<?php

namespace App\Http\Controllers\Store;
use  App\Http\Controllers\Controller;
use  App\Http\Controllers\InfomationController;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreItem;
use App\Models\StoreKeep;
use App\Models\GachaCategory;
use App\Models\Gacha;
/*
| =============================================
|  ストアー コントローラー
| =============================================
*/
class StoreController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {

        # スライド
        $slides = self::getSlides();
        // dd($slides);

        # カテゴリー 一覧
        $categories = GachaCategory::where('is_published',1) //公開中
        ->orderBy('created_at')
        ->get();

        # 商品
        $store_items = StoreItem::
        where('published_at','<',now()->format('Y-m-d H:i:s'))
        ->limit(11)->get();

        ## お知らせ
        $infomations =
        InfomationController::GetInfomationsQuery()
        ->limit(3)
        ->get();

        # セクショングループ
        $section_group = self::getSectionGroup($categories);

        # ガチャ
        $gachas = Gacha::where('published_at', '<=', now())//公開中のみ
        ->orderByDesc('published_at')
        ->orderBy('is_sold_out')//売り切れは下
        ->limit(3)
        ->get();


        return view('store.index', compact(
            'slides','categories','infomations','section_group','gachas'
        ));
    }



        /**
         * スライド情報
        */
        public static function getSlides()
        {
            $slides = [];

            // お知らせ
            $infos = InfomationController::GetInfomationsQuery()
            ->where('is_slide',1)
            ->limit(10)
            ->get();

            foreach ($infos as $info) {
                $slides[] = [
                    'type' => 'info',
                    'href' => route('infomation.show',$info),
                    'image'=> $info->image_path ??  asset( 'storage/site/image/no_image.jpg' ),
                ];
            }


            // 商品
            $store_items = StoreItem::where('is_slide',1)
            ->where('published_at','<',now()->format('Y-m-d H:i:s'))
            ->limit(10)
            ->get();

            foreach ($store_items as $store_item) {
                $params = ['category_code'=>$store_item->category->code_name, 'store_item'=>$store_item, 'key'=>$store_item->key];
                $slides[] = [
                    'type' => 'store_item',
                    'href' => $store_item->r_show,
                    'image'=> $store_item->image_paths ? $store_item->image_paths[0] : $store_item->noImage(),
                    'store_item'=> $store_item,
                ];
            }

            return $slides;
        }



        /**
         * セクショングループ(カテゴーリー別)
        */
        public function getSectionGroup($categories)
        {
            $limit=8;
            $section_group = [];
            foreach ($categories as $category)
            {
                # カテゴリー別のEC商品
                $store_items = StoreItem::
                where('published_at','<',now()->format('Y-m-d H:i:s'))
                ->where('category_id',$category->id)
                ->orderByDesc('published_at')
                ->limit($limit)->get();

                if($store_items->count())
                {
                    $section_group[] = [
                        'line_id'     => 'line_id_'.$category->code_name,
                        'line_label'  => $category->name,
                        'line_r_more' => route('store.search',['category_code_name'=>$category->code_name]),
                        'store_items' => $store_items,
                    ];
                }

            }
            return $section_group;
        }


    /**
     * 検索結果ページ表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
    */
    public function search(Request $request)
    {
        # カテゴリーID
        $category = GachaCategory::where('code_name',$request->category_code_name)->first();
        $category_id = $category ? $category->id : null;

        // dd($request->all());
        # 検索パラメーター
        $search_inputs = [
            'category_id' => $category_id,
            'keyword'     => $request->keyword,
            'order'       => $request->order,
            'category_code_name' => $request->category_code_name,
        ];

        return view('store.search',compact(
            'search_inputs',
        ));
    }


    /**
     * 詳細
     *
     * @param $code
     * @return \Illuminate\View\View
    */
    public function show($code)
    {
        # 商品情報取得
        $store_item = StoreItem::where('code',$code)//コード認証
        ->forUserPublished()//ユーザー公開用スコープ
        ->first();
        if(!$store_item){ return \App::abort(404); }//商品が存在しないとき

        # 表示カウントの加算
        $store_item->showed_count ++;
        $store_item->save();

        # この商品が買い物カートに保存されているか？
        $store_keep = !Auth::check() ? null :
        StoreKeep::forUserPublished()
        ->where('store_item_id',$store_item->id)
        ->first();

        # スライド
        $slides = self::getSlides();

        return view('store.show',compact('store_item','store_keep','slides'));
    }


}
