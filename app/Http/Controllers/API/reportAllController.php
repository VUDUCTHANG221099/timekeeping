<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\employeeProject;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class reportAllController extends Controller
{
    /**
     * Summary of index
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     * @description code API the projects of employees follow month
     */
    public function index($data)
    {
        // return $this->indexAll($data, 9);
        $yearAndMonth = explode('-', $data);
        $list = [];
        $user = User::all();
        $data = [];
        foreach ($user as $key => $value) {
            if ($this->indexAll($yearAndMonth[0], $yearAndMonth[1], $value->id)) {
                $list[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'name_day_hours' => $this->indexAll($yearAndMonth[0], $yearAndMonth[1], $value->id),
                ];
            }
        }
        if (count($list) > 0) {
            $data = [
                'list' => $list,
                'status' => 200,
            ];
        } else {
            $data = [
                'list' => 0,
                'status' => 500,
            ];
        }
        return response()->json($data);
    }
    public function indexAll($year, $month, $id)
    {
        try {
            // $yearAndMonth = explode('-', $data);
            $listProject = [];
            $projects = employeeProject::whereMonth('day_work', '=', $month)->with('project')
                ->whereYear('day_work', '=', $year)->where('user_id', '=', $id)->select('project_id', 'user_id')
                ->distinct()->get();
            $employeeProject = employeeProject::whereMonth('day_work', '=', $month)
                ->whereYear('day_work', '=', $year)->where('user_id', '=', $id)->
                get();
            // dd($employeeProject->toArray());
            if (count($projects) > 0 and count($employeeProject) > 0) {
                $days = [];
                for ($i = 0; $i < count($projects); $i++) {
                    for ($j = 0; $j < count($employeeProject); $j++) {
                        if ($projects[$i]->project_id === $employeeProject[$j]->project_id) {
                            $days[] = [
                                'day_work' => (int) Carbon::parse($employeeProject[$j]->day_work)->translatedFormat('d'),
                                'hours' => $employeeProject[$j]->working_hours,
                            ];
                            $listProject[$i] = [
                                'id' => $projects[$i]->user_id,
                                // 'name' => $user[$k]->user->name,
                                'nameProject' => $projects[$i]->project->name,
                                // 'idProject' => $projects[$i]->project_id,
                                'days' => $days
                            ];
                        }
                    }
                    $days = [];
                }
                return $listProject;
            } else {
                return 0;
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
