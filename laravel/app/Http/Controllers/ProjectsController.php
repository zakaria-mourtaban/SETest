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

			$users = \DB::table('user_projects')
				->join('users', 'user_projects.user_id', '=', 'users.id')
				->where('user_projects.project_id', $project->id)
				->select('users.id', 'users.name', 'users.email')
				->get();

			return response()->json([
				'project' => $project,
				'members' => $users
			], 200);
		}

		$projects = Project::all();
		foreach ($projects as $project) {
			$project->members = \DB::table('user_projects')
				->join('users', 'user_projects.user_id', '=', 'users.id')
				->where('user_projects.project_id', $project->id)
				->select('users.id', 'users.name', 'users.email')
				->get();
		}

		return response()->json($projects, 200);
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
