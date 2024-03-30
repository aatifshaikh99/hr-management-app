<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function index()
    {
        $users = User::with('team:id,name')->get(); // Eager load the team relationship with only the necessary columns (id, name)
        return response()->json(['users' => $users]);
    }

    public function getProfile(Request $request)
    {
        $employee = $request->user(); // Get logged-in employee
        return response()->json($employee);
    }

    public function updateProfile(Request $request)
    {
        $employee = $request->user(); // Get logged-in employee
        $employee->update($request->all()); // Update employee's profile
        return response()->json(['message' => 'Profile updated successfully', 'data' => $employee]);
    }

    public function updateEmployee(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:developer,HR',
            'skills' => 'required|string',
        ]);

        // Find the employee by ID
        $employee = User::findOrFail($id);

        // Update employee details
        $employee->update($validatedData);

        // Optionally, return a response or redirect
        return response()->json(['message' => 'Employee updated successfully']);
    }

    public function deleteEmployee($id)
    {
        // Find the employee by ID
        $employee = User::findOrFail($id);

        // Delete the employee
        $employee->delete();

        // Optionally, return a response or redirect
        return response()->json(['message' => 'Employee deleted successfully']);
    }

    public function dashboardStats()
    {
        $projectCount = Project::count();
        $teamCount = Team::count();
        $employeeCount = User::count();

        return response()->json([
            'project_count' => $projectCount,
            'team_count' => $teamCount,
            'employee_count' => $employeeCount
        ]);
    }
}
