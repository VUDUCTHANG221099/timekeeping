<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class dashboardController extends Controller
{
    /**
     * indexEmployee Get view dashboard employee
     * indexAdmin Get view dashboard Admin
     *
     */
    public function index()
    {
        try {
            if (Gate::allows('policy', Auth::user())) {
                return view('Dashboard.admin.index');
            } else {
                return view('Dashboard.employee.index');
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function indexEmployee()
    {
    }
    public function indexAdmin()
    {
    }
}
