<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infomation;
use Illuminate\Support\Facades\Auth;
/*
| =============================================
|  サイト管理者　お知らせ コントローラー
| =============================================
*/
class AdminInfomationController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $infomations = Infomation::orderByDesc('created_at')
        ->get();

        return view('admin.infomation.index', compact('infomations'));
    }


    /**
     * 表示
     *
     * @param  \App\Models\Infomation $infomation
     * @return \Illuminate\Http\Response
     */
    public function show( Infomation $infomation )
    {
        return view('admin.infomation.show', compact('infomation'));
    }

    /**
     * 新規作成
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $infomation = new Infomation();

        return view('admin.infomation.create', compact('infomation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
