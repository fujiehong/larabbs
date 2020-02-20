<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    public function __construct()
    {
        //中间件：except 方法来设定 指定动作 不使用 Auth 中间件进行过滤，
        //意为 —— 除了此处指定的动作以外，所有其他动作都必须登录用户才能访问，类似于黑名单的过滤机制。
        //only 方法  白名单方法，将只过滤指定动作。
        //我们提倡在控制器 Auth 中间件使用中，首选 except 方法，这样的话，当你新增一个控制器方法时，默认是安全的，此为最佳实践。
        $this->middleware('auth',['except'=>['show']]);
    }
    public function show(User $user)
    {

        return view('users.show',compact('user'));
    }

    public function edit(User $user)
    {
        //policy 判断用户身份是不是自已，
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    public function update(UserRequest $request,User $user,ImageUploadHandler $uploader)
    {

        //policy 判断用户身份是不是自已，
        $this->authorize('update', $user);
        $data=$request->all();

        if ($request->avatar)
        {
            $result=$uploader->save($request->avatar,'avatar',$user->id,416);
            if($result)
            {
                $data['avatar']=$result['path'];
            }


        }
        $user->update($data);

        return redirect()->route('users.show',$user->id)->with('success','个人信息资料更新成功');
    }
}
