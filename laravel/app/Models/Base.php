<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//模型基类
class Base extends Model{

	//取消使用时间戳
    public $timestamps = false;

	//通用增加操作
	public function add($map){
		return $this->insert($map);
	}
	//通用删除操作
	public function del($map){
		return $this->where($map)->delete($map);		
	}
	//通用查找操作
	public function get($map){
		return $this->where($map)->get();
	}
	//通用修改操作
	public function edit($map,$data){
		return $this->where($map)->update($data);
	}
}