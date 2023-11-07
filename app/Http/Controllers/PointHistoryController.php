<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePointHistoryRequest;
use App\Http\Requests\UpdatePointHistoryRequest;
use App\Models\PointHistory;

class PointHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
