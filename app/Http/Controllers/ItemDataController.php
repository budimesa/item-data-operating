<?php

namespace App\Http\Controllers;

use App\Models\ItemData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemDataController extends Controller
{
    public function index()
    {
        $itemDatas = ItemData::all();
        return response()->json(['itemDatas' => $itemDatas]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'qty_safty' => 'required|numeric',
            'safety_m' => 'required|numeric',
            'dol_trade' => 'required|numeric',
            'price_type' => 'required|string',
            'std_cost' => 'required|numeric',
            'qty_std_l' => 'required|numeric',
            'durability' => 'required|numeric',
            'qty_pack' => 'required|numeric',
            'std_wgt' => 'required|numeric',
            'size_code' => 'required|string',
            'unit_po' => 'required|string',
            'vend_proc' => 'required|string',
            'unit_stk' => 'required|string',
            'item_name' => 'required|string',
            'item_code' => 'required|string',
            'item_type' => 'required|string',
            'item_spec' => 'required|string',
            'brand_code' => 'required|string',
            'unit_pr' => 'required|string',
            'lead_time' => 'required|numeric',
            'clr_tol_code' => 'required|string',
            'unit_prod' => 'required|string',   
            'item_grade' => 'required|string',
            'series_type' => 'required|string',
            'unit_sales' => 'required|string',
            'phanton' => 'required|string',
            'unit_usg' => 'required|string',
            'color_code' => 'required|string',
            'density_code' => 'required|string',
            'size_tol_code' => 'required|string',
            'qty_min' => 'required|numeric',
            'level_code' => 'required|string',
            'created_by' => 'required|string',
            'updated_by' => 'required|string',
            'usage_to' => 'required|string',
            'plus_minus_percentage' => 'required|numeric',
            'standar_plus_minus' => 'required|numeric',
            'kw_1_xx_percentage' => 'required|numeric',
        ]);

        $data = $request->validated();
        $data['created_by'] = Auth::id(); // Ambil ID pengguna yang sedang login

        ItemData::create($data);

        return response()->json([
            'message' => 'Item Data created successfully!',
            'itemData' => $data,
        ]);
    }

}
