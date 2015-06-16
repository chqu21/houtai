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
        $joinStr =  C('DB_PREFIX').'member A ON A.member_id = '.C('DB_PREFIX').'member_info.member_id';
	    $info = $this->join($joinStr)->where(array('A.member_id'=>$memberId))->find();
        return $info;
	}

    public function editMemberInfo($mId,$data){
        $member_info_db = D('MemberInfo');
        $member_db = D('Member');
        $dt = array('mobile'=>$data['mobile'],'password'=>$data['password']);
        try{
            $this->startTrans();
            $member_info_db->where(array('member_id'=>$mId))->save($data);
            $member_db->where(array('member_id'=>$mId))->save($dt);
            $this->commit();
        } catch (ThinkException $e) {
            $this->rollback();
        }
        return true;
    }
    

}