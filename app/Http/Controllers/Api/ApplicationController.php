<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Project;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function store(Request $request, Project $project): JsonResponse
    {
        abort_unless($request->user()->role === 'graduate', 403, 'Only graduates can apply.');
        abort_unless($project->status === 'open', 409, 'This project is closed.');
        $data = $request->validate(['message' => ['required', 'string', 'min:10', 'max:2000']]);
        if ($project->applications()->where('graduate_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'Already applied to this project.'], 409);
        }
        try {
            $application = $project->applications()->create([
                'graduate_id' => $request->user()->id,
                'message' => $data['message'],
                'status' => 'pending',
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json(['message' => 'Already applied to this project.'], 409);
        }
        return response()->json(['application' => $application], 201);
    }

    public function index(Request $request, Project $project): JsonResponse
    {
        abort_unless($request->user()->id === $project->startup_id, 403, 'Not your project.');
        return response()->json($project->applications()->with('graduate:id,name,email')->latest()->paginate(10));
    }

    public function updateStatus(Request $request, Application $application): JsonResponse
    {
        abort_unless($request->user()->id === $application->project->startup_id, 403, 'Not your application to review.');
        $data = $request->validate(['status' => ['required', Rule::in(['accepted', 'rejected'])]]);
        abort_unless($application->status === 'pending', 409, 'This application was already reviewed.');
        $application->update(['status' => $data['status']]);
        return response()->json(['application' => $application]);
    }
}
