<?php 

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Team;
use App\Models\User;
use App\Models\Task;
use DB;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
	//显示增加任务页面
	public function displayAdd(Team $team,$team_name){
		$team_info = $team->get(['team_name'=>$team_name])->toArray();
		return view('Task/displayAdd',['team_id'=>$team_info[0]['team_id']]);
	}	
	
	//创建任务
	public function add(Task $task,Request $request){
		//对前端传参检查
		$request->validate([
			'team_id'=>'required',
			'task_name'=>'required',
			'task_descri'=>'required',
			'sDate'=>'required',
			'eDate'=>'required',
		]);
		$nowTime = strtotime(date("y-m-d h:i:s"));
		if(strtotime(($request->input('sDate')) > $nowTime)){
			$task_status = '0';
		}else{
			$task_status = '1';
		}
		// pd(['s'=>date($request->input('sDate')),'now'=>date(time())]);
        //插入任务表的数据
        $map = [
        	'task_id'=>md5(uniqid(mt_rand(),true)),
        	'task_name'=>$request->input('task_name'),
        	'task_team_id'=>$request->input('team_id'),
        	'task_status'=>$task_status,
        	'task_description'=>$request->input('task_descri'),
        	'task_manager_id'=>session('user_id'),
        	'task_deadline'=>strtotime(date($request->input('eDate'))),
        	'task_kickoff_date'=>strtotime(date($request->input('sDate')))
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $task->add($map);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg'=>'创建任务成功!','icon'=>'1']);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'创建任务失败！','icon'=>'2']);
        }
        // return response()->json(['msg'=>'创建任务成功!','icon'=>'1']);
	}

	//显示所有任务
    public function displayAll(Task $task,User $user,Team $team,Request $request){
    	$pagedata = $task->all()->toArray();
    	// pd($pagedata);
        // 默认页码
        $page = $request->input('page')?$request->input('page'):1;
        // pd($page);
        //设定一页行数
        $pagesize=2;
        //总共行数
        $total=count($pagedata);
        //实例化分页类
        $paged=new LengthAwarePaginator($pagedata,$total,$pagesize);
        //设置分页跳转路由
        $paged=$paged->setPath(route('displayAllTask'));
        //截取指定页数据
        $pageout=array_slice($pagedata, ($page-1)*$pagesize,$pagesize);
        foreach ($pageout as &$key ) {
    		$teamInfo = $team->get(['team_id'=>$key['task_team_id']])->toArray();
    		array_push($key,$teamInfo[0]['team_name']);
    		$userInfo = $user->get(['user_id'=>$key['task_manager_id']])->toArray();
    		array_push($key,$userInfo[0]['user_name']);
    	}
        // pd($pageout);
        return view('Task/displayAll',['tasks'=>$pageout,'paged'=>$paged]);
    }
}
?>
