<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function index(){
        return view('auth');
    }

    public function login_submit(Request $request){
        request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $credentials = $request->only('email', 'password');
        $remember = $request['remember'];
        if (Auth::attempt($credentials,$remember)) {
            // Authentication passed...
           // return redirect()->intended('dashboard');
            return 'success';
        }else{
            return 'fail';
        }

    }
    public function register(Request $request)
    {
        request()->validate([
            'shop_name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed'

        ]);
            $password = bcrypt($request->password);

         $user = User::create([
            'shop_name' => $request->shop_name,
            'email' => $request->email,
            'password' => $password
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials,true)) {
            // Authentication passed...
            // return redirect()->intended('dashboard');
            return 'success';
        }else{
            return 'fail';
        }

    }

    public function getUser(){
        $id = Auth::id();
       // $user = User::findOrFail(1);
        return $user = User::find($id, ['id', 'email']);
    }
    public function updateProfile(Request $request,$id){
        $this->authorize('isAdmin');
        User::where('id', $id)
            ->update([
                'shop_name' => $request->shop_name
            ]);

    }


    public function __construct()
    {
        $this->middleware('guest')->except(['logout','getUser','updateProfile','register']);
    }
}
