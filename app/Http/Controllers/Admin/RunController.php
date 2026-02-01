<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ToolRun;

class RunController extends Controller
{
    public function index()
    {
        $runs = ToolRun::with(['user', 'tool'])->latest()->paginate(20);
        return view('admin.runs.index', compact('runs'));
    }

    public function show(ToolRun $run)
    {
        return view('admin.runs.show', compact('run'));
    }
}
