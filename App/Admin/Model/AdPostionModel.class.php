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
    public function getAdInfo($postionId){
        $info = $this->where(array('postion_id'=>$postionId))->find();
        return $info;
    }
}