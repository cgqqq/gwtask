<?php 

namespace App\Http\Controllers\Task;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Team;
use App\Models\User;
use App\Models\Task;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
	//显示增加任务页面
	public function displayAdd(Task $task,Team $team,$team_name){
		$team_info = $team->get(['team_name'=>$team_name])->toArray();
		return view('Task/displayAdd',['team_id'=>$team_info[0]['team_id']]);
	}	
	
}
?>
