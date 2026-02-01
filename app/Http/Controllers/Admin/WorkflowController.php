<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow;

class WorkflowController extends Controller
{
    public function index()
    {
        $workflows = Workflow::with(['user'])->latest()->paginate(20);
        return view('admin.workflows.index', compact('workflows'));
    }

    public function destroy(Workflow $workflow)
    {
        $workflow->delete();
        return back()->with('success', 'Workflow deleted.');
    }
}
