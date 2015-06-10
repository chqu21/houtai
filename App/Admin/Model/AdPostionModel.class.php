<?php
namespace Admin\Model;
use Think\Model;

class AdPostionModel extends Model{
    protected $tableName = 'ad_postion';
	protected $pk        = 'postion_id';
	public    $error;


    /**
     * 获取用户信息
     */
    public function getAdInfo($teacherId){
        $info = $this->where(array('ad_id'=>$teacherId))->find();
        return $info;
    }
}