<?php 

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Storage;
use App\Models\Team;
use App\Models\User;
use App\Models\Task;
use App\Models\Stask;
use App\Models\Membership;
Use App\Models\Stask_allocation;
use App\Models\TaskTransaction;
use App\Models\TeamUploading;
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
	public function add(Task $task,Request $request,TaskTransaction $taskTransaction,TeamUploading $teamUploading){
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
        	'task_kickoff_date'=>strtotime(date($request->input('sDate'))), 
        	'task_allocation_status'=>'0'
        ];
        $map_trans = [
            'tran_id'=>md5(uniqid(mt_rand(),true)),
            'trans_brief'=>"Task Created",
            'trans_description'=>$request->input('task_descri'),
            'task_id'=>$task_id,
            'time'=>strtotime(date("Y-m-d H:i:s"))
        ];
        $map_uploading = [
            'id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$request->input('team_id'),
            'uploader_id'=>session('user_id'),
            'time'=>strtotime(date("Y-m-d H:i:s")),
            'content'=>'Hey guys,I have published a task just right now!!Come over and check out the detail!'
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $task->add($map);
            $taskTransaction->add($map_trans);
            $teamUploading->add($map_uploading);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
            return response()->json(['msg'=>'创建任务成功!','icon'=>'1','task_id'=>$map['task_id'],'team_id'=>$map['task_team_id']]);
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
            return response()->json(['msg'=>'创建任务失败！','icon'=>'2']);
        }
        // return response()->json(['msg'=>'创建任务成功!','icon'=>'1']);
	}

	//显示所有任务
    public function displayAll(User $user,Team $team,Request $request,TaskTransaction $taskTransaction,TeamUploading $teamUploading,Task $task){
	     if(empty($request->input('flag'))){

       }
       else{
           $this->createTransaction($taskTransaction,$request,$teamUploading,$task);
       }

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

    	}
        // pd($pageout);


        return view('Task/displayAll',['tasks'=>$pageout,'paged'=>$paged]);
    }
    //显示子任务分配界面
    public function displayAllocateSubTask(Request $request,Team $team,Task $task,$task_id,$team_id){
    	$teamInfo = $team->get(['team_id'=>$team_id])->toArray();
    	$team_name = $teamInfo[0]['team_name'];
    	$taskInfo = $task->get(['task_id'=>$task_id])->toArray();
    	$task_name = $taskInfo[0]['task_name'];    	
    	// pd($team_user_list);
    	$return_data = [
    		'team_name'=>$team_name,
    		'task_name'=>$task_name,
    		'task_id'=>$task_id,
    		'team_id'=>$team_id
    	];

        return view('Task/displayAllocateSubTask',['return_data'=>$return_data]);

    }
    public function createTransaction(TaskTransaction $taskTransaction,Request $request,TeamUploading $teamUploading,Task $task){


         $request->validate([
             'task_id'=>'required',
             'trans_brief'=>'required',
             'trans_description'=>'required'
         ]);
        $file = $request->trans_Resource_Path;

        // 判断文件是否上传成功
        if ($request->hasFile('trans_Resource_Path') && $file->isValid()) {

            //资源存储文件夹
            $destinatePath = 'uploads/resources';
            //指定上传后的文件文件名
            $file_name = time()."-".$file->getClientOriginalName();
            //从上传临时位置转移到指定位置
            $file->move($destinatePath, $file_name);
            $trans_Resource_path=$destinatePath.'/'.$file_name;
        }
        else{
            $trans_Resource_path=null;
        }
        $task_info=$task->where(['task_id'=>$request->input('task_id')])->get()->toArray();

        $map_trans = [
            'tran_id'=>md5(uniqid(mt_rand(),true)),
            'trans_brief'=>$request->input('trans_brief'),
            'trans_description'=>$request->input('trans_description'),
            'task_id'=>$request->input('task_id'),
            'time'=>strtotime(date("Y-m-d H:i:s")),
            'trans_Resource_path'=>$trans_Resource_path,
            'trans_Resource_intro'=>$request->input('trans_Resource_intro')
        ];
        $map_uploading = [
            'id'=>md5(uniqid(mt_rand(),true)),
            'team_id'=>$task_info[0]['task_team_id'],
            'uploader_id'=>$task_info[0]['task_manager_id'],
            'time'=>strtotime(date("Y-m-d H:i:s")),
            'content'=>'Hey guys,I have updated progress of task :'.$task_info[0]['task_name']." ! Come over and check out the detail !",
            'resource'=>$trans_Resource_path,
        ];
        try {
            //开始事务
            DB::beginTransaction();
            $taskTransaction->add($map_trans);
            $teamUploading->add($map_uploading);
            //提交事务
            DB::commit();
            //返回前端添加成功结果
        } catch(QueryException $ex) {
            //回滚事务
            DB::rollback();
            //返回前端添加失败结果
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
    //获得该团队的队员
    public function getTeamUsers(Request $request,Membership $membership,User $user){
    	$request->validate([
            'team_id'=>'required'
        ]);
        //获取该团队队员user_id
        $memberships = $membership->get(['team_id'=>$request->input('team_id')])->toArray();
        //构造该团队队员信息列表
        $team_user_list = [];
        foreach ($memberships as $key) {
            //单个队员信息
            $userInfo = [];
            $user_tmp = $user->get(['user_id'=>$key['member_id']])->toArray();
            array_push($userInfo,['user_id'=>$key['member_id']]);
            array_push($userInfo,['user_name'=>$user_tmp[0]['user_name']]);
            array_push($team_user_list,$userInfo);
        }
        return response()->json(['team_user_list'=>$team_user_list]);
    }
    //分配指定任务的子任务名、子任务描述及子任务对应组员
    public function allocateSub(Request $request,Stask $stask,Stask_allocation $stask_allocation,TaskTransaction $taskTransaction,TeamUploading $teamUploading,Task $task){
    	$sub_task_all = $request->input('sub_task_all');
    	$isSuccessfull = true;
    	foreach($sub_task_all as $key ){
    		$flag = true ;
    		$stask_id=md5(uniqid(mt_rand(),true));
    		$map = [
    			'stask_id'=>$stask_id,
    			'stask_name'=>$key['sub_task_name'],
    			'stask_description'=>$key['sub_task_descri'],
    			'task_id'=>$request->input('task_id'),
    			'status'=>'0'
    		];
            /*更新transaction列表*/
            $map_trans = [
                'tran_id'=>md5(uniqid(mt_rand(),true)),
                'trans_brief'=>"Created sub task : ".$key['sub_task_name'],
                'trans_description'=>$key['sub_task_descri'],
                'task_id'=>$request->input('task_id'),
                'time'=>strtotime(date("Y-m-d H:i:s")),
                'trans_Resource_path'=>$stask_id,
                'trans_Resource_intro'=>'stask'
            ];
            $task_info=$task->where(['task_id'=>$request->input('task_id')])->get()->toArray();
            $map_uploading = [
                'id'=>md5(uniqid(mt_rand(),true)),
                'team_id'=>$task_info[0]['task_team_id'],
                'uploader_id'=>session('user_id'),
                'time'=>strtotime(date("Y-m-d H:i:s")),
                'content'=>'Hey guys,I have updated progress of task :'.$task_info[0]['task_name']." ! Come over and check out the detail !",
                'resource'=>null,
            ];
    		try {
	            //开始事务
	            DB::beginTransaction();
	            $stask->add($map);
	            $taskTransaction->add($map_trans);
	            $teamUploading->add($map_uploading);
	            //提交事务
	            DB::commit();	            
	        } catch(QueryException $ex) {
	            //回滚事务
	            DB::rollback();
	            $isSuccessfull = false ;
	            breask;
	        }
	        foreach($key['sub_task_users'] as $key1){
	        	$map1 = [
	        		'a_id'=>md5(uniqid(mt_rand(),true)),
	        		'stask_id'=>$map['stask_id'],
	        		'res_id'=>$key1
	        	];
	        	try {
		            //开始事务
		            DB::beginTransaction();
		            $stask_allocation->add($map1);
		            //提交事务
		            DB::commit();	            
	       		} catch(QueryException $ex) {
		            //回滚事务
		            DB::rollback();
		            $isSuccessfull = false ;
		            $flag = false ;
		            breask;
	       		}
	        }
	        if(!$flag){
	        	break;
	        }
    	}
    	if($isSuccessfull){
			//返回前端添加成功结果
            return response()->json(['msg' => 'Allocate Sub-task Successfully!']);
    	}else{
			//返回前端添加失败结果
	         return response()->json(['msg' => 'Failed To Allocate Sub-task!']);
    	}
    }


}





