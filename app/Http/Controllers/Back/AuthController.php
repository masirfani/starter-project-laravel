<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function login(){
        // dd(session('alert')['type']);
        return view('auth.login');
    }

    function profile(){
        return view('auth.profile');
    }

    function authentication(Request $request){
        $validate = $request->validate([
            'email'    => 'required',
            'password' => 'required'
        ]);
    
        if (Auth::attempt($validate)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return redirect()->route('auth.login')->with('alert', ["type" => "error", "text" => "email / password yang anda masukkan salah"]);
    }

    function register(){
        return view('auth.register');
    }

    function store(Request $request){
        $validate = $request->validate([
            'name'     => 'required',
            'email'    => 'required|unique:users',
            'password' => 'required|min:8'
        ]);

        User::create($validate);

        return redirect()->route('auth.login')
        ->with('alert', ["type" => "success", "text" => "Selamat akun anda sudah terdaftar, silahkan login"]);
    }

    function update(Request $request){
        // dd($request->all());
        $validate = $request->validate([
            'name'     => 'required',
            'email'    => 'required|unique:users,email,'.Auth::user()->id,
            'password' => 'nullable|min:8',
            'picture' => 'nullable|image|mimes:jpg,png,jpeg',
        ]);

        if (empty($request->password)) {
            unset($validate['password']);
        }else{
            $validate['password'] = password_hash($request->password, PASSWORD_BCRYPT);
        }

        if (empty($request->picture)) {
            unset($validate['picture']);
        }else{

            $file_path = public_path("uploads/user/".Auth::user()->picture);
            if (file_exists($file_path) && Auth::user()->picture !== "default.png") {
                unlink($file_path);
            }

            $file      = $request->picture;
            $file_name = "p-".$request->email.".".$file->getClientOriginalExtension();
            $file->move(public_path("uploads/user/"), $file_name);
            $validate['picture'] = $file_name;
        }

        User::find(Auth::user()->id)->update($validate);

        return redirect()->route('auth.profile')
        ->with('alert', ["type" => "success", "text" => "Akun berhasil dirubah"]);
    }


    public function logout()
    {
        Auth::logout();
        
        return redirect()->route('auth.login');
    }
}
