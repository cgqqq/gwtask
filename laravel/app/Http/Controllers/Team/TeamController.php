<?php

namespace App\Http\Controllers\Team;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use App\Models\Team;
use App\Models\User;
use App\Models\Friend;
use App\Models\Membership;
use DB;

use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    //创建团队
    public function add(Request $request,Team $team,Membership $membership){
        //对前端传参检查
        $request->validate([
            'team_name'=>'required',
            'team_info'=>'required|max:1000'
        ]);
        //团队信息
        $data_team = [
            'team_id'=>md5(uniqid(mt_rand(),true)),
            'team_name'=>$request->input('team_name'),
            'created_at'=>strtotime(date("Y-m-d H:i:s")),
            'team_funder_id'=>session('user_id'),
            'team_info'=>$request->input('team_info')
        ];
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
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg'=>'添加成功!','icon'=>'1']);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'该团队已存在！','icon'=>'2']);
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
            return response()->json(['msg'=>'退出成功!','icon'=>'1']);
        }else{
            //组员请求退出，退出失败
            return response()->json(['msg'=>'退出失败!','icon'=>'2']);
        }
    }
    //显示单个团队信息、成员
    public function displayOne(User $user,Membership $membership,Team $team,Request $request,$team_name,Friend $friend){
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



        //要显示的页面；传给前端的分页信息：团队介绍、队员信息、分页；
        // pd($pageOut);
        if($funderName==session('user_name')){
            return view('Team/displayOneAuth',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged,'data'=>$data]);
        }else{
            return view('Team/displayOne',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged,'data'=>$data]);
        }

    }
    //移除团队成员
    public function removeMember(Request $request,Membership $membership,Team $team){
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
            return response()->json(['msg'=>'Your invitation has been successfully sent!!','icon'=>'1']);
       } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'Network is busy now,try again later！','icon'=>'2']);
        }

    }


}
