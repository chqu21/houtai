<?php
namespace Admin\Model;
use Think\Model;

class MemberInfoModel extends Model{
    protected $tableName = 'member_info';
	protected $pk        = 'member_info_id';
	public    $error;

	
	/**
	 * 获取用户信息
	 */
	public function getTeacherInfo($teacherId){
	    $info = $this->where(array('teacher_id'=>$teacherId))->find();
	    return $info;
	}
    

}