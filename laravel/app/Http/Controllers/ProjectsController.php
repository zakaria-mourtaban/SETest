<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string',
            'description' => 'string',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'message' => 'Project created successfully.',
            'project' => $project,
        ], 201);
    }

    public function read(Request $request)
    {
        if ($request->has('id')) {
            $project = Project::find($request->input('id'));

            if (!$project) {
                return response()->json(['message' => 'Project not found'], 404);
            }

            return response()->json($project, 200);
        }

        return response()->json(Project::all(), 200);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'integer',
            'name' => 'string',
            'description' => 'string',
        ]);

        $project = Project::find($validated['id']);
        $project->update($validated);

        return response()->json([
            'message' => 'Project updated successfully.',
            'project' => $project,
        ], 200);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'integer',
        ]);

        $project = Project::find($validated['id']);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }
}
