<?php

namespace App\Http\Controllers\User;

use App\Models\Invitation;
use App\Models\Mail;
use Illuminate\Http\Request;
use Storage;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Friend;
use App\Models\Membership;
use App\Models\Team;
use App\Models\App_join;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //显示个人管理界面
    public function displayInfo(User $user,Mail $mail,Invitation $invitation){
        //sql查询条件
        $map = [
            'user_id' => session('user_id')
        ];
        //从数据库获得结果集以数组形式返回
        $info = $user->get($map)->toArray();
        // pd($info);
        //若有该用户数据，显示页面

        /*查询是否有未读邮件*/
        $newsNum=count($mail->where(['mail_status'=>'0','mail_to_id'=>session('user_id')])->get()->toArray())+count($invitation->where(['user_id'=>session('user_id'),'status'=>'0'])->get()->toArray());
        if($info){
            $assign = $info[0];
            return view('User/displayInfo',['assign'=>$assign,'newsNum'=>$newsNum]);
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
            //结果返回前端
            return response()->json(['flag'=>true]);
        }else{
            return response()->json(['flag'=>false]);
        }
    }

    public function logout(){
        //清空当前用户session
        session()->flush();
        //回到起始页
        return redirect('/');
    }

    //注册用户
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
            'user_name'=>(string)str_replace(" ","",$input['user_name']),
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
    //显示增加组员界面
    public function displayAllForAdd(User $user,Membership $membership,Team $team,Request $request,$team_id){
        $teamInfo = $team->get(['team_name'=>$team_id])->toArray();
        /*   $team_id = $teamInfo[0]['team_id'];*/
        $current_team_users_primary = $membership->get(['team_id'=>$team_id])->toArray();
        $current_team_users = [];
        foreach ($current_team_users_primary as $key => $value) {
            array_push($current_team_users,$value['member_id']);
        }
        // pd($current_team_users);
        $pageData = $user
            ->whereNotin('user_id',$current_team_users)
            ->get()
            ->toArray();
        // pd($pageData);
        // 设定当前页号
        $page=1;
        if($request->input('page'))
        {
            $page=$request->input('page');
        }
        // pd($page);
        //设定一页行数
        $pageSize=2;
        //总共行数
        $total=count($pageData);
        //实例化分页类啊
        $paged=new LengthAwarePaginator($pageData,$total,$pageSize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('displayAllForAdd',$team_id));
        //截取指定页数据
        $pageOut=array_slice($pageData, ($page-1)*$pageSize,$pageSize);
        // pd($pageOut);
        return view('User/displayAllForAdd',['users'=>$pageOut,'paged'=>$paged,'teamInfo'=>$teamInfo]);

        // //简单分页
        // $users = $user->paginate(2);
        // // pd($users);
        // if($users){
        //     return view('User/displayAllForAdd',['users'=>$users,'team_name'=>$team_name]);
        // }else{
        //     return 0;
        // }
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
    //显示当前用户所关注用户的列表
    public function displayFollow(Friend $friend,User $user,Request $request){
        $map = [
            'follow_id'=>session('user_id')
        ];
        $pageData = $friend->where($map)->get()->toArray();
        // pd($pageData);
        foreach ($pageData as $key => &$value) {
            $followed_info = $user->get(['user_id'=>$value['followed_id']])->toArray();
            // pd($followed_info);
            $value = $followed_info!=null?array_merge($value,$followed_info[0]):null;
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

    public function displaySearchResult(User $user,Request $request,Friend $friend){
        //拿到从view传过来的需查找的user ID
        $request->validate([
            'key'=>'required',
        ]);
        //查询语句返回一个user
        //将结果返回到view页面
        $data = [
            'user_id'=>$request->input('key')
        ];
        $result=$user->get($data)->toArray();
        $map = [
            'follow_id'=>session('user_id')
        ];
        $friendship=$friend->where('followed_id',$data)->where('follow_id',$map)->get()->toArray();
        if($friendship==null){
            /*未关注该查找目标用户*/
            $friendship=false;
        }
        else{
            /*已关注该查找目标用户*/
            $friendship=true;

        }

        return view('User/displaySearchResult',['friendship'=>$friendship,'result'=>$result]);
    }

    public function displayOthersInfo(Request $request,$user_id,User $user,Team $team,Membership $membership,Friend $friend){
        /*查询用户信息*/
        $user_info=$user->get(['user_id'=>$user_id])->toArray();

     /*   $team_mine_info=$team->get(['team_funder_id'=>$user_id])->toArray();
        $team_in_info=$membership->where(['member_id'=>$user_id])->join('team','membership.team_id','=','team.team_id')->get()->toArray();
        $following=$friend->get(['follow_id'=>$user_id])->toArray();
        $followingNum=count($following);
        $follower=$friend->get(['followed_id'=>$user_id])->toArray();
        $followerNum=count($follower);*/

        return view('User/displayOthersInfo',['user_info'=>$user_info[0]]);
    }
    public function displayOthersInfoTeams(Request $request,Membership $membership,Team $team,User $user){
        $user_id=$request->input('user_id');
        $user_info=$user->get(['user_id'=>$user_id])->toArray();
        $team_mine_info=$team->where(['team_funder_id'=>$user_id])->join('user','user.user_id','=','team.team_funder_id')->get()->toArray();
        $team_in_info=$membership->where(['member_id'=>$user_id])
                                 ->join('team','membership.team_id','=','team.team_id')
                                 ->join('user','user.user_id','=','team.team_funder_id')->get()->toArray();


        return view('User/displayOthersInfoTeams',['teams'=>$team_in_info,'user_info'=>$user_info[0],'myTeams'=>$team_mine_info]);

    }

    public function displayInfoOptions(){
        return view('User/displayInfoOptions');
    }
    public function applyJoinTeam(Request $request,App_join $app_join){
        $request->validate([
            'team_id'=>'required',
            'applicant_id'=>'required'
        ]);
        $team_id=$request->input('team_id');
        $applicant_id=$request->input('applicant_id');
        /*需要设计一个数据表存储msg*/
        $app_join_data= [
            'app_team_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$team_id,
            'applicant_id'=>$applicant_id,
            'status'=>'0',
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $app_join->add($app_join_data);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg'=>'Your invitation has been successfully sent!!','icon'=>'1']);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'Network is busy now,try again later！','icon'=>'2']);
        }
    }

    /**
     * @param Request $request
     * @param Mail $mail
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayInfoMailBox(Mail $mail, User $user,Invitation $invitation,Team $team){
        /*1.获得user id,查询是否存在邮箱新消息*/
        $newsNum=count($mail->where(['mail_status'=>'0','mail_to_id'=>session('user_id')])->get()->toArray())+count($invitation->where(['user_id'=>session('user_id'),'status'=>'0'])->get()->toArray());
        $mailsNum=count($mail->where(['mail_to_id'=>session('user_id')])->get()->toArray());
        $invitationNum=count($invitation->where(['user_id'=>session('user_id'),'status'=>'0'])->get()->toArray());
        /*2.如果存在查询消息*/
        $mailsRecieved=$mail->where(['mail_to_id'=>session('user_id')])->orderBy('mail_sent_time','desc')->get()->toArray();

        foreach ($mailsRecieved as $key=>&$value){
            /*将发送用户的用户名加入到返回数据当中*/
            $mail_from_names=$user->get(['user_id'=>$value['mail_from_id']])->toArray();
            $value['mail_from_name']=$mail_from_names[0]['user_name'];
            /*计算邮件发送时间与系统时间的间隔*/
            $now=date("Y-m-d H:i:s");
            if(floor(strtotime($now)-$value['mail_sent_time'])>86400){
                $value['mail_time_gap']=(floor((strtotime($now)-$value['mail_sent_time'])/86400)).'day(s) ago';
                $value['mail_time_gap']=" just now";
            }elseif (floor(strtotime($now)-$value['mail_sent_time'])>3600){
                $value['mail_time_gap']=(floor((strtotime($now)-$value['mail_sent_time'])/3600)).'hour(s) ago';

            }
            elseif(floor(strtotime($now)-$value['mail_sent_time'])>60){
                $value['mail_time_gap']=(floor((strtotime($now)-$value['mail_sent_time'])/60))." minute(s) ago";

            }
            else{
                $value['mail_time_gap']='just now';
            }


        }

/*查询是否有新的未读邀请*/
        $invite=$invitation->where(['user_id'=>session('user_id'),'status'=>'0'])->get()->toArray();
        /*查询当前已发送的邮件*/
        $sentMails=$mail->get(['mail_from_id'=>session('user_id')])->toArray();
        foreach($sentMails as $key => &$value){
            $mail_to_name=$user->get(['user_id'=>$value['mail_to_id']])->toArray();
            $value['mail_to_name']=$mail_to_name[0]['user_name'];

            if(floor(strtotime($now)-$value['mail_sent_time'])>86400){
                $value['mail_time_gap']=(floor((strtotime($now)-$value['mail_sent_time'])/86400)).'day(s) ago';
                $value['mail_time_gap']=" just now";
            }elseif (floor(strtotime($now)-$value['mail_sent_time'])>3600){
                $value['mail_time_gap']=(floor((strtotime($now)-$value['mail_sent_time'])/3600)).'hour(s) ago';

            }
            elseif(floor(strtotime($now)-$value['mail_sent_time'])>60){
                $value['mail_time_gap']=(floor((strtotime($now)-$value['mail_sent_time'])/60))." minute(s) ago";

            }
            else{
                $value['mail_time_gap']='just now';
            }
        }
        if(!empty($invite)){
            foreach($invite as $key=>&$value){
                $info=$team->get(['team_id'=>$value['team_id']])->toArray();
                $value['invitation_team_name']=$info[0]['team_name'];
            }
            return view('User/displayInfoMailBox',['newsNum'=>$newsNum,'mailsNum'=>$mailsNum,'invitationNum'=>$invitationNum,'mailsRecieved'=>$mailsRecieved,'sentMails'=>$sentMails,'invitations'=>$invite]);
        }
        else{
            return view('User/displayInfoMailBox',['newsNum'=>$newsNum,'mailsNum'=>$mailsNum,'mailsRecieved'=>$mailsRecieved,'sentMails'=>$sentMails]);
        }

    }
    public function acceptInvitation(Request $request,Invitation $invitation,Team $team,Membership $membership){
        $request->validate([
            'team_id'=>'required',
            'user_id'=>'required'
        ]);
        $team_id=$request->input('team_id');
        $user_id=$request->input('user_id');
        $map=[
            'team_id'=>$team_id,
            'user_id'=>$user_id
        ];
        $update_data=[
            'status'=>'1'
        ];
        $map_team = [
            'membership_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$team_id,
            'member_id'=>$user_id
        ];
        $team_info=$team->get(['team_id'=>$team_id])->toArray();
        try {
            //开始事务
            DB::beginTransaction();
            $invitation->edit($map,$update_data);
            $membership->add($map_team);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg'=>'Welcome to team : '.$team_info[0]['team_name'],'icon'=>'1']);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'Network is busy now,try again later！','icon'=>'2']);
        }
    }
    public function refuseInvitation(Request $request,Invitation $invitation){
        $request->validate([
            'team_id'=>'required',
            'user_id'=>'required'
        ]);
        $team_id=$request->input('team_id');
        $user_id=$request->input('user_id');

        $map=[
            'team_id'=>$team_id,
            'user_id'=>$user_id
        ];
        $update_data=[
          'status'=>'2'
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $invitation->edit($map,$update_data);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg'=>'Operation succeeded','icon'=>'1']);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'Network is busy now,try again later！','icon'=>'2']);
        }
    }
    public function sendMail(Request $request,Mail $mail,User $user){
        $request->validate([
            'mail_to_id'=>'required',
            'mail_from_id'=>'required',
        ]);
        $mail_to_id=$request->input('mail_to_id');
        $mail_from_id=$request->input('mail_from_id');
        $mail_title=$request->input('mail_title');
        $mail_content=$request->input('mail_content');
       /* $existID=$user->where(['user_id'=>$mail_to_id])->get();*/
        $data = [
            'user_id'=>$mail_to_id
        ];
        //判断数据在表中是否存在
        $exist = count($user->get($data))?true:false;
        if($exist){
            $map=[
                'mail_id'=>md5(uniqid(mt_rand(),true)),
                'mail_to_id'=>$mail_to_id,
                'mail_from_id'=>$mail_from_id,
                'mail_title'=>$mail_title,
                'mail_content'=>$mail_content,
                'mail_type'=>'1',/*Sent by user:1 Sent by system:2*/
                'mail_status'=>'0',/*Unread:0 Read:1*/
                'mail_sent_time'=>strtotime(date("Y-m-d H:i:s"))
            ];
            try {
                //开始事务
                DB::beginTransaction();
                $mail->add($map);
                //提交事务
                DB::commit();
                //返回前端添加成功结果
                return response()->json(['msg'=>'Mail has been sent successfully!']);
            } catch(QueryException $ex) {
                //回滚事务
                DB::rollback();
                //返回前端添加失败结果
                return response()->json(['msg'=>'Network is busy now,try again later!']);
            }
        }
        else{
            return response()->json(['msg'=>'Sorry!User id does not exist!']);
        }
    }

    public function dumpMail(Request $request,Mail $mail){
        $request->validate([
            'mail_id'=>'required'
        ]);
        $mailId=$request->input('mail_id');
        $map=[
            'mail_id'=>$mailId
            ];
        try {
            //开始事务
            DB::beginTransaction();
            $mail->del($map);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg'=>'Mail has been deleted successfully!']);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'Network is busy now,try again later!']);
        }


    }
    public function displayInfoMailContent(Request $request, $mail_id,Mail $mail,Invitation $invitation,User $user){
        $newsNum=count($mail->where(['mail_status'=>'0','mail_to_id'=>session('user_id')])->get()->toArray())+count($invitation->where(['user_id'=>session('user_id'),'status'=>'0'])->get()->toArray());
        $mail_content=$mail->get(['mail_id'=>$mail_id])->toArray();
        $mail_from_info=$user->get(['user_id'=>$mail_content[0]['mail_from_id']])->toArray();
        if($mail_content[0]['mail_status']==0){
            $map=['mail_id'=>$mail_id];
            $update_data=['mail_status'=>"1"];
            try {
                //开始事务
                DB::beginTransaction();
                $mail->edit($map,$update_data);
                //提交事务
                DB::commit();

            } catch(QueryException $ex) {
                //回滚事务
                DB::rollback();
            }

        }
        return view('User/displayInfoMailContent',['newsNum'=>$newsNum,'mail_content'=>$mail_content[0],'mail_from_info'=>$mail_from_info[0]]);
    }

    public function displayInfoMyMailContent(Request $request, $mail_id,Mail $mail,Invitation $invitation,User $user)
    {
        $newsNum = count($mail->where(['mail_status' => '0', 'mail_to_id' => session('user_id')])->get()->toArray()) + count($invitation->where(['user_id' => session('user_id'), 'status' => '0'])->get()->toArray());
        $mail_content = $mail->get(['mail_id' => $mail_id])->toArray();
        $mail_to_info = $user->get(['user_id' => $mail_content[0]['mail_to_id']])->toArray();
        return view('User/displayInfoMyMailContent', ['newsNum' => $newsNum, 'mail_content' => $mail_content[0], 'mail_to_info' => $mail_to_info[0]]);
    }
}






