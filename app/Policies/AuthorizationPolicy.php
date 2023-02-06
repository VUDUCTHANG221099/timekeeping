<?php

namespace App\Policies;

use App\Models\TabletUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AuthorizationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    public function index(User $user){
        $user=User::where('id',$user->id)->with("Roles")->first();
        if($user->roles[0]->id=="1"){
            return true;
        }else{
            return false;
        }
    }
    public function employee(User $user){
        $user=User::where('id',$user->id)->with("Roles")->first();
        if($user->roles[0]->id=="2"){
            return true;
        }else{
            return false;
        }
    }
}
