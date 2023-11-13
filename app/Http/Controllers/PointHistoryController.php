<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PointHistory;
/*
| =============================================
|  ポイント購入履歴 コントローラー
| =============================================
*/
class PointHistoryController extends Controller
{

    /** ログイン中のみ処理可能 @return void */
    public function __construct(){ $this->middleware('auth');}

    /**
     * ポイント購入履歴 一覧
     * @param String $month
     * @return \Illuminate\View\View
     */
    public function index( $month='' )
    {
        $user = Auth::user();
        $point_histories = PointHistory::where('user_id',$user->id)
        ->orderByDesc('id')
        ->get();



        $totalPoints = $user->point_histories->sum('value');
        // dd($totalPoints);





        return view('point_history.index',compact('point_histories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePointHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePointHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PointHistory  $pointHistory
     * @return \Illuminate\Http\Response
     */
    public function show(PointHistory $pointHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PointHistory  $pointHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(PointHistory $pointHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePointHistoryRequest  $request
     * @param  \App\Models\PointHistory  $pointHistory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePointHistoryRequest $request, PointHistory $pointHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PointHistory  $pointHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PointHistory $pointHistory)
    {
        //
    }
}
