<?php
namespace Admin\Model;
use Think\Model;

class AppraiseModel extends Model{
    protected $tableName = 'appraise';
	protected $pk        = 'appraise_id';
	public    $error;

    public function addAppraise($subjectId,$data){
        $subject_db = D('Subject');
        $appraise_db = D('Appraise');
        try{
            $this->startTrans();
            $appraise_db ->add($data);
            $appraise_num = $appraise_db ->where("subject_id=$id")->count();
            $subject_db->where(array('subject_id'=>$id))->save(array('comment_num'=>$appraise_num+1));
            $this->commit();
            return true;
        } catch (ThinkException $e) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 获取主题信息
     */
    public function getAppraiseInfo($appraiseId){
        $info = $this->where(array('appraise_id'=>$appraiseId))->find();
        return $info;
    }

}