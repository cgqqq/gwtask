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
    	$request->validate([
    		'team_name'=>'required',
    		'team_info'=>'required|max:1000'
    	]);
    	$data_team = [
    		'team_id'=>md5(uniqid(mt_rand(),true)),
    		'team_name'=>$request->input('team_name'),
    		'created_at'=>strtotime(date("Y-m-d H:i:s")),
    		'team_funder_id'=>session('user_id'),
    		'team_info'=>$request->input('team_info')
    	];
        $data_membership = [
            'membership_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$data_team['team_id'],            
            'member_id'=>session('user_id'),
        ];
    	if($team->add($data_team)&&$membership->add($data_membership)){
    		return response()->json(['msg'=>'添加成功!','icon'=>'1']);
    	}else{
    		return response()->json(['msg'=>'添加失败!','icon'=>'2']);
    	}
    }
    //显示为我的团队
    public function displayMine(Team $team,Membership $membership,Request $request){
        $pagedata = $membership->where(['member_id'=>session('user_id')])->get()->toarray();
        foreach ($pagedata as $key => &$value) {
            $team_info = $team->get(['team_id'=>$value['team_id']])->toarray();
            $value = array_merge($value,reset($team_info)?$team_info['0']:array());
        }
        // pd($pagedata);
        $page=1;
        if($request->input('page'))
        {
            $page=$request->input('page');
        }
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
        $request->validate([
            'team_id'=>'required',
            'member_id'=>'required'
        ]);
        $map = [
            'team_id'=>$request->input('team_id'),
            'member_id'=>session('user_id')
        ];
        $data = [
            'membership_id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$request->input('team_id'),
            'member_id'=>$request->input('member_id')
        ];
        if(!$membership->get($map)->isEmpty()){
            return response()->json(['msg'=>'已加入!','icon'=>'7']);
        }else if($membership->add($data)){
            return response()->json(['msg'=>'加入成功!','icon'=>'1']);
        }else{
            return response()->json(['msg'=>'加入失败!','icon'=>'2']);
        }
    }
    //离开团队
    public function quit(Request $request,Membership $membership,Team $team){
        $request->validate([
            'team_id'=>'required'
        ]);
        $map = [
            'team_id' => $request->input('team_id'),
            'team_funder_id'=>session('user_id')
        ];
        //暂定队长不能退出团队
        if(!$team->get($map)->isEmpty()){
            return response()->json(['msg'=>'队长不能退出!','icon'=>'2']);
        }else if($membership->del(['team_id'=>$request->input('team_id')])){
            return response()->json(['msg'=>'退出成功!','icon'=>'1']);
        }else{
            return response()->json(['msg'=>'退出失败!','icon'=>'2']);
        }
    }
    //显示单个团队信息、成员
    public function displayOne(User $user,Membership $membership,Team $team,Request $request,$team_name){
        $teamInfo = $team->get(['team_name'=>$team_name])->toarray();
        $funderInfo = $user->where(['user_id'=>$teamInfo[0]['team_funder_id']])->value('user_name');
        $teamInfo[0] = array_merge($teamInfo[0],['user_name'=>$funderInfo]);
        $teamMember = $membership->get(['team_id'=>$teamInfo[0]['team_id']])->toarray();
        foreach ($teamMember as $key => &$value) {
            $userInfo = $user->get(['user_id'=>$value['member_id']])->toArray();
            $value = array_merge($value,$userInfo[0]);
        }

        $page = $request->input('page')?$request->input('page'):1;
        $pageSize = 2;
        $total = count($teamMember);
        $paged = new LengthAwarePaginator($teamMember,$total,$pageSize);
        $paged = $paged->setPath(route('displayOneTeam',['team_name'=>$teamInfo[0]['team_name']]));
        $pageOut = array_slice($teamMember,($page-1)*$pageSize,$pageSize);

        return view('Team/displayOne',['team_info'=>$teamInfo[0],'pageOut'=>$pageOut,'paged'=>$paged]);
    }
    //移除团队成员
    public function removeMember(Request $request,Membership $membership,Team $team){
        $request->validate([
            'team_name'=>'required',
            'user_list'=>'requied'
        ]);
        $data = [
            'msg'=>null,
            'icon'=>null
        ];
        //团队名称
        $team_name = $request->input('team_name');
        //根据团队名称获取团队信息
        $teamInfo = $team->get(['team_name'=>$team_name])->toArray;
        //获取团队id
        $team_id = $teamInfo[0]['team_id'];
        $usr_list = $request->input('user_list');
        $user_list = json_decode(user_list);
        foreach ($user_list as $key ) {
            
        }
    }
}
