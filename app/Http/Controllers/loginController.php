<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class loginController extends Controller
{
    /**
     * Functions View Login=>index
     * Functions Submit Login=>login
     * Functions logout=>logout
     *
     */

    public function index()
    {
        try {
            return view("login");
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function login(loginRequest $request)
    {
        try {
            $data = [
                'email' => Str::of($request->email)->trim(),
                'password' => Str::of($request->password)->trim(),
            ];
            if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                return redirect()->route('dashboard.get');
            }
            $user = User::where(['email' => $data['email']])->first();
            if (isset($user)) {
                if (!Hash::check($request->password, $user->password)) {
                    $request->session()->flash('message', 'Tài khoản hoặc mật khẩu chưa đúng!');
                    $request->session()->flash('email', $data['email']);
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('message', 'Tài khoản hoặc mật khẩu chưa đúng!');
                $request->session()->flash('email', $data['email']);
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function logout()
    {
        try {
            Auth::logout();
            return redirect()->route("login.get");
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
