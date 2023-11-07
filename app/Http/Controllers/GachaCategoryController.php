<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGachaCategoryRequest;
use App\Http\Requests\UpdateGachaCategoryRequest;
use App\Models\GachaCategory;

class GachaCategoryController extends Controller
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
     * @param  \App\Http\Requests\StoreGachaCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGachaCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GachaCategory  $gachaCategory
     * @return \Illuminate\Http\Response
     */
    public function show(GachaCategory $gachaCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GachaCategory  $gachaCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(GachaCategory $gachaCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGachaCategoryRequest  $request
     * @param  \App\Models\GachaCategory  $gachaCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGachaCategoryRequest $request, GachaCategory $gachaCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GachaCategory  $gachaCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(GachaCategory $gachaCategory)
    {
        //
    }
}
