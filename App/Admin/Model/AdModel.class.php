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
    public function getAdInfo($postionId){
        $info = $this->where(array('postion_id'=>$postionId))->find();
        return $info;
    }

    //清除设置相关缓存
    public function clearCatche(){
        S('setting_site', null);
        S('common_setting_behavior', null);
    }
}