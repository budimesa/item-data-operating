<?php

namespace App\Http\Controllers;

use App\Models\ColorTol;
use Illuminate\Http\Request;

class ColorTolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colorTols = ColorTol::all();
        return response()->json(['colorTol' => $colorTols]);
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
    public function show(ColorTol $colorTol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ColorTol $colorTol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ColorTol $colorTol)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ColorTol $colorTol)
    {
        //
    }
}
