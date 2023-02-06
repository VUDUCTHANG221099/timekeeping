<?php
namespace App\Repositories;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectRepository
{
    protected $project;
    public function __construct()
    {
        $this->project = new Project();
    }
    public function all()
    {
        return $this->project->orderBy("name", "ASC")->get();
    }
    public function create($data)
    {
        $project = new Project();
        $project->create($data);
        // $project = new Project();
        // $project->create([
        //     'name' => Str::of($name_project)->trim(),
        //     'start_project' => $start_project,
        //     'end_project' => $end_project ?? null,
        //     'description' => Str::of($description)->trim() ?? null,
        //     'status' => $status,
        //     'menber' => $menber,
        //     'cost' => Str::of($cost)->trim(),
        // ]);
    }
    public function update($id, $name_project, $description, $status)
    //$start_project, $end_project,$menber,$cost

    {
        $this->project->where('id', $id)->update([
            'name' => Str::of($name_project)->trim(),
            // 'start_project' => $start_project,
            // 'end_project' => $end_project ?? null,
            'description' => Str::of($description)->trim() ?? null,
            'status' => $status,
            // 'menber' => $menber,
            // 'cost' => Str::of($cost)->trim(),
        ]);
    }
    public function getProject($id)
    {
        return $this->project->where('id', $id)->first();
    }
    public function AddEmpProject($employee, $project)
    {
        foreach ($employee as $key => $value) {
            DB::table('plan')->insert([
                'status_plan' => "1",
                'project_id' => $project,
                'user_id' => $value,
                'day_addEmp' => Carbon::now(),
            ]);
        }
    }
    public function destroy($id)
    {
        $this->project->where('id', $id)->delete();
    }
    // public function ProjectPlan()
    // {
    //     return Project::with("plans")->get();
    // }
    public function countEmpProjects()
    {
        return DB::table('plan')->count();
    }
}
