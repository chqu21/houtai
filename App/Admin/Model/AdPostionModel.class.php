<?php
namespace Admin\Model;
use Think\Model;

class AdModel extends Model{
    protected $tableName = 'ad';
	protected $pk        = 'ad_id';
	public    $error;


    /**
     * 获取用户信息
     */
    public function getAdInfo($teacherId){
        $info = $this->where(array('ad_id'=>$teacherId))->find();
        return $info;
    }
}