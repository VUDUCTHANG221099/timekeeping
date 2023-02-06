<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\SoftDeletes;
class Project extends Model
{
    use HasFactory;
    // SoftDeletes;
    protected $fillable = [
        'name',
        // 'start_project',
        // 'end_project',
        'description',
        'status',
        // 'menber',
        // 'cost',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, "plan", "project_id", "user_id")->select('*')
            ->orderBy('day_addEmp', 'desc');
    }
}
