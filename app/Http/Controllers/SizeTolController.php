<?php

namespace App\Http\Controllers;

use App\Models\SizeTol;
use Illuminate\Http\Request;

class SizeTolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizeTols = SizeTol::all();
        return response()->json(['sizeTol' => $sizeTols]);
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
    public function show(SizeTol $sizeTol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SizeTol $sizeTol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SizeTol $sizeTol)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SizeTol $sizeTol)
    {
        //
    }
}
