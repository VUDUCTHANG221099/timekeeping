<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Position;
use App\Repositories\PositionRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class positionController extends Controller
{
    /**
     * Functions index=>View list Position
     * Functions create =>Add Position [viewCreate|create]
     * Functions update=>Update Position [viewUpdate|update]
     * Functions destroy=>Destroy Position
     */
    protected $position;
    public function __construct(PositionRepository $positionRepository)
    {
        $this->position = $positionRepository;
    }
    public function index()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    'position' => $this->position->all(),
                ];
                return view('position', $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewCreate()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                return view('position.add_update');
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function create(PositionRequest $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    'namePosition' => $request->namePosition,
                    "display_name" => $request->display_name,
                ];
                $this->position->create($data['namePosition'], $data['namePosition']);
                session()->flash('success', 'Thêm thành công!');
                return redirect()->route('position.get.all');
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function viewUpdate($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $data = [
                    "position" => $this->position->getPosition($id),
                ];
                return view("position.add_update", $data);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function update(PositionRequest $request)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                Position::where("id", $request->id)->update([
                    'display_name' => $request->display_name
                ]);
                $this->position->update($request->id, $request->display_name);
                session()->flash('success', 'Cập nhật thành công!');
                return redirect()->route("position.get.all");
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function destroy($id)
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                $this->position->destroy($id);
                session()->flash('success', 'Xóa thành công!');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
