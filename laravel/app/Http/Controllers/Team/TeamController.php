<?php

namespace App\Http\Controllers\Team;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Pagination\LengthAwarePaginator;
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
    	if($team->add($data_team)&&$membership->add($data_membership)){
            //添加成功
    		return response()->json(['msg'=>'添加成功!','icon'=>'1']);
    	}else{
            //添加失败
    		return response()->json(['msg'=>'添加失败!','icon'=>'2']);
    	}
    }
    //显示为我的团队
    public function displayMine(Team $team,Membership $membership,Request $request){
        //我的所有团队记录
        $pagedata = $membership
        ->where(['member_id'=>session('user_id')])
        ->join('team','membership.team_id','=','team.team_id')
        ->join('user','user.user_id','=','team.team_funder_id')
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
        $paged=$paged->setPath(route('displayMyTeam'));
        //截取指定页数据
        $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);
        // pd($pageout);
        return view('Team/displayMine',['pageout'=>$pageout,'paged'=>$paged]);        
    }
    //显示所有团队
    public function displayAll(Team $team){
    	$data = $team->paginate(2);

    	return view('Team/displayAll',['teams'=>$data]);
    }
    //加入团队
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
    public function displayOne(User $user,Membership $membership,Team $team,Request $request,$team_name){
        //获取该团队信息
        $teamInfo = $team->get(['team_name'=>$team_name])->toarray();
        //获取该团队创建者信息
        $funderName = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_name');
        //团队信息加入该团队创建者信息
        $teamInfo[0] = array_merge($teamInfo[0],['user_name'=>$funderName]);
        //获取组员列表
        $teamMember = $membership->get(['team_id'=>$teamInfo[0]['team_id']])->toarray();
        //对组员列表添加每个组员详细信息
        foreach ($teamMember as $key => &$value) {
            //获取单个组员信息
            $userInfo = $user->get(['user_id'=>$value['member_id']])->toArray();
            //组员列表添加该组员信息
            $value = array_merge($value,$userInfo[0]);
        }
        //页码
        $page = $request->input('page')?$request->input('page'):1;
        //每页记录数
        $pageSize = 2;
        //总数
        $total = count($teamMember);
        //实例化分页类
        $paged = new LengthAwarePaginator($teamMember,$total,$pageSize);
        //分页url
        $paged = $paged->setPath(route('displayOneTeam',['team_name'=>$teamInfo[0]['team_name']]));
        //要显示页的内容
        $pageOut = array_slice($teamMember,($page-1)*$pageSize,$pageSize);
        //要显示的页面；传给前端的分页信息：团队介绍、队员信息、分页；
        if($funderName==session('user_name')){
            return view('Team/displayOneAuth',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged]);
        }else{
            return view('Team/displayOne',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged]);
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
    //显示用户搜索已加入团队的结果信息
    public function displaySearchMine(Membership $membership,Team $team,Request $request,User $user){
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
        $paged=$paged->setPath(route('displayMyTeam'));
        //截取指定页数据
        $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);
        // pd($pageout);
        return view('Team/displayMine',['pageout'=>$pageout,'paged'=>$paged]);   
        
    }
}
