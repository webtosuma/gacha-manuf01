<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Text;
/*
| =============================================
|  Admin　ガチャ(一覧表示の設定) コントローラー
| =============================================
*/
class AdminGachaSettingsController extends Controller
{
    /**
     * ガチャ一覧の設定　編集
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_list(Request $request)
    {
        $types = [

            # ガチャ販売機の画像を利用する
            'gacha_settings_card_image',
            # ガチャ販売機の頭部画像
            'gacha_settings_card_image_head',
            'gacha_settings_card_image_head_default',
            # ガチャ販売機の本体画像
            'gacha_settings_card_image_body',
            'gacha_settings_card_image_body_default',

            # ガチャの読み込み中動画
            'gacha_settings_loading_movie',
            'gacha_settings_loading_movie_path',
            'gacha_settings_loading_movie_path_default',

            # 限定ガチャのラベル表示
            'gacha_settings_type_label_image',
            # 限定ガチャのテキスト表示
            'gacha_settings_type_label_text',
            # デフォルトの表示サイズ
            'gacha_settings_size',

        ];

        $data = [];
        foreach ($types as $type) {
            $text = new Text();
            $data[$type] = $text[$type];
        }
        // dd($data['gacha_settings_loading_movie_path']);

        return view('admin.gacha.settings.edit_list', compact('data'));
    }
}
