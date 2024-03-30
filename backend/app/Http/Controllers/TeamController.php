<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teamsQuery = Team::query()->with('teamLeader', 'employees');

        // For normal employees, filter teams based on their presence in the team
        if ($user->role !== 'HR') {
            $teamsQuery->whereHas('employees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $teams = $teamsQuery->get();

        $teams->each(function ($team) {
            $team->member_count = $team->employees->count();
        });

        return response()->json(['teams' => $teams]);
    }


    public function createTeam(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'team_leader_id' => 'nullable|exists:users,id',
            // Add validation for employee_ids as an array
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:users,id',
        ]);

        // Create the team
        $team = Team::create([
            'name' => $validatedData['name'],
            'team_leader_id' => $validatedData['team_leader_id'],
        ]);

        // Attach employees to the team
        $team->employees()->attach($validatedData['employee_ids']);

        return response()->json($team, 201);
    }

    public function assignEmployeeToTeam(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'team_id' => 'required|exists:teams,id',
        ]);

        $team = Team::findOrFail($validatedData['team_id']);
        $team->employees()->attach($validatedData['user_id']);

        return response()->json(['message' => 'Employee assigned to team successfully']);
    }

    public function assignProjectToTeam(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($validatedData['project_id']);
        $project->employees()->attach($validatedData['user_id']);

        return response()->json(['message' => 'Project assigned to employee successfully']);
    }

    public function assignTeamToProject(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $teamId = $request->input('team_id');
        $projectId = $request->input('project_id');

        $team = Team::findOrFail($teamId);
        $project = Project::with('teams')->findOrFail($projectId);

        // Check if the team is already assigned to the project
        if ($project->teams && $project->teams->contains($team)) {
            return response()->json(['message' => 'Team is already assigned to the project'], 400);
        }

        // Associate the team with the project
        $project->teams()->attach($team);

        return response()->json(['message' => 'Team assigned to project successfully'], 200);
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
