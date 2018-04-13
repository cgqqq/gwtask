<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserUpdating;
use App\Models\Friend;
use App\Models\User;
use App\Models\TeamUploading;
use App\Models\Team;
use Illuminate\Pagination\LengthAwarePaginator;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function home(Request $request,UserUpdating $userUpdating,Friend $friend,User $user,Membership $membership,TeamUploading $teamUploading,Team $team){
        if(empty($request->input('flag'))){

        }
        else{
            $request->validate([
                'updater_id'=>'required',
                'content'=>'required'
            ]);
            $file = $request->resource;

            // 判断文件是否上传成功
            if ($request->hasFile('resource') && $file->isValid()) {

                //资源存储文件夹
                $destinatePath = 'uploads/resources';
                //指定上传后的文件文件名
                $file_name = time()."-".$file->getClientOriginalName();
                //从上传临时位置转移到指定位置
                $file->move($destinatePath, $file_name);
                $resource=$destinatePath.'/'.$file_name;
                $type='sResource';
            }
            else{
                $resource=null;
                $type='pUpdating';
            }
            $map_updater = [
                'updating_id'=>md5(uniqid(mt_rand(),true)),
                'updater_id'=>$request->input('updater_id'),
                'time'=>strtotime(date("Y-m-d H:i:s")),
                'content'=>$request->input('content'),
                'resource'=>$resource,
                'type'=>$type
            ];
            try {
                //开始事务
                DB::beginTransaction();
                $userUpdating->add($map_updater);
                //提交事务
                DB::commit();
                //返回前端添加成功结果
            } catch(QueryException $ex) {
                //回滚事务
                DB::rollback();
                //返回前端添加失败结果
            }
        }
        $friendsList=$friend->where(['follow_id'=>session('user_id')])->get(['followed_id'])->toArray();
        $index=0;
        foreach($friendsList as &$key){
            $key['friendUpdatings']=$userUpdating->where(['updater_id'=>$key['followed_id']])->orderBy('time','desc')->get()->toArray();
            $index=$index+1;
        }
        $j=0;
        $results=null;
        foreach($friendsList as &$key1){
            foreach ($key1['friendUpdatings'] as &$key2){
                $key2['user_profile']=$user->where(['user_id'=>$key2['updater_id']])->value('user_profile');
                $key2['user_name']=$user->where(['user_id'=>$key2['updater_id']])->value('user_name');
                $results[$j]=&$key2;
                $j=$j+1;
            }
        }
        $MineUpdatings=$userUpdating->where(['updater_id'=>session('user_id')])->orderBy('time','desc')->get()->toArray();
        foreach ($MineUpdatings as &$mineUpdating){
            $mineUpdating['user_profile']=$user->where(['user_id'=>$mineUpdating['updater_id']])->value('user_profile');
            $mineUpdating['user_name']=$user->where(['user_id'=>$mineUpdating['updater_id']])->value('user_name');
            $results[$j]=&$mineUpdating;
            $j=$j+1;
        }
        if($results!=null){
            $sort = array_column($results, 'time');
            array_multisort($sort, SORT_DESC, $results);
            $page = 1;
            if ($request->input('page')) {
                $page = $request->input('page');
            }
            // pd($page);
            //设定一页行数
            $pageSize =5;
            //总共行数
            $total = count($results);
            //实例化分页类啊
            $paged = new LengthAwarePaginator($results, $total, $pageSize);
            //设置分页跳转路由
            $paged = $paged->setPath(route('home'));
            //截取指定页数据
            $pageOut = array_slice($results, ($page - 1) * $pageSize, $pageSize);
            // pd($pageOut);
        }

        $teamArray=$membership->where(['member_id'=>session('user_id')])->get(['team_id'])->toarray();

        $teamUpdatings=null;
        $i=0;
        foreach($teamArray as $aTeam){
            $teamUploadings=$teamUploading->where(['team_id'=>$aTeam['team_id']])->get()->toarray();
            foreach ($teamUploadings as &$item) {
                $item['user_profile']=$user->where(['user_id'=>$item['uploader_id']])->value('user_profile');
                $item['user_name']=$user->where(['user_id'=>$item['uploader_id']])->value('user_name');
                $item['team_name']=$team->where(['team_id'=>$item['team_id']])->value('team_name');
                $teamUpdatings[$i]=&$item;
                $i=$i+1;
            }
        }
        $myTeamUploadings=$teamUploading->where(['uploader_id'=>session('user_id')])->get()->toarray();
        foreach ($myTeamUploadings as &$item) {
            $item['user_profile']=$user->where(['user_id'=>$item['uploader_id']])->value('user_profile');
            $item['user_name']=$user->where(['user_id'=>$item['uploader_id']])->value('user_name');
            $item['team_name']=$team->where(['team_id'=>$item['team_id']])->value('team_name');
            $teamUpdatings[$i]=&$item;
            $i=$i+1;
        }
        if($teamUpdatings!=null){
            $sortT = array_column($teamUpdatings, 'time');
            array_multisort($sortT, SORT_DESC, $teamUpdatings);
        }
        if(!empty($pageOut)){
            return view('Home/home',['friendsUpdatings'=>$pageOut,'paged'=>$paged,'teamUpdatings'=>$teamUpdatings]);
        }
        else{
            return view('Home/home',['teamUpdatings'=>$teamUpdatings]);
        }
    }
    public function display(Request $request,User $user){
        if(empty($request->input('flag'))){
            return view('Index/register');
        }
        else {
            $email = $request->input('email');
            $result = $user->where(['user_email' => $email])->get()->toArray();
            if($result!=null){
                return response()->json(['msg'=>'The email address has been registered !']);
            }
            else{
                 return response()->json(['msg'=>'1']);
            }
        }


    }
}
