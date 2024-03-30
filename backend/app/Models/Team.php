<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'team_leader_id'];

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'team_project');
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_team', 'team_id', 'user_id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    public function assignEmployee($userId)
    {
        $this->employees()->attach($userId);
    }

    public function removeEmployee($userId)
    {
        $this->employees()->detach($userId);
    }
}
