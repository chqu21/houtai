<?php
namespace Admin\Model;
use Think\Model;

class MemberAddressModel extends Model{
    protected $tableName = 'member_address';
	protected $pk        = 'address_id';
	public    $error;



    /**
     * 获取课程信息
     */
    public function getMemberAddress($mId){
        $info = $this->where(array('member_id'=>$mId))->find();
        return $info;
    }


}