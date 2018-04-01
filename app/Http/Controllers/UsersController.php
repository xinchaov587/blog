<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // 用户注册页-渲染
    public function create()
    {
        return view('users.create');
    }

    // 用户注册逻辑
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|between:5,12',
            'email' => 'required|max:50|unique:users|email',
            'password' => 'required|between:6,12|confirmed',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        session()->flash('success', '恭喜你注册成功');
        return redirect()->route('users.show', [$user]);
    }

    // 个人资料页-渲染

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
