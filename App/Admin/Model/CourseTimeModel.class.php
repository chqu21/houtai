<?php
namespace Admin\Model;
use Think\Model;

class CourseTimeModel extends Model{
    protected $tableName = 'course_time';
	protected $pk        = 'course_time_id';
	public    $error;


/**
 * 复制课程时间
 * @param  [array] $data ['begin_time','end_time','month']
 * @return [bollan]       ['true','false']
 */
    public function copyTimes($data){
            $beginDate = $data['begin_date'];
            $endDate = $data['end_date'];
            $rs = $this->where("`class_date`>='".$beginDate."' and `class_date`<='".$endDate."'")->select();       
            $newCourseTime = array();

            try{
                $this->startTrans();
                foreach($rs as $k => $v){
                	    $tId = $v['teacher_id'];
                        $dT = explode('-',$v['class_date']);
                        $tDate = $dT[0].'-'.$data['month'].'-'.$dT[2];
                        $newCourseTime['class_date'] = $tDate;
                        $newCourseTime['course_id'] = $v['course_id'];
                        $newCourseTime['teacher_id'] = $tId;
                        $newCourseTime['teacher_name'] = $v['teacher_name'];
                        $newCourseTime['class_time'] = json_encode(json_decode($v['class_time']));
                        $newCourseTime['teacher_no_time'] = $v['teacher_no_time'];
                        $newCourseTime['class_time'] = $v['class_time'];
                        $newCourseTime['class_week'] = $v['class_week'];
                        $newCourseTime['teacher_no_time'] = 0;
                        $newCourseTime['raw_add_time'] = date('Y-m-d H:i:s');
                        $r = $this->where("teacher_id = '".$tId."' AND class_date = '".$tDate."'")->select();
                        if (empty($r)){
                          $result = $this->add($newCourseTime);
                          if (empty($result)){
                             $this->rollback();
                             return false;
                           }                     	
                        }

                    
                   }

                    $this->commit();
                    return true;
                } catch (ThinkException $e) {                
                    $this->rollback();
                    return false;
                }
    }

}