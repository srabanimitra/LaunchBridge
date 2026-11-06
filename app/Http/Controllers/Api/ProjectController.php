<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Project::with('startup:id,name')->where('status', 'open')->latest()->paginate(10));
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($project->load('startup:id,name'));
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->role === 'startup', 403, 'Only startups can post projects.');
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'skills' => ['required', 'array', 'min:1', 'max:10'],
            'skills.*' => ['required', 'string', 'max:50'],
            'compensation' => ['required', 'string', 'max:200'],
        ]);
        $project = $request->user()->projects()->create($data + ['status' => 'open']);
        return response()->json(['project' => $project], 201);
    }
}
