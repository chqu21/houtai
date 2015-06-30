<?php
namespace Admin\Model;
use Think\Model;

class CourseModel extends Model{
    protected $tableName = 'course';
	protected $pk        = 'course_id';
	public    $error;

    public  $classMethod = array(
        1 => '一对一(学生上门)',
        2 => '一对一(教师外出)',
        3 => '小组课（2～5人,学生上门）',
        4 => '小班课（6～10人,学生上门）',
        5 => '大班课（10人以上,学生上门)'
    );

    /**
     * 获取课程信息
     */
    public function getCourseInfo($courseId){
        $info = $this->where(array('course_id'=>$courseId))->find();
        return $info;
    }

    public function getCourseList($tId){
        $info = $this->where(array('teacher_id'=>$tId))->select();
        return $info;
    }

    public function addCourseByClassMethodId($tId,$data){
        try{
            $teacher_db =  D('Teacher');
            $address_db =  D('MemberAddress');
            $teacherInfo = $teacher_db->where("teacher_id=$tId")->find();
            $this->startTrans();
            foreach($data as $v){
                $cInfo = $this->where("teacher_id=$tId AND class_method_id=$v")->find();
                if (empty($cInfo)){
                    $adr = $v <> '2' ? $address_db->getMemberAddress($teacherInfo['member_id']) : '';
                    $data = array(
                        'teacher_id' => $tId,
                        'class_method_id'=> $v,
                        'class_method' => $this->classMethod[$v],
                        'course_name' => $teacherInfo['discipline'],
                        'teacher_name' => $teacherInfo['teacher_name'],
                        'course_category' => $teacherInfo['course_category'],
                        'class_address' => !empty($adr) ? ($adr['city'].$adr['district'].$adr['address_detail']) : '',
                        'display' => 1,
                    );
                    $this->add($data);
                }
            }
            $this->commit();
        } catch (ThinkException $e) {
            $this->rollback();
        }
        return true;
    }


}