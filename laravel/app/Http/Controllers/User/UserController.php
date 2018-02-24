<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Friend;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //显示个人管理界面
    public function displayInfo(User $user){
        //sql查询条件
        $map = [
        'user_id' => session('user_id')
        ];
        //从数据库获得结果集以数组形式返回
        $info = $user->get($map)->toArray();
        // pd($info);
        //若有该用户数据，显示页面
        if($info){
            $assign = $info[0];            
            return view('User/displayInfo',['assign'=>$assign]);
        }

        
    }
    //登录操作
    public function login(User $user,Request $request){
        //验证输入数据是否合法
        $this->validate($request,[
            'user_id'=>'required',
            'user_password'=>'required'
            ]);

        //获取所有请求数据
        $input = $request->all();
        //sql查询条件
        $map = [
            'user_id'=>$input['user_id']
        ];
        //从数据库获得结果集
        $info = $user->get($map);
        // pd($hashArr);
        // 获得数据库存的密码加密值hash
        $hash = !$info->isEmpty() ? $info[0]['user_password'] : null ;
        //对用户输入的密码与加密值比较验证
        if( $hash && password_verify($input['user_password'],$hash)){
            //以session形式保存用户信息
            session([
                'user_id'=>$input['user_id'],
                'user_name'=>$info[0]['user_name'],
                'user_profile'=>'uploads/user_profile/'.$info[0]['user_profile']
                ]);
            //显示页面
            return response()->json(['flag'=>true]);
        }else{
            return response()->json(['flag'=>false]);
        }
    }

    public function logout(){
        //清空session
        session()->flush();
        return redirect('/');
    }

    //添加用户
    public function add(User $user,Request $request){
        //验证输入数据是否合法
        $this->validate($request,[
            'user_id'=>'required',
            'user_name'=>'required',
            'user_password'=>'required',
            'user_email'=>'required|email'
            ]);    

        //获取所有请求数据
        $input = $request->all();
        //获取指定请求数据
        $file = $request->user_profile;    
        //要添加的数据
        $map = [
        'user_id'=>$input['user_id'],
        'user_name'=>$input['user_name'],
        'user_password'=>password_hash($input['user_password'],PASSWORD_DEFAULT),
        'user_email'=>$input['user_email'],
        ];
        // 判断头像是否上传成功
        if($request->hasFile('user_profile') && $file->isValid()){
            //头像存储文件夹
            $destinatePath = 'uploads/user_profile';
            //获得上传文件的文件名
            $file_name = $input['user_id'].'&&'.$file->getClientOriginalName();
            //从上传临时位置转移到指定位置
            $file->move($destinatePath,$file_name);
            //用户信息要保存的头像文件名
            $map['user_profile'] = $file_name;
        }
        //添加操作
        $re = $user->add($map);                
        //判断是否添加成功
        if($re){
            //存储用户信息
            session([
                'user_id'=>$input['user_id'], 
                'user_profile'=>'uploads/user_profile/'.$file_name
                ]);

            return view('Home/home');
        }else{
            return "add user fail";
        }        
    }

    //删除用户
    public function del(User $user,Request $request){
        $data = [
            'msg'=>null,
            'icon'=>null
        ];
        $map['user_id'] =$request->input('user_id');
        $userInfo = $user->get($map)->toArray();
        $profile_name = $userInfo[0]['user_profile'];
        //删除头像文件
        $delProfile = unlink(public_path('uploads/user_profile').'/'.$profile_name);
        $delUser = $user->del($map);
        if($delProfile && $delUser){
            $data['msg'] = '删除成功!';
            $data['icon']='1';
        }else{
            $data['msg'] = '删除失败!';
            $data['icon']='2';
        }
        return response()->json($data);
    }

    //获取用户信息
    public function get(User $user){
        $input = Request::all();
        $map = [
        $map['user_id'] = $input['user_id']
        ];
        $re = $user->get($map);
        if($re){
            return var_dump(json_decode(json_encode($re),true));
        }else{
            return " get user fail";
        }
    }

    //修改用户信息
    public function edit(User $user){
        $input = Request::all();
        $map = [
        $map['user_id'] = $input['user_id']
        ];
        $data = [
        'user_name'=>$name,
        'user_password'=>password_hash($passwd,PASSWORD_DEFAULT)
        ];
        $re = $user->edit($map,$data);
        if($re){
            return "update user succ";
        }else{
            return "update user fail";
        }
    }
    //显示所有用户
    public function displayAll(User $user){
        //简单分页
        $users = $user->paginate(2);
        // pd($users);
        if($users){
            return view('User/displayAll',['users'=>$users]);
        }else{
            return 0;
        }
    }

    //修改密码
    public function editPass(User $user,Request $request){
        $map = ['user_id'=>$request->input('user_id')];
        $data = ['user_password'=>password_hash($request->input('user_password'),PASSWORD_DEFAULT)];


        if($user->edit($map,$data)){
            return response()->json(['flag'=>'1','newHash'=>$data['user_password']]);
        }else{
            return response()->json(['flag'=>'0']);
        }
    }
    //关注or取关用户
    public function follow(Friend $friend,Request $request){
        //处理结果信息
        $msg = null;$icon = null;
        //对输入数据的验证
        $this->validate($request,[
            'type'=>[
            'required',
            Rule::in(['follow','unfollow'])
            ],
            'followed_id'=>'required'
        ]);
        //要处理的好友关注数据
        $data = [
            'follow_id'=>session('user_id'),
            'followed_id'=>$request->input('followed_id')
        ];
        //判断数据在表中是否存在
        $exist = count($friend->get($data))?true:false;
        //关注
        if($request->input('type')==='follow'){
            if($exist){
                $msg = '已关注!';$icon='7';
            }else{
                if($friend->add($data)){
                    $msg = '关注成功!';$icon='1';
                }else{
                    $msg = '关注失败!';$icon='2';
                }
            }
        }else{//取关
            if(!$exist){
                $msg = '未关注!';$icon='7';
            }else{
                if($friend->del($data)){
                    $msg = '取关成功!';$icon='1';
                }else{
                    $msg = '取关失败!';$icon='2';
                }
            }
        }
        //返回处理结果
        return response()->json(['msg'=>$msg,'icon'=>$icon]);
    }
    //显示关注列表
    public function displayFollow(Friend $friend,User $user,Request $request){
        $map = [
            'follow_id'=>session('user_id')
        ];
        $pageData = $friend->where($map)->get()->toArray();
        // pd($pageData);
        foreach ($pageData as $key => &$value) {
            $followed_info = $user->get(['user_id'=>$value['followed_id']])->toArray();
            $value = array_merge($value,$followed_info[0]);
        }
        // $follows = $this->arrayToObject($follows);
        // pd($pageData);
        // 设定当前页号
        $page=1;
        if($request->input('page'))
        {
            $page=$request->input('page');
        }
        // pd($page);
        //设定一页行数
        $pageSize=6;
        //总共行数
        $total=count($pageData);
        //实例化分页类啊
        $paged=new LengthAwarePaginator($pageData,$total,$pageSize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('displayFollow'));
        //截取指定页数据
        $pageOut=array_slice($pageData, ($page-1)*$pageSize,$pageSize);
        // pd($pageOut);
        return view('User/displayFollow',['pageOut'=>$pageOut,'paged'=>$paged]);
    }
    //显示粉丝列表
    public function displayFollower(Friend $friend,User $user,Request $request){
        $map = [
            'followed_id'=>session('user_id')
        ];
        $pageData = $friend->where($map)->get()->toArray();
        // pd($pageData);
        foreach ($pageData as $key => &$value) {
            $followed_info = $user->get(['user_id'=>$value['follow_id']])->toArray();
            $value = array_merge($value,$followed_info[0]);
        }
        // $follows = $this->arrayToObject($follows);
        // pd($pageData);
        // 设定当前页号
        $page=1;
        if($request->input('page'))
        {
            $page=$request->input('page');
        }
        // pd($page);
        //设定一页行数
        $pageSize=6;
        //总共行数
        $total=count($pageData);
        //实例化分页类啊
        $paged=new LengthAwarePaginator($pageData,$total,$pageSize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('displayFollow'));
        //截取指定页数据
        $pageOut=array_slice($pageData, ($page-1)*$pageSize,$pageSize);
        // pd($pageOut);
        return view('User/displayFollower',['pageOut'=>$pageOut,'paged'=>$paged]);
    }
}
