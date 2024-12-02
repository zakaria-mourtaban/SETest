<?php

namespace App\Http\Controllers;

use App\Models\UserProject;
use Illuminate\Http\Request;

class UserProjectController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'integer',
            'project_id' => 'integer',
        ]);

        $userProject = UserProject::create($validated);

        return response()->json([
            'message' => 'relationship created successfully.',
            'user_project' => $userProject,
        ], 201);
    }

    public function read(Request $request)
    {
        // Fetch all user-project relationships
        $userProjects = UserProject::all();

        return response()->json($userProjects, 200);
    }

    public function readById($id)
    {
        $userProject = UserProject::find($id);

        if (!$userProject) {
            return response()->json(['message' => 'User-Project relationship not found'], 404);
        }

        return response()->json($userProject, 200);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'integer|exists:users,id',
            'project_id' => 'integer|exists:projects,id',
        ]);

        $userProject = UserProject::find($id);

        if (!$userProject) {
            return response()->json(['message' => 'User-Project relationship not found'], 404);
        }

        $userProject->update($validated);

        return response()->json([
            'message' => 'User-Project relationship updated successfully.',
            'user_project' => $userProject,
        ], 200);
    }

    public function destroy($id)
    {
        $userProject = UserProject::find($id);

        if (!$userProject) {
            return response()->json(['message' => 'User-Project relationship not found'], 404);
        }

        $userProject->delete();

        return response()->json(['message' => 'User-Project relationship deleted successfully.'], 200);
    }
}
