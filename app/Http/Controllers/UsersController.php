<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function __construct()
    {
        // 限制只能访问自己账号对应的资料修改页面
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);

        // 限制已登录用户不能访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    
    // 列出所有用户
    public function index()
    {
        $users = User::orderBy('id')->paginate(10) ;
        return view('users.index', compact('users'));
    }
    
    // 用户注册页-渲染
    public function create()
    {
        return view('users.create');
    }

    // 用户注册-逻辑
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|between:2,12',
            'email' => 'required|max:50|unique:users|email',
            'password' => 'required|between:6,12|confirmed',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        $this->sendEmailConfirmationTo($user);

        session()->flash('success', '验证邮件已发送到您的注册邮箱, 请注意查收');
        return redirect('/');
    }

    // 个人资料页-渲染
    public function show(User $user)
    {
        $statuses = $user->statuses()
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        return view('users.show', compact('user', 'statuses'));
    }
    
    // 个人资料编辑-渲染
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }
    
    // 个人资料编辑-逻辑
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|between:2,12',
            'password' => 'nullable|between:6,12|confirmed'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success', '个人资料更新成功');
        return redirect()->route('users.show', $user->id);
    }

    // 删除用户操作
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户!');
        return back();
    }

    protected function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Blog 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    //  用户激活
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        echo 'sadfasd';
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = '粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
