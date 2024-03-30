<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'details', 'status'];

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_project', 'project_id', 'user_id');
    }

    protected $guarded = [];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_project');
    }

    public function teamLeader()
    {
        return $this->belongsToMany(User::class, 'teams', 'project_id', 'team_leader_id');
    }
}
