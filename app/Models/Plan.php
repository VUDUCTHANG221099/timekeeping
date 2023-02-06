<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'plan';
    protected $fillable = ["*"];
    public function Project()
    {
        return $this->hasMany(Project::class, 'id', 'project_id');
    }
}
