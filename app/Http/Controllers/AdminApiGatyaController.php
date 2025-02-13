<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GachaCategory;
use App\Models\Gacha;
use App\Models\GachaDiscription;
use App\Models\GachaPrize;
/*
| =============================================
|  ガチャ サイト管理者API コントローラー
| =============================================
*/
class AdminApiGatyaController extends Controller
{

    /**
     * カテゴリ一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request)
    {
        $category = GachaCategory::orderBy('created_at')->get();

        return response()->json( $category );
    }


    /**
     * 一覧取得
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        # カテゴリーコードの確認
        $category_code  = $request->category_code;
        $gacha_category = GachaCategory::where('code_name',$category_code)->first();


        # 表示できるガチャ一覧
        $query = Gacha::query();

            # カテゴリーの絞り込み
            if( $gacha_category ){
                $query->where('category_id',$gacha_category->id);
            }

            # 売り切れは下
            $query->orderBy('is_sold_out');

            # 公開状態
            switch ( $request->published_status ) {
                case 'published'://公開中
                    $query->where('published_at','<>',null)->where('published_at','<=',now());
                    break;

                case 'reserv_publish'://公開予約
                    $query->where('published_at','>',now());
                    break;

                case 'an_publish'://未公開
                    $query->where('published_at',null);
                    break;

                case 'sold_out'://売り切れ
                    $query->where('is_sold_out',1);
                    break;

                default:
                    # code...
                    break;
            }



            # 並び替え(新しい順)
            if( $request->order!='asc.published_at' ){
                $query->orderByDesc('published_at')
                ->orderByDesc('created_at');

            }else{
                $query->orderBy('published_at')
                ->orderBy('created_at');
            }

            // $query->orderByDesc('published_at')
            // ->orderByDesc('created_at');

            $query->has('category');//カテゴリーが存在するもののみ

        $gachas = $query->paginate( 20 );//ページネーション


        # 追加情報
        foreach ($gachas as $gacha)
        {

            $gacha->is_published = $gacha->is_published;//公開中
            $gacha->published_at = $gacha->published_at;//公開・公開予約中
            $gacha->published_at_format = $gacha->published_at ? $gacha->published_at->format('Y/m/d H:i') : '--/--/-- --:--';
            $gacha->type_text           = $gacha->types()[$gacha->type];//ガチャの種類
            $gacha->user_rank_label     = env('NEW_TICKET_SISTEM',false) ? ( $gacha->user_rank_id!==null ? $gacha->user_rank->label : '全ての' ) : null ;


            /* 画像 */
            $gacha->ratio      = config('app.gacha_card_ratio'); //画像比率
            $gacha->image_path = $gacha->image_path;             //
            $gacha->type       = $gacha->type;                   //ガチャの種類

            $gacha->i_time                 = $gacha->initial_time; //カウントダウン時間
            $gacha->limitted_i_time        = $gacha->initial_timezone;//時間帯限定
            // $gacha->published_at_format    = $gacha->published_at > now()
            // ? $gacha->published_at->format('Y/m/d H:i:s') : null;//(新作ガチャ)カウントダウン用日時
            $gacha->add_chance_image_path  = $gacha->add_chance_image_path;//アド確定予告画像パス
            $gacha->add_chance_count       = $gacha->add_chance_count;     //天井系ガチャのアド確定までの回転数
            $gacha->have_user_rank         = $gacha->have_user_rank;       //個人のプレイ数の商品登録
            $gacha->user_played_count      = $gacha->user_played_count;    //ログインユーザー個人のプレイ数

            $gacha->img_path_one_chance    = $gacha->img_path_one_chance;   //ワンチャンス限定
            $gacha->img_path_one_time      = $gacha->img_path_one_time;     //一回限定
            $gacha->img_path_only_oneday   = $gacha->img_path_only_oneday;  //1日一回限定
            $gacha->img_path_only_new_user = $gacha->img_path_only_new_user;//新規会委員限定
            $gacha->img_path_user_rank     = $gacha->img_path_user_rank;    //会員ランク限定

            /* メーター */
            $gacha->user_rank_id    = $gacha->user_rank_id;    //
            $gacha->remaining_ratio = $gacha->remaining_ratio; //残数比率
            $gacha->remaining_count = $gacha->remaining_count; //残数
            $gacha->max_count       = $gacha->max_count;       //総口数
            $gacha->sponsor_ad      = $gacha->sponsor_ad;      //スポンサー
            $gacha->new_label_path  = $gacha->new_label_path;  //NEW ラベル
            $gacha->img_path_point  = $gacha->img_path_point;  //ポイントアイコン


            /* ルーティング */
            $gacha->r_admin_show    = route('admin.gacha.show',$gacha);
            $gacha->r_admin_edit    = route('admin.gacha.edit',$gacha);
            $gacha->r_admin_copy    = route('admin.gacha.copy',$gacha);
            $gacha->r_admin_destroy = route('admin.gacha.destroy',$gacha);



        }





        # カテゴリー一覧
        $categories = GachaCategory::orderBy('created_at')->get();

        # 公開状態選択肢
        $published_statuses = [
            ['key'=>'published',      'label'=> '公開中'  ],
            ['key'=>'reserv_publish', 'label'=> '公開予約'],
            ['key'=>'an_publish',     'label'=> '未公開'  ],
            ['key'=>'sold_out',       'label'=> '売り切れ'],
        ];

        # 並び替え選択肢
        $orders = [
            ['key'=>'desc.published_at', 'label'=> '新しい順' ],
            ['key'=>'asc.published_at' , 'label'=> '古い順'  ],
        ];

        return response()->json( compact('gachas','categories','published_statuses','orders'));
    }




    /**
     * ランク情報の取得
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gacha $gacha
     * @return \Illuminate\Http\Response
     */
    public function ranks(Request $request, Gacha $gacha)
    {
        # ガチャ情報
        $gacha = Gacha::find($gacha->id);
        $gacha->max_count = $gacha->max_count;
        $gacha->total_play_point = $gacha->one_play_point * $gacha->max_count;//合計ポイント
        $gacha->total_point = $gacha->total_point;

        # ランク情報
        $discriptions = $gacha->discriptions;
        foreach ($discriptions as $discription) {
            $discription->rank_label = $discription->rank_label; //ランクラベル

            $discription->total_count_format     = $discription->total_count_format;     //口数(g_prizes_max_count)
            $discription->average_point_format   = $discription->average_point_format;
            $discription->winning_ratio_format   = $discription->winning_ratio_format;

            $discription->hit_nums = $discription->hit_nums;//商品の合当選ガチャPLAY数

            $discription->gacha_prizes_count = GachaPrize::where('gacha_id',$gacha->id)->where('gacha_rank_id',$discription->gacha_rank_id)
            ->get()->count();//登録が茶用品の種類数

            $ratio = $gacha->max_count
            ? $discription->g_prizes->sum('max_count')/$gacha->max_count*100 :0;
            $discription->g_prizes_ratio = round( $ratio, 2);//当選率(g_prizes_ratio)

        }

        return response()->json(compact('gacha','discriptions'));
    }




    /**
     * ガチャランク別、ガチャ商品情報の取得
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\GachaDiscription $discription
     * @return \Illuminate\Http\Response
     */
    public function ranks_gacha_prizes(Request $request, GachaDiscription $discription)
    {
        $g_prizes = $discription->g_prizes;
        foreach ($g_prizes as $g_prize) {
            $g_prize->prize = $g_prize->prize; //商品情報
            $g_prize->prize->rank = $g_prize->prize->rank; //商品情報
            $g_prize->prize->image_path = $g_prize->prize->image_path;//商品画像
        }


        return response()->json( $g_prizes );
    }
}
