<?php
namespace Admin\Model;
use Think\Model;

class SubjectModel extends Model{
    protected $tableName = 'subject';
	protected $pk        = 'subject_id';
	public    $error;

    /**
     * 获取主题信息
     */
    public function getSubjectInfo($subjectId){
        $info = $this->where(array('subject_id'=>$subjectId))->find();
        return $info;
    }
}