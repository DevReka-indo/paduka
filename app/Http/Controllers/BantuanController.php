<?php

namespace App\Http\Controllers;

use App\Models\Changelog;

class BantuanController extends Controller
{
    public function index()
    {
        $changelogs = Changelog::with('items')
            ->where('is_published', true)
            ->orderByDesc('tanggal_rilis')
            ->get();

        return view('bantuan.index', compact('changelogs'));
    }
}
