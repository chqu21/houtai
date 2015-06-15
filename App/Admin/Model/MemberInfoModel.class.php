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
	public function getMemberInfo($memberId){
	    $info = $this->where(array('member_id'=>$memberId))->find();
	    return $info;
	}
    

}