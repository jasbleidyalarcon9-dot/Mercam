<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function create(){
       return view('auth.register');

    }
    public function store(Request $request){
        $validate = $request -> validate(['name' => 'required|string|max:80',
                               'email'=> 'required|email|unique:users,email',
                              'password'=> 'required|min:8|confirmed',
                              'terms' => 'accepted',
    ]);
    

    $user = User:: create([
         'name' => $validate['name'],
         'email' => $validate['email'],
         'password' => Hash::make($validate['password']),

    ]);
    auth()->login($user);
    return redirect()->route('dashboard');
}
}
