<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeeProject extends Model
{
    use HasFactory;
    protected $table = 'employee_project';
    protected $fillable = [
        "*"
    ];
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function Project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
