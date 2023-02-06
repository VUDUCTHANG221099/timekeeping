<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "birthday",
        "phone",
        "address",
        "gender",
        'salary',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'user_position', 'user_id', 'position_id');
    }
    public function tabletUser()
    {
        return $this->hasOne(TabletUser::class);
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'plan', 'user_id', 'project_id')
            ->select('*')
            ->orderByDesc('day_addEmp');
    }
    public function employeeProjects()
    {
        return $this->belongsToMany(Project::class, "employee_project", "user_id", "project_id")
            ->orderByDesc('day_work')->select('day_work');
    }
    public function routeNotificationForSlack($notification)
    {
        return config("app.slack_webhook");
    }
}
