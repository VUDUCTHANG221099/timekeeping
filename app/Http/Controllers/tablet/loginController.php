<?php
namespace App\Http\Controllers\tablet;

use App\Http\Controllers\Controller;
use App\Models\TabletUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TheSeer\Tokenizer\Exception;

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
            return view("tablet.login");
        } catch (\Throwable $th) {
            abort(500);
        }
    }
    public function login(Request $request)
    {

        try {
            $data = ['email' => $request->emp_code, 'password' => $request->password];
            /**Check user đã bị nghỉ việc hay chưa */
            $check = TabletUser::where('email', $data['email'])->first();

            if (isset($check) and $check->user->status == "0") {
                $request->session()->flash('error', 'Nhập không đúng vui lòng thử lại!');
                return redirect()->route('tabletLoginGet');
            }

            /**Check user đã bị nghỉ việc hay chưa */

            if (Auth::guard('tablet_users')->attempt($data)) {
                return redirect()->route('tabletCheckoutGet');
            } else {
                $request->session()->flash('error', 'Nhập không đúng vui lòng thử lại!');
                return redirect()->route('tabletLoginGet');
            }
        } catch (\Exception $e) {
            abort(500);
        }
    }
    public function logout()
    {
        try {
            Auth::guard('tablet_users')->logout();
            return redirect()->route('tabletLoginGet');
        } catch (\Throwable $th) {
            abort(500);
        }
    }
}
