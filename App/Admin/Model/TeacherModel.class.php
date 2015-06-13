<?php
namespace Admin\Model;
use Think\Model;

class TeacherModel extends Model{
    protected $tableName = 'teachers';
	protected $pk        = 'teacher_id';
	public    $error;

	
	/**
	 * 获取用户信息
	 */
	public function getTeacherInfo($teacherId){
	    $info = $this->where(array('teacher_id'=>$teacherId))->find();
	    return $info;
	}

    public function addTeacher($data){
        $teachingAge = $data['teaching_age'];
        if ($teachingAge <= 5){
            $teachingAgeRange = 1;
        }elseif($teachingAge > 5 and $teachingAge<=10){
            $teachingAgeRange = 2;
        }elseif($teachingAge > 10 and $teachingAge<=15){
            $teachingAgeRange = 3;
        }elseif($teachingAge > 15 and $teachingAge<=20){
            $teachingAgeRange = 4;
        }elseif($teachingAge > 20){
            $teachingAgeRange = 5;
        }
        $price = $data['price'];
        if ($price <= 100){
            $priceRange = 1;
        }elseif($price > 100 and $price<=150){
            $priceRange = 2;
        }elseif($price > 150 and $price<=200){
            $priceRange = 3;
        }elseif($price > 200 and $price<=250){
            $priceRange = 4;
        }elseif($price > 250 and $price<=300){
            $priceRange = 5;
        }elseif($price > 300){
            $priceRange = 6;
        }
        $data['price_range'] = $priceRange;
        $data['teaching_age_range'] = $teachingAgeRange;
        $teacher_db = D('Teacher');
        $member_db = D('member');
        $memberInfo['mobile'] = $data['mobile'];
        $memberInfo['identity'] = 3;
        $memberInfo['password'] = $data['password'];
        unset($data['password']);
        unset($data['pwdconfirm']);
        try{
            $this->startTrans();
            $memberId = $member_db->add($memberInfo);
            $data['member_id'] = $memberId;
            if (empty($memberId)){
                $this->rollback();
                return false;
            }
            $teacher_db->add($data);
            $this->commit();
        } catch (ThinkException $e) {
            $this->rollback();
        }
        return true;
    }

}