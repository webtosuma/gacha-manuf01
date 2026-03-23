<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitleMachines;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル 筺体 コントローラー
| =============================================
*/
class AdminGachaTitleMachineController extends Controller
{
    /**
     * 一覧
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function index( ManufGachaTitle $gacha_title )
    {
        # タイトル商品 一覧
        $machines = $gacha_title->machines;


        return view('manuf_admin.gacha_title.machine.index', compact(
            'gacha_title','machines',
        ));
    }

}
