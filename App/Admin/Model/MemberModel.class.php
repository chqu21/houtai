<?php
namespace Admin\Model;
use Think\Model;

class MemberModel extends Model{
    protected $tableName = 'member';
	protected $pk        = 'member_id';
	public    $error;

	
	/**
	 * 获取用户信息
	 */
	public function getTeacherInfo($teacherId){
	    $info = $this->where(array('teacher_id'=>$teacherId))->find();
	    return $info;
	}
    

}