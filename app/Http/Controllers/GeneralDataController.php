<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeneralDataController extends Controller
{
    public function index()
    {
        return inertia('Posts/Index', [
            // 'posts' => Post::all(),
        ]);
    }

}