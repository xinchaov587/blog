<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    // 用户登录-渲染
    public function create()
    {
        return view('sessions.create');
    }

    // 用户登录-逻辑
    public function store(Request $request)
    {
        $credentials =  $this->validate($request, [
            'email' => 'required|email|max:50',
            'password' => 'required|between:6,12'
        ]);

        $result = Auth::attempt($credentials, $request->has('remember'));

        if ($result) {
            session()->flash('success', '欢迎回来');
            return redirect()->route('users.show', [Auth::user()]);
        } else {
            session()->flash('danger', '账号或密码错误');
            return redirect()->back();
        }
    }

    // 登出行为
    public function logout()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出');
        return redirect('login');
    }
}
