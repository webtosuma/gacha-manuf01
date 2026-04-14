<?php

namespace App\Http\Controllers\Manuf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Manuf\AdminGachaTitleMachineRequest;
use App\Models\ManufGachaTitle;
use App\Models\ManufGachaTitleMachine;
use App\Services\Manuf\GachaTitleMachineService;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル 筺体 コントローラー
| =============================================
*/
class AdminGachaTitleMachineController extends Controller
{
    /** サービスの登録 */
    public function __construct(GachaTitleMachineService $service)
    {
        $this->service = $service;
    }


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


    /**
     * 詳細
     *
     * @param  ManufGachaTitle $gacha_title
     * @param  ManufGachaTitleMachine $machine
     * @return \Illuminate\Http\Response
     */
    public function show(
        ManufGachaTitle $gacha_title ,
        ManufGachaTitleMachine $machine
    ){
        return view('manuf_admin.gacha_title.machine.show', compact(
            'gacha_title','machine',
        ) );
    }



    /**
     * 新規作成
     *
     * @param  ManufGachaTitle $gacha_title
     * @return \Illuminate\Http\Response
     */
    public function create(ManufGachaTitle $gacha_title)
    {
        # 新規作成モデル
        $machine = new ManufGachaTitleMachine();


        return view('manuf_admin.gacha_title.machine.create', compact(
            'gacha_title','machine'
        ) );
    }



        /**
         * 登録
         *
         * @param  AdminGachaTitleMachineRequest $request
         * @param  ManufGachaTitle $gacha_title
         * @return \Illuminate\Http\Response
        */
        public function store (
            AdminGachaTitleMachineRequest $request,
            ManufGachaTitle $gacha_title
        ){
            # 登録サービス
            $this->service->store($request, $gacha_title);

            $request->session()->regenerateToken();// 二重送信防止

            return redirect()
            ->route('admin.gacha_title.machine', $gacha_title)
            ->with(['alert-success' => '筐体を新規登録しました']);
        }



    /**
     * 編集
     *
     * @param  ManufGachaTitle $gacha_title
     * @param  ManufGachaTitleMachine $machine
     * @return \Illuminate\Http\Response
     */
    public function edit(
        ManufGachaTitle $gacha_title ,
        ManufGachaTitleMachine $machine
    ){

        return view('manuf_admin.gacha_title.machine.edit', compact(
            'gacha_title','machine',
        ) );
    }


        /**
         * 更新
         *
         * @param  AdminGachaTitleMachineRequest $request
         * @param  ManufGachaTitle $gacha_title
         * @param  ManufGachaTitleMachine $machine
         * @return \Illuminate\Http\Response
         */
        public function update(
            AdminGachaTitleMachineRequest $request,
            ManufGachaTitle $gacha_title,
            ManufGachaTitleMachine $machine
        ){
            # 更新サービス
            $this->service->update($request, $machine);

            $request->session()->regenerateToken();// 二重送信防止

            return redirect()
            ->route('admin.gacha_title.machine', $gacha_title)
            ->with(['alert-warning' => '筐体を更新しました']);
        }


    /**
     * 削除
     *
     * @param  Request $request
     * @param  ManufGachaTitle $gacha_title
     * @param   ManufGachaTitleMachine $machine
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        ManufGachaTitle $gacha_title,
         ManufGachaTitleMachine $machine,
    ){
        # DBデータの論理削除
        $this->service->delete($request, $machine);

        $request->session()->regenerateToken();// 二重送信防止

        return redirect()
        ->route('admin.gacha_title.machine', $gacha_title)
        ->with(['alert-danger'=>'筐体を1件削除しました']);
    }



    /**
     * コピー
     *
     * @param  Request $request
     * @param  ManufGachaTitle $gacha_title
     * @param   ManufGachaTitleMachine $machine
     * @return \Illuminate\Http\Response
     */
    public function copy(
        Request $request,
        ManufGachaTitle $gacha_title,
         ManufGachaTitleMachine $machine
    ){
        $this->service->copy($machine, $gacha_title);

        $request->session()->regenerateToken();

        return redirect()
        ->route('admin.gacha_title.machine', $gacha_title)
        ->with(['alert-warning' => '筐体をコピーしました']);
    }


}
