<?php

namespace App\Http\Controllers;

use App\Models\SeriesType;
use Illuminate\Http\Request;

class SeriesTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seriesTypes = SeriesType::all();
        return response()->json(['seriesTypes' => $seriesTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SeriesType $seriesType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeriesType $seriesType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeriesType $seriesType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeriesType $seriesType)
    {
        //
    }
}
