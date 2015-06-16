<?php
namespace Admin\Model;
use Think\Model;

class CourseModel extends Model{
    protected $tableName = 'course';
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