<?php
namespace Admin\Model;
use Think\Model;

class ServiceCommentsModel extends Model{
    protected $tableName = 'service_comments';
	protected $pk        = 'comments_id';
	public    $error;

    /**
     * 获取评价信息
     */
    public function getCommentsInfo($commentsId){
        $info = $this->where(array('comments_id'=>$commentsId))->find();
        return $info;
    }
}