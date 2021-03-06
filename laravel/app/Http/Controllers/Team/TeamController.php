<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Task\TaskController;
use App\Models\Invitation;
use App\Models\App_join;
use App\Models\Mail;
use App\Models\Privacy;
use App\Models\Stask_allocation;
use App\Models\Stask_comment;
use App\Models\Stask_submission;
use App\Models\TaskTransaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use App\Models\Team;
use App\Models\User;
use App\Models\Friend;
use App\Models\Membership;
use App\Models\TeamUploading;
use App\Models\UserUpdating;
use App\Models\Task;
use App\Models\Stask;
use DB;

use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    //创建团队
    public function add(Request $request,Team $team,Membership $membership,UserUpdating $userUpdating){
        //对前端传参检查
        $request->validate([
            'team_name'=>'required',
            'team_info'=>'required|max:1000'
        ]);
        //团队信息
        $team_id=md5(uniqid(mt_rand(),true));
        $data_team = [
            'team_id'=>$team_id,
            'team_name'=>$request->input('team_name'),
            'created_at'=>strtotime(date("Y-m-d H:i:s")),
            'team_funder_id'=>session('user_id'),
            'team_info'=>$request->input('team_info')
        ];
        $map_updating = [
            'updating_id'=>md5(uniqid(mt_rand(),true)),
            'updater_id'=>session('user_id'),
            'time'=>strtotime(date("Y-m-d H:i:s")),
            'type'=>'cTeam',
            'content'=>'Hey!I have created a team named : '.$request->input('team_name').' ! Come over and check out details !',
            'resource'=>$team_id
        ];
        //加入用户个人动态
        //添加队长的团队-组员关系记录
        $data_membership = [
            'membership_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$data_team['team_id'],
            'member_id'=>session('user_id'),
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $team->add($data_team)&&$membership->add($data_membership);
            $userUpdating->add($map_updating);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            if(session('applocale')=='en'){
                $msg='Team created successfully!';
            }else{
                $msg = '创建队伍成功！';
            }
            return response()->json(['msg'=>$msg]);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            if(session('applocale')=='en'){
                $msg='Busy network!Try again later!';
            }else{
                $msg = '网络繁忙，请稍后再试！';
            }
            return response()->json(['msg'=>$msg]);
        }
    }
    //显示为我所在的团队
    public function displayMine(Team $team,Membership $membership,Request $request,$sort_key=null){
        $sort_key=$sort_key=='default'?'team.team_id':(string)$sort_key;
        //我的所有团队记录
        $pagedata = $membership
            ->where(['member_id'=>session('user_id')])
            ->join('team','membership.team_id','=','team.team_id')
            ->join('user','user.user_id','=','team.team_funder_id')
            ->orderBy($sort_key,'asc')
            ->get()
            ->toarray();
        foreach ($pagedata as $key => &$value) {
            //单个团队的详细信息
            $team_info = $team->get(['team_id'=>$value['team_id']])->toarray();
            //团队记录添加团队详细信息
            $value = array_merge($value,reset($team_info)?$team_info['0']:array());
        }
        // pd($pagedata);
        // 默认页码
        $page = $request->input('page')?$request->input('page'):1;
        // pd($page);
        //设定一页行数
        $pagesize=4;
        //总共行数
        $total=count($pagedata);
        //实例化分页类
        $paged=new LengthAwarePaginator($pagedata,$total,$pagesize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('displayMyTeam',$sort_key));
        //截取指定页数据
        $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);
        // pd($pageout);
        return view('Team/displayMine',['pageout'=>$pageout,'paged'=>$paged]);
    }
    //显示我创建团队
    public function displayMineCre(Team $team,Membership $membership,Request $request,$sort_key=null){
        // 排序规则,默认或按要求
        $sort_key = $sort_key=='default'?'team.team_id':(string)$sort_key;
        //我的所有团队记录
        $pagedata = $team
            ->where(['team.team_funder_id'=>session('user_id')])
            ->join('user','user.user_id','=','team.team_funder_id')
            ->orderBy($sort_key,'asc')
            ->get()
            ->toarray();
        foreach ($pagedata as $key => &$value) {
            //单个团队的详细信息
            $team_info = $team->get(['team_id'=>$value['team_id']])->toarray();
            //团队记录添加团队详细信息
            $value = array_merge($value,reset($team_info)?$team_info['0']:array());
        }
        // pd($pagedata);
        // 默认页码
        $page = $request->input('page')?$request->input('page'):1;
        // pd($page);
        //设定一页行数
        $pagesize=4;
        //总共行数
        $total=count($pagedata);
        //实例化分页类
        $paged=new LengthAwarePaginator($pagedata,$total,$pagesize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('displayMyTeamCre',$sort_key));
        //截取指定页数据
        $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);
        // pd($pageout);
        return view('Team/displayMineCre',['pageout'=>$pageout,'paged'=>$paged]);
    }
    //显示所有团队
    public function displayAll(Team $team){
        // 每页显示2行的团队数据
        $data = $team->paginate(2);

        return view('Team/displayAll',['teams'=>$data]);
    }
    //当前用户加入团队
    public function join(Request $request,Membership $membership){
        //对前端传参检查
        $request->validate([
            'team_id'=>'required',
            'member_id'=>'required'
        ]);
        //据此检查该团队是否已存在
        $map = [
            'team_id'=>$request->input('team_id'),
            'member_id'=>session('user_id')
        ];
        //要增加的团队信息
        $data = [
            'membership_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$request->input('team_id'),
            'member_id'=>$request->input('member_id')
        ];
        if(!$membership->get($map)->isEmpty()){
            //该团队已存在
            return response()->json(['msg'=>'已加入!','icon'=>'7']);
        }else if($membership->add($data)){
            //增加团队成功
            return response()->json(['msg'=>'加入成功!','icon'=>'1']);
        }else{
            //增加团队失败
            return response()->json(['msg'=>'加入失败!','icon'=>'2']);
        }
    }
    //离开团队
    public function quit(Request $request,Membership $membership,Team $team){
        //对前端传参检查
        $request->validate([
            'team_id'=>'required'
        ]);
        //要查找是否存在的数据
        $map = [
            'team_id' => $request->input('team_id'),
            'team_funder_id'=>session('user_id')
        ];
        //暂定队长不能退出团队
        if(!$team->get($map)->isEmpty()){
            //队长请求退出，退出失败
            return response()->json(['msg'=>'队长不能退出!','icon'=>'2']);
        }else if($membership->del(['team_id'=>$request->input('team_id')])){
            //组员请求退出，退出成功
            if(session('applocale')=='en'){
                $msg='Operated Successfully!';
            }else{
                $msg = '退出成功！';
            }
            return response()->json(['msg'=>$msg]);
        }else{
            //组员请求退出，退出失败
            if(session('applocale')=='en'){
                $msg='Busy network!Try again later!';
            }else{
                $msg = '网络繁忙，请稍后再试！';
            }
            return response()->json(['msg'=>$msg]);
        }
    }
    //显示单个团队信息、成员
    public function displayOne(User $user,Membership $membership,Team $team,Request $request,$team_name,Friend $friend,TeamUploading $teamUploading){
        //获取该团队信息
        $teamInfo = $team->get(['team_name'=>$team_name])->toarray();
        //获取该团队创建者信息
        $funderName = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_name');
        $funderProfile = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_profile');
        //团队信息加入该团队创建者信息
        $teamInfo[0] = array_merge($teamInfo[0],['user_name'=>$funderName]);
        $teamInfo[0] = array_merge($teamInfo[0],['user_profile'=>$funderProfile]);
        //获取组员列表
        $teamMember = $membership->get(['team_id'=>$teamInfo[0]['team_id']])->toarray();
        // pd($teamMember);
        //该团队有组员，搜索成员信息
        $data['belong2team']=false;/*该属性用于判断当前用户是否属于该团队，false代表不属于该团队*/
        if(!empty($teamMember)){
            //对组员列表添加每个组员详细信息

            foreach ($teamMember as $key => &$value) {
                //获取单个组员信息
                $userInfo = $user->get(['user_id'=>$value['member_id']])->toArray();
                // pd($userInfo[0]);
                //组员列表添加该组员信息
                $value = array_merge($value,$userInfo[0]);

                /*判断用户是否属于该团队*/
                if($value['member_id']==session('user_id')){
                    $data['belong2team']=true;
                }
            }
        }
        //页码
        $page = $request->input('page')?$request->input('page'):1;

        //总数
        $total = count($teamMember);/*算上一个add icon*/
        //每页记录数
        $pageSize = 18;
        //实例化分页类
        $paged = new LengthAwarePaginator($teamMember,$total,$pageSize);
        //分页url
        $paged = $paged->setPath(route('displayOneTeam',['team_name'=>$teamInfo[0]['team_name']]));
        //要显示页的内容
        $pageOut = array_slice($teamMember,($page-1)*$pageSize,$pageSize);
        /*判断是否为最后一页*/
        if($page*$pageSize>=$total){
            $data['finalPage']=true;
        }
        else{
            $data['finalPage']=false;
        }

        /*用户的关注数量 与被关注数量*/
        $map1=[
            'followed_id'=>session('user_id')
        ];
        $map2 = [
            'follow_id'=>session('user_id')
        ];

        $data['followNum']=count($friend->get($map2)->toArray());
        $data['followerNum']=count($friend->get($map1)->toArray());


        //获取该团队动态信息
        $uploadings=$teamUploading->where(['team_id'=>$teamInfo[0]['team_id']])->orderBy('time','desc')->get()->toarray();
        //添加上传人的头像信息和姓名
        foreach ($uploadings as &$key){
            $uploader_info=$user->where(['user_id'=>$key['uploader_id']])->get()->toArray();
            $key['uploader_profile']=$uploader_info[0]['user_profile'];
            $key['uploader_name']=$uploader_info[0]['user_name'];
        }
        //要显示的页面；传给前端的分页信息：团队介绍、队员信息、分页；
        // pd($pageOut);
        if($funderName==session('user_name')){
            return view('Team/displayOneAuth',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged,'data'=>$data,'uploadings'=>$uploadings]);
        }else{
            return view('Team/displayOne',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged,'data'=>$data,'uploadings'=>$uploadings]);
        }

    }
    //移除团队成员
    public function removeMember(Request $request,Membership $membership,Mail $mail,Team $team,User $user){
        //对前端传参检查
        $request->validate([
            'team_id'=>'required',
            'userId_list'=>'required'
        ]);
        //初始化返回前端的信息
        $data = [
            'msg'=>'删除组员成功!',
            'icon'=>1
        ];
        $fail_users_idList = [];
        //团队id
        $team_id = $request->input('team_id');
        $team_name=$team->where(['team_id'=>$team_id])->value('team_name');
        $map = ['team_id'=>$team_id,'member_id'=>null];
        //组员id列表
        $userId_list = $request->input('userId_list');
        //删除组员
        foreach ($userId_list as $key ) {
            //某组员id
            $map['member_id'] = $key;
            //删除失败，返回的失败信息
            if(!$membership->del($map)){
                $fail_users_idList.push($key);
                $data['icon'] = 2;
            }
            else{
                $user_name=$user->where(['user_id'=>$key])->value('user_name');
                $map=[
                    'mail_id'=>md5(uniqid(mt_rand(),true)),
                    'mail_to_id'=>$key,
                    'mail_from_id'=>'admin',
                    'mail_title'=>"System Inform",
                    'mail_content'=>"Dear user ".$user_name.",good day! Team manager has removed you from team : ".$team_name,
                    'mail_type'=> "0",/*Sent by user:1 Sent by system:0*/
                    'mail_status'=> "0",/*Unread:0 Read:1*/
                    'mail_sent_time'=>strtotime(date("Y-m-d H:i:s"))
                ];
                $mail->add($map);
            }
        }
        //如果有删除失败的情况，返回的失败信息
        if($data['icon']==2){
            $data['msg'] = '删除组员存在失败！';
        }
        return response()->json(['msg'=>$data['msg'],'icon'=>$data['icon'],'idList'=>$fail_users_idList]);
    }
    //显示当前用户根据关键字搜索已加入团队
    public function displaySearchMine(Membership $membership,Team $team,Request $request,User $user){
        //
        $map=['member_id','=',session('user_id')];
        $map1=['team_name','like','%'.$request->input('search-key').'%'];
        $map2=['team_info','like','%'.$request->input('search-key').'%'];
        $map3=['team_'];
        //我的所有团队记录
        $pagedata = $membership
            ->where([$map,$map1])
            ->orWhere([$map,$map2])
            ->join('team','membership.team_id','=','team.team_id')
            ->join('user','user.user_id','=','team.team_funder_id')
            ->get()->toarray();
        foreach ($pagedata as $key => &$value) {
            //单个团队的详细信息
            $team_info = $team->get(['team_id'=>$value['team_id']])->toarray();
            //团队记录添加团队详细信息
            $value = array_merge($value,reset($team_info)?$team_info[0]:array());
        }
        // pd($pagedata);
        // 默认页码
        $page = $request->input('page')?$request->input('page'):1;
        // pd($page);
        //设定一页行数
        $pagesize=4;
        //总共行数
        $total=count($pagedata);
        //实例化分页类
        $paged=new LengthAwarePaginator($pagedata,$total,$pagesize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('SearchMyTeam'));
        //截取指定页数据
        $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);
        // pd($pageout);
        return view('Team/displaySearchMine',['pageout'=>$pageout,'paged'=>$paged]);

    }
    //队长添加队员
    public function addTeammates(Request $request,Membership $membership,Team $team){
        //对前端传参检查
        $request->validate([
            'team_id'=>'required',
            // 记录user_id的数组
            'user_list'=>'required'
        ]);
        // flag 值为0表示添加队员没有错，1表示用户已加入该队伍,2表示添加队员出错
        $flag = 0;
        // 添加失败的用户列表
        $fail_list = [];
        // 根据前端传参： 团队名 查询团队信息
        $teamInfo =$team->get(['team_id'=>$request->input('team_id')])->toArray();
        $teamInfo = current($teamInfo);
        // 获取团队信息中的team_id
        $team_id = $request->input('team_id');
        // 前端传过来的用户列表中的用户依次加入队伍操作
        foreach ($request->input('user_list') as $key ) {
            // 判断用户是否已加入团队
            $userExist = $membership->get(['team_id'=>$team_id,'member_id'=>$key])->isEmpty() ? false : true;
            if($userExist){
                $flag = 1;
                array_push($fail_list,$key);
            }else{
                $map = [
                    'membership_id'=>md5(uniqid(mt_rand(),true)),
                    'team_id'=>$team_id,
                    'member_id'=>$key
                ];
                if(!$membership->add($map)){
                    $flag = 2;
                    array_push($fail_list,$key);
                }
            }

        }
        //暂定队长不能退出团队
        if($flag==0){
            //队长添加组员成功
            return response()->json(['msg'=>'添加成功!','icon'=>'1']);
        }else if($flag == 1){
            //组员已存在，添加失败
            return response()->json(['msg'=>'组员已存在，添加失败!','icon'=>'3','fail_list'=>$fail_list]);
        }else {
            //队长添加组员存在失败
            return response()->json(['msg'=>'添加队员失败!','icon'=>'2','fail_list'=>$fail_list]);
        }

    }
    public function displaySearchResult(Request $request,Team $team,Membership $membership){

        //拿到从view传过来的需查找的Team Name/Team info
        /* $this->validate($request,[
             'key'=>'required'
         ]);*/
        $userid=session('user_id');
        if($request->input('key')){

            $s_key=$request->input('key');
            session_start();
            session()->put('key',$request->input('key'));
        }
        else{

            $s_key=session('key');

        }
        $data1=['team_name','like','%'.$s_key.'%'];
        $data2=['team_info','like','%'.$s_key.'%'];


        /*1.首先判断是否存在搜索目标*/
        $pagedata=$team->where([$data1])->orWhere([$data2])->get()->toArray();
        /*2.若存在，判断user与该team的关系*/
        if(!empty($pagedata)){
            // /*3.判断查找行为的用户本身是否为改团队的成员*/
            // foreach ($pagedata as $key => &$value) {
            //     /*4.若user创建该team*/
            //     $funderOrNot=$team->where(['team.team_id'=>$value['team_id']])->join('membership','membership.team_id','=','team.team_id')->get()->toArray();
            //     pd($funderOrNot);
            //     if ($funderOrNot[0]['team_funder_id']==$userid) {
            //         $flag[0]=1;
            //         $value['flag']=1;
            //     } else if ($funderOrNot[0]['member_id']==$userid) {
            //         $flag[0]=2;
            //         /*5.若user已加入该team*/
            //         $value['flag']=2;
            //     } else {
            //         $flag[0]=3;
            //         $value['flag']=3;
            //         /*6.若user并未加入该team*/

            //     }
            //     $value = array_merge($value,$flag);

            // }
            foreach ($pagedata as &$key ) {
                $team_id = $key['team_id'];
                $user_list = $membership->get(['team_id'=>$team_id])->toArray();
                // pd($user_list);
                //与关键字匹配团队的成员数量
                $count = !empty($user_list)?count($user_list):0;
                array_push($key,['count'=>$count]);
            }
        }
        // pd($pagedata);


        $page = $request->input('page')?$request->input('page'):1;
        // pd($page);
        //设定一页行数
        $pagesize=4;
        //总共行数
        $total=count($pagedata);
        //实例化分页类
        $paged=new LengthAwarePaginator($pagedata,$total,$pagesize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('SearchAllTeam'));
        //截取指定页数据
        $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);

        return view('Team/displaySearchResult',['pageout'=>$pageout,'paged'=>$paged])->withCookie('love');
    }

    public function manageTeamMember(Request $request,Membership $membership,User $user,Team $team){
        $team_name=$request->input('team_name');
        $teamInfo=$team->get(['team_name'=>$team_name])->toArray();
        $teamMember=$membership->get(['team_id'=>$teamInfo[0]['team_id']])->toArray();
        $funderName = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_name');
        $funderProfile = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_profile');

        //团队信息加入该团队创建者信息
        $teamInfo[0] = array_merge($teamInfo[0],['user_name'=>$funderName]);
        $teamInfo[0] = array_merge($teamInfo[0],['user_profile'=>$funderProfile]);
        if(!empty($teamMember)){
            //对组员列表添加每个组员详细信息

            foreach ($teamMember as $key => &$value) {
                //获取单个组员信息
                $userInfo = $user->get(['user_id'=>$value['member_id']])->toArray();
                // pd($userInfo[0]);
                //组员列表添加该组员信息
                $value = array_merge($value,$userInfo[0]);
            }
        }
        //页码
        $page = $request->input('page')?$request->input('page'):1;

        //总数
        $total = count($teamMember);/*算上一个add icon*/
        //每页记录数
        $pageSize = 15;
        //实例化分页类
        $paged = new LengthAwarePaginator($teamMember,$total,$pageSize);
        //分页url
        $paged = $paged->setPath(route('ManageTeamMember',['team_name'=>$team_name]));
        //要显示页的内容
        $pageOut = array_slice($teamMember,($page-1)*$pageSize,$pageSize);
        /*判断是否为最后一页*/
        //要显示的页面；传给前端的分页信息：团队介绍、队员信息、分页；


        return view('Team/manageTeamMember',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged]);

    }
    public function manageTeamMemberAdd(Request $request,User $user,Team $team,Membership $membership){
        $team_name=$request->input('team_name');
        $teamInfo=$team->get(['team_name'=>$team_name])->toArray();

        $user_id=$request->input('key');
        $result_user=$user->get(['user_id'=>$user_id])->toArray();
        $result=count($membership->where(['team_id'=>$teamInfo[0]['team_id'],'member_id'=>$user_id])->get()->toArray());
        $isMember=0;
        if($result!=0){
            $isMember=1;
        }

        return view('Team/manageTeamMemberAdd',['team_info'=>$teamInfo[0],'result'=>$result_user,'isMember'=>$isMember]);
    }

    public function sendInvitation(Request $request,Invitation $invitation){
        /*验证数据*/
        $request->validate([
            'team_id'=>'required',
            'user_id'=>'required',
            'title'=>'max:100',
            'content'=>'max:10000'
        ]);
        $team_id=$request->input('team_id');
        $user_id=$request->input('user_id');
        /*需要设计一个数据表存储msg*/
        $invitation_data= [
            'invitation_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$team_id,
            'user_id'=>$user_id,
            'title'=>$request->input('title'),
            'content'=>$request->input('content'),
            'time'=>strtotime(date("Y-m-d H:i:s")),
            'status'=>'0'
        ];

        try {
            //开始事务
            DB::beginTransaction();
            $invitation->add($invitation_data);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            if(session('applocale')=='en'){
                $msg='Your invitation has been successfully sent!!';
            }else{
                $msg = '邀请函发送成功！';
            }
            return response()->json(['msg'=>$msg]);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            if(session('applocale')=='en'){
                $msg='Busy network!Try again later!';
            }else{
                $msg = '网络繁忙，请稍后再试！';
            }
            return response()->json(['msg'=>$msg]);
        }

    }

    public function applicationManage(Request $request,App_join $app_join,Membership $membership,TeamUploading $teamUploading,Team $team,User $user){
        $request->validate([
            'app_id'=>'required',
            'type'=>'required'
        ]);
        $app_id=$request->input('app_id');
        $application=$app_join->where(['app_id'=>$app_id])->get()->toArray();
        $teamFounder=$team->where(['team_id'=>$application[0]['app_team_id']])->join('user','user.user_id','=','team.team_funder_id')->get()->toArray();
        $map=['app_id'=>$app_id];
        if($request->input('type')=="approve"){
            $update_data=['status'=>"1"];
            $data = [
                'membership_id'=>md5(uniqid(mt_rand(),true)),
                'team_id'=>$application[0]['app_team_id'],
                'member_id'=>$application[0]['applicant_id']
            ];
            $map_uploading = [
                'uploading_id'=>md5(uniqid(mt_rand(),true)),
                'team_id'=>$application[0]['app_team_id'],
                'uploader_id'=>$teamFounder[0]['team_funder_id'],
                'time'=>strtotime(date("Y-m-d H:i:s")),
                'content'=>'Welcome new member——'.$teamFounder[0]['user_name']." to join in ".$teamFounder[0]['team_name']."!"
            ];
        }
        else{
            $update_data=['status'=>"2"];
        }
        try {
            //开始事务
            DB::beginTransaction();
            //提交事务
            $membership->add($data);
            $teamUploading->add($map_uploading);
            if(isset($data)){
                $app_join->edit($map,$update_data);}
            DB::commit();
            //返回前端添加成功结果
            if(session('applocale')=='en'){
                $msg='Operated Succeed.!';
            }else{
                $msg = '操作成功！';
            }
            return response()->json(['msg'=>$msg]);

        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            if(session('applocale')=='en'){
                $msg='Busy network!Try again later!';
            }else{
                $msg = '网络繁忙，请稍后再试！';
            }
            return response()->json(['msg'=>$msg]);
        }
    }
    public function createTeamUploading(TeamUploading $teamUploading,Request $request){
        $request->validate([
            'content'=>'required',
            'team_id'=>'required'
        ]);
        $map_uploading=[
            'uploading_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$request->input('team_id'),
            'uploader_id'=>session('user_id'),
            'time'=>strtotime(date("Y-m-d H:i:s")),
            'content'=>$request->input('content')
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $teamUploading->add($map_uploading);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            if(session('applocale')=='en'){
                $msg='Posted Successfully!';
            }else{
                $msg = '发送成功！';
            }
            return response()->json(['msg'=>$msg]);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            if(session('applocale')=='en'){
                $msg='Busy network!Try again later!';
            }else{
                $msg = '网络繁忙，请稍后再试！';
            }
            return response()->json(['msg'=>$msg]);
        }

    }
    public function displayOneAuthTasks(Request $request,$team_id,Task $task,Team $team,User $user,Membership $membership,Friend $friend,TeamUploading $teamUploading,TaskTransaction $taskTransaction,Stask $stask){

        if(empty($request->input('flag'))){
        }
        else{
            $create_trans=new TaskController();
            $create_trans->createTransaction($taskTransaction,$request,$teamUploading,$task);

        }

        //获取该团队信息
        $teamInfo = $team->get(['team_id'=>$team_id])->toarray();
        //获取该团队创建者信息
        $funderName = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_name');
        $funderProfile = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_profile');
        //团队信息加入该团队创建者信息
        $teamInfo[0] = array_merge($teamInfo[0],['user_name'=>$funderName]);
        $teamInfo[0] = array_merge($teamInfo[0],['user_profile'=>$funderProfile]);
        //获取组员列表
        $teamMember = $membership->get(['team_id'=>$teamInfo[0]['team_id']])->toarray();
        // pd($teamMember);
        //该团队有组员，搜索成员信息
        $data['belong2team']=false;/*该属性用于判断当前用户是否属于该团队，false代表不属于该团队*/
        if(!empty($teamMember)){
            //对组员列表添加每个组员详细信息

            foreach ($teamMember as $key => &$value) {
                //获取单个组员信息
                $userInfo = $user->get(['user_id'=>$value['member_id']])->toArray();
                // pd($userInfo[0]);
                //组员列表添加该组员信息
                $value = array_merge($value,$userInfo[0]);

                /*判断用户是否属于该团队*/
                if($value['member_id']==session('user_id')){
                    $data['belong2team']=true;
                }
            }
        }
        //页码
        $page = $request->input('page')?$request->input('page'):1;

        //总数
        $total = count($teamMember);/*算上一个add icon*/
        //每页记录数
        $pageSize = 9;
        //实例化分页类
        $paged = new LengthAwarePaginator($teamMember,$total,$pageSize);
        //分页url
        $paged = $paged->setPath(route('displayOneTeam',['team_name'=>$teamInfo[0]['team_name']]));
        //要显示页的内容
        $pageOut = array_slice($teamMember,($page-1)*$pageSize,$pageSize);
        /*判断是否为最后一页*/
        if($page*$pageSize>=$total){
            $data['finalPage']=true;
        }
        else{
            $data['finalPage']=false;
        }

        /*用户的关注数量 与被关注数量*/
        $map1=[
            'followed_id'=>session('user_id')
        ];
        $map2 = [
            'follow_id'=>session('user_id')
        ];

        $data['followNum']=count($friend->get($map2)->toArray());
        $data['followerNum']=count($friend->get($map1)->toArray());

        //获取该团队动态信息
        $uploadings=$teamUploading->where(['team_id'=>$teamInfo[0]['team_id']])->orderBy('time','desc')->get()->toArray();
        //添加上传人的头像信息和姓名
        foreach ($uploadings as &$key){
            $uploader_info=$user->where(['user_id'=>$key['uploader_id']])->get()->toArray();
            $key['uploader_profile']=$uploader_info[0]['user_profile'];
            $key['uploader_name']=$uploader_info[0]['user_name'];
        }

        $tasks=$task->where(['task_team_id'=>$team_id])->get()->toArray();
        foreach ($tasks as &$key ) {
            $teamInformation = $team->get(['team_id'=>$key['task_team_id']])->toArray();
            array_push($key,$teamInformation[0]['team_name']);
            $userInfo = $user->get(['user_id'=>$key['task_manager_id']])->toArray();
            array_push($key,$userInfo[0]['user_name']);
            if($key['task_status']=='1'){
                if($key['task_deadline']<time()){
                    $timeLeft="Task Expired";
                }
                else{
                    $time=$key['task_deadline']-time();
                    $timeDay=0;
                    $timeHour=0;
                    $timeMin=0;
                    if($time>=86400){
                        $timeDay=(int)($time/86400);
                        $time=$time%86400;
                    }
                    if($time>=3600){
                        $timeHour=(int)($time/3600);
                        $time=$time%3600;
                    }
                    $timeMin=(int)($time/60);

                    $timeLeft=$timeDay." day(s) and ".$timeHour." hour(s) ".$timeMin."minute(s) before deadline";
                }
                $key['timeLeft']=$timeLeft;
            }elseif($key['status']=="0"){
                $key['timeLeft']="Task Will Kick Off At ".date("Y-m-d H:i:s",$key['task_kickoff_date']);
            }
            else{
                $key['timeLeft']="Task Has Finished";
            }
            $trans=$taskTransaction->where(['task_id'=>$key['task_id']])->orderBy('time','asc')->get()->toArray();
            $i=0;
            foreach ($trans as &$tran){
                $key['trans'][$i]=&$tran;
                $i=$i+1;
            }
            /*查看子任务的数量*/
            $stask_num=count($stask->where(['task_id'=>$key['task_id']])->get());
            /*查看状态为完成的人物数量*/
            $completedStask_num=count($stask->where(['task_id'=>$key['task_id'],'status'=>'2'])->get());
            /*返回完成比例*/
            $progress=0;
            if($stask_num!=0){
            $progress=round(($completedStask_num/$stask_num)*100);
            }
            $key['progress']=$progress;
        }
        if(session('user_id')==$teamInfo[0]['team_funder_id']){
            $AuthOrNot='displayOneAuth';
        }
        else{
            $AuthOrNot='displayOne';
        }
        return view('Team/displayOneAuthTasks',['tasks'=>$tasks,'team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged,'data'=>$data,'uploadings'=>$uploadings,'AuthOrNot'=>$AuthOrNot]);


    }
    public function displayOneAuthResources(Request $request,$team_id,TaskTransaction $taskTransaction,Task $task,Stask_submission $stask_submission,Stask $stask ){
        $allTask=$task->where(['task_team_id'=>$team_id])->get()->toArray();
        $i=0;
        foreach ($allTask as &$aTask){
            $taskTrans=$taskTransaction->where(['task_id'=>$aTask['task_id']])->where('trans_Resource_intro','!=','stask')->get(['trans_Resource_path','trans_Resource_intro'])->toArray();
            $j=0;
            foreach ($taskTrans as &$taskTran){
                if($taskTran['trans_Resource_path']==null){

                }
                else{
                    $str=$taskTran['trans_Resource_path'];
                    $arr=substr($str, -3, 3);
                    $taskTran['resourceName']= substr(strrchr($str, "-"), 1);
                    if($arr=='mkv'||$arr=='mov'||$arr=='mp4'||$arr=='rmvb'||$arr=='wmv'||$arr=='vob'){
                        $taskTran['type']='video';
                    }
                    elseif($arr=='bmp'||$arr=='peg'||$arr=='png'||$arr=='jpg'||$arr=='gif'){
                        $taskTran['type']='picture';
                    }
                    elseif($arr=='txt'){
                        $taskTran['type']='txt';
                    }
                    elseif($arr=='pdf'){
                        $taskTran['type']='pdf';
                    }
                    elseif($arr=='zip'){
                        $taskTran['type']='zip';
                    }
                    elseif($arr=='ord'||'doc'||'ocx'){
                        $taskTran['type']='word';
                    }
                    elseif($arr=='cel'){
                        $taskTran['type']='excel';
                    }
                    elseif($arr=='ppt'){
                        $taskTran['type']='ppt';
                    }
                    else{
                        $taskTran['type']='unknown-file';
                    }
                    $pagedata[$i][$j]=&$taskTran;
                    $j=$j+1;
                }

            }

            $i=$i+1;
        }

        if(isset($pagedata)){
            // pd($pagedata);
            // 默认页码
            $page = $request->input('page')?$request->input('page'):1;
            // pd($page);
            //设定一页行数
            $pagesize=8;
            //总共行数
            $total=count($pagedata);
            //实例化分页类
            $paged=new LengthAwarePaginator($pagedata,$total,$pagesize);
            //设置分页跳转路由
            $paged=$paged->setPath(route('displayOneAuthResources',$team_id));
            //截取指定页数据
            $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);
            // pd($pageout);
            return view('Team/displayOneAuthResources',['resources'=>$pageout,'paged'=>$paged]);

        }
        else{
            return view('Team/displayOneAuthResources');
        }

    }
    public function displayOneAuthStask(Request $request,$team_id,User $user,Stask $stask,Stask_allocation $stask_allocation,Stask_submission $stask_submission,Team $team){
        //获取该团队信息
        $team_funder_id=$team->where(['team_id'=>$team_id])->value('team_funder_id');
        $stask_id=$request->input('stask_id');
        $stask_info=$stask->where(['stask_id'=>$stask_id])->get()->toArray();
        $stask_members=$stask_allocation->where(['stask_id'=>$stask_id])->get(['res_id'])->toArray();
        foreach ($stask_members as &$stask_member){
            $stask_member['res_name']=$user->where(['user_id'=>$stask_member['res_id']])->value('user_name');
            $stask_member['res_profile']=$user->where(['user_id'=>$stask_member['res_id']])->value('user_profile');
        }
        if(empty($request->input('flag'))){
            if($stask_info[0]['status']=='0'){
                return view('Team/displayOneAuthStask',['stask_members'=>$stask_members,'stask_info'=>$stask_info[0]]);
            }
            else{
                $file_path=$stask_submission->where(['stask_id'=>$request->input('stask_id')])->value('file');
                return view('Team/displayOneAuthStask',['stask_members'=>$stask_members,'stask_info'=>$stask_info[0],'task_manager_id'=>$team_funder_id,'file_path'=>$file_path]);
            }

        }
        else{
            $file_path=$this->submitStask($request,$stask_submission,$stask,$stask_allocation,$user);
            if($file_path==null){
                $result=false;
            }
            else{$result=true;}
            return view('Team/displayOneAuthStask',['stask_members'=>$stask_members,'stask_info'=>$stask_info[0],'isSubmitted'=>$result,'task_manager_id'=>$team_funder_id,'file_path'=>$file_path]);
        }
    }
    public function submitStask(Request $request,Stask_submission $stask_submission,Stask $stask){
        $request->validate([
            'stask_file'=>'required'
        ]);

        $file = $request->stask_file;
        // 判断文件是否上传成功
        if ($request->hasFile('stask_file') && $file->isValid()) {

            //资源存储文件夹
            $destinatePath = 'uploads/resources';
            //指定上传后的文件文件名
            $file_name = time()."-".$file->getClientOriginalName();
            //从上传临时位置转移到指定位置
            $file->move($destinatePath, $file_name);
            $file_path=$destinatePath.'/'.$file_name;
        }
        else{
            $file_path=null;
        }
        $fileExist=$stask_submission->where(['stask_id'=>$request->input('stask_id')])->get()->toArray();
        if(empty($fileExist)){

            $map_submission=[
                'submission_id'=>md5(uniqid(mt_rand(),true)),
                'stask_id'=>$request->input('stask_id'),
                'time'=>strtotime(date("Y-m-d H:i:s")),
                'file'=>$file_path
            ];
            $map=['stask_id'=>$request->input('stask_id')];
            $map_update=['status'=>'1'];
        }
        else{
            $map1=[
                'stask_id'=>$request->input('stask_id')
            ];
            $map_file_update=[
                'file'=>$file_path
            ];
        }
        try {
            //开始事务
            DB::beginTransaction();
            if(empty($fileExist)){
            $stask_submission->add($map_submission);
            $stask->edit($map,$map_update);}
            else{
                $stask_submission->edit($map1,$map_file_update);
            }
            //提交事务
            DB::commit();
            //返回前端添加成功结果

        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
        }
        return $file_path;
    }
    public function displayOneAuthStasks(Request $request,$team_id,Task $task,Team $team,User $user,Membership $membership,Friend $friend,TeamUploading $teamUploading,Stask_submission $stask_submission,Stask_allocation $stask_allocation,Stask $stask,Stask_comment $stask_comment){
        if(!empty($request->input('flag'))){
            $this->submitStask($request,$stask_submission,$stask,$stask_allocation,$user);
        }
        //获取该团队信息
        $teamInfo = $team->get(['team_id'=>$team_id])->toarray();
        //获取该团队创建者信息
        $funderName = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_name');
        $funderProfile = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_profile');
        //团队信息加入该团队创建者信息
        $teamInfo[0] = array_merge($teamInfo[0],['user_name'=>$funderName]);
        $teamInfo[0] = array_merge($teamInfo[0],['user_profile'=>$funderProfile]);
        //获取组员列表
        $teamMember = $membership->get(['team_id'=>$teamInfo[0]['team_id']])->toarray();
        // pd($teamMember);
        //该团队有组员，搜索成员信息
        $data['belong2team']=false;/*该属性用于判断当前用户是否属于该团队，false代表不属于该团队*/
        if(!empty($teamMember)){
            //对组员列表添加每个组员详细信息

            foreach ($teamMember as $key => &$value) {
                //获取单个组员信息
                $userInfo = $user->get(['user_id'=>$value['member_id']])->toArray();
                // pd($userInfo[0]);
                //组员列表添加该组员信息
                $value = array_merge($value,$userInfo[0]);

                /*判断用户是否属于该团队*/
                if($value['member_id']==session('user_id')){
                    $data['belong2team']=true;
                }
            }
        }
        //页码
        $page = $request->input('page')?$request->input('page'):1;

        //总数
        $total = count($teamMember);/*算上一个add icon*/
        //每页记录数
        $pageSize = 9;
        //实例化分页类
        $paged = new LengthAwarePaginator($teamMember,$total,$pageSize);
        //分页url
        $paged = $paged->setPath(route('displayOneTeam',['team_name'=>$teamInfo[0]['team_name']]));
        //要显示页的内容
        $pageOut = array_slice($teamMember,($page-1)*$pageSize,$pageSize);
        /*判断是否为最后一页*/
        if($page*$pageSize>=$total){
            $data['finalPage']=true;
        }
        else{
            $data['finalPage']=false;
        }

        /*用户的关注数量 与被关注数量*/
        $map1=[
            'followed_id'=>session('user_id')
        ];
        $map2 = [
            'follow_id'=>session('user_id')
        ];

        $data['followNum']=count($friend->get($map2)->toArray());
        $data['followerNum']=count($friend->get($map1)->toArray());

        //获取该团队动态信息
        $uploadings=$teamUploading->where(['team_id'=>$teamInfo[0]['team_id']])->orderBy('time','desc')->get()->toArray();
        //添加上传人的头像信息和姓名
        foreach ($uploadings as &$key){
            $uploader_info=$user->where(['user_id'=>$key['uploader_id']])->get()->toArray();
            $key['uploader_profile']=$uploader_info[0]['user_profile'];
            $key['uploader_name']=$uploader_info[0]['user_name'];
        }
        if(session('user_id')==$teamInfo[0]['team_funder_id']){
            $AuthOrNot='displayOneAuth';
        }
        else{
            $AuthOrNot='displayOne';
        }

        $tasks=$task->where(['task_team_id'=>$team_id,'task_allocation_status'=>'1'])->get()->toArray();
        $i=0;
        foreach ($tasks as &$aTask ) {
            /*获取每个任务下的子任务*/
            $tasks[$i]['stasks']=$stask->where(['task_id'=>$aTask['task_id']])->get()->toarray();
            $stasks =& $tasks[$i]['stasks'];
            $i = $i + 1;
            foreach ($stasks as &$aStask) {
                /*获取子任务的成员信息*/
                $aStask['members'] = $stask_allocation->where(['stask_id' => $aStask['stask_id']])->get(['res_id'])->toarray();
                foreach ($aStask['members'] as &$member){
                    $member['res_profile']=$user->where(['user_id'=>$member['res_id']])->value('user_profile');
                    $member['res_name']=$user->where(['user_id'=>$member['res_id']])->value('user_name');
                }
                if ($aStask['status'] != '0') {
                    $aStask['infors'] = $stask_submission->where(['stask_id' => $aStask['stask_id']])->get()->toArray();
                    $aStask['comments']=$stask_comment->where(['stask_id'=>$aStask['stask_id']])->orderBy('time','desc')->get()->toArray();
                    foreach($aStask['comments'] as &$comment){
                        $comment['commentator_profile']=$user->where(['user_id'=>$comment['commentator_id']])->value('user_profile');
                    }
                }
            }
        }

        return view('Team/displayOneAuthStasks',['stasks'=>$tasks,'team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged,'data'=>$data,'uploadings'=>$uploadings,'AuthOrNot'=>$AuthOrNot]);


    }
    public function deleteTeamUploading(Request $request,TeamUploading $teamUploading){

        $map=['uploading_id'=>$request->input('id')];
        try {
            //开始事务
            DB::beginTransaction();
           $teamUploading->del($map);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            if(session('applocale')=='en'){
                $msg='Deleted successfully!';
            }else{
                $msg = '删除成功！';
            }
            return response()->json(['msg'=>$msg]);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            if(session('applocale')=='en'){
                $msg='Busy network!Try again later!';
            }else{
                $msg = '网络繁忙，请稍后再试！';
            }
            return response()->json(['msg'=>$msg]);
        }
    }

}
