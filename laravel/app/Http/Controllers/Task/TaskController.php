<?php 

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Team;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskTransaction;
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
	public function add(Task $task,Request $request,TaskTransaction $taskTransaction){
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
		$task_id=md5(uniqid(mt_rand(),true));
        $map = [
        	'task_id'=>$task_id,
        	'task_name'=>$request->input('task_name'),
        	'task_team_id'=>$request->input('team_id'),
        	'task_status'=>$task_status,
        	'task_description'=>$request->input('task_descri'),
        	'task_manager_id'=>session('user_id'),
        	'task_deadline'=>strtotime(date($request->input('eDate'))),
        	'task_kickoff_date'=>strtotime(date($request->input('sDate')))
        ];
        $map_trans = [
            'tran_id'=>md5(uniqid(mt_rand(),true)),
            'trans_brief'=>"Task Created",
            'trans_description'=>$request->input('task_descri'),
            'task_id'=>$task_id,
            'time'=>strtotime(date("Y-m-d H:i:s"))
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $task->add($map);
            $taskTransaction->add($map_trans);
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
    public function displayAll(Task $task,User $user,Team $team,Request $request,TaskTransaction $taskTransaction){

    	$pagedata = $task->all()->toArray();


    	// pd($pagedata);
        // 默认页码
        $page = $request->input('page')?$request->input('page'):1;
        // pd($page);
        //设定一页行数
        $pagesize=10;
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
			if($key['status']=='1'){
                if($key['task_deadline']<time()){
                    $timeLeft="Task Expried";
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
			$key['trans']=&$trans;
    	}
        // pd($pageout);


        return view('Task/displayAll',['tasks'=>$pageout,'paged'=>$paged]);
    }
    public function createTransaction(TaskTransaction $taskTransaction,Request $request){
        $request->validate([
            'task_id'=>'required',
            'trans_brief'=>'required',
            'trans_description'=>'required'
        ]);
       /* $file = $request->trans_Resource_Path;

        // 判断文件是否上传成功
        if ( $file->isValid()) {

            //资源存储文件夹
            $destinatePath = 'uploads/resources';
            //获得上传文件的文件名
            $file_name = $file->getClientOriginalName()."/".time();
            //从上传临时位置转移到指定位置
            $file->move($destinatePath, $file_name);
            $trans_Resource_path=$destinatePath.'/'.$file_name;
        }
        else{
            $trans_Resource_path=null;
        }*/

        $map_trans = [
            'tran_id'=>md5(uniqid(mt_rand(),true)),
            'trans_brief'=>$request->input('trans_brief'),
            'trans_description'=>$request->input('trans_description'),
            'task_id'=>$request->input('task_id'),
            'time'=>strtotime(date("Y-m-d H:i:s")),
            'trans_Resource_path'=>null,
            'trans_Resource_intro'=>$request->input('trans_Resource_intro')
        ];

        try {
            //开始事务
            DB::beginTransaction();
            $taskTransaction->add($map_trans);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg' => 'success!']);

        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg' => 'fail!']);
        }
    }
    
    public function deleteTransaction(TaskTransaction $taskTransaction,Request $request){
        $request->validate([
            'tran_id'=>'required'
        ]);
        $map=[
            'tran_id'=>$request->input('tran_id')
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $taskTransaction->where($map)->delete($map);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg' => 'Delete Successfully!']);

        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg' => 'Busy Network!Try Again!']);
        }

    }

}





