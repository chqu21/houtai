<?php
namespace Admin\Model;
use Think\Model;

class GroupCourseModel extends Model{
    protected $tableName = 'group_course';
	protected $pk        = 'course_id';
	public    $error;

    /**
     * 获取课程信息
     */
    public function getCourseInfo($courseId){
        $info = $this->where(array('course_id'=>$courseId))->find();
        return $info;
    }

}