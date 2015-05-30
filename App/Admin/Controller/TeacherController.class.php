<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理员相关模块
 * @author wangdong
 */
class TeacherController extends CommonController {

    /**
     * 老师列表
     */
    public function teacherList($page = 1, $rows = 10,$search = array(),$sort = 'teacher_id', $order = 'asc'){
        if(IS_POST){
            $teacher_db = D('Teacher');
            //搜索
            $where = array();
            foreach ($search as $k=>$v){
                if(!$v) continue;
                $where[] = "`{$k}` like '%{$v}%'";
            }
            $where = implode(' and ', $where);
            $limit=($page - 1) * $rows . "," . $rows;
            $total = $teacher_db->where($where)->count();
            $order = $sort.' '.$order;
            $list = $total ? $teacher_db->where($where)->order($order)->limit($limit)->select() : array();



            foreach($list as $k => $lt){
                if ($lt['certification_flag']==1){
                    $list[$k]['certification_flag'] = '已认证';
                }else{
                    $list[$k]['certification_flag'] = '未认证';
                }

                if ($lt['education_flag']==1){
                    $list[$k]['education_flag'] = '已认证';
                }else{
                    $list[$k]['education_flag'] = '未认证';
                }

                if ($lt['teacher_certification_flag']==1){
                    $list[$k]['teacher_certification_flag'] = '已认证';
                }else{
                    $list[$k]['teacher_certification_flag'] = '未认证';
                }

                if ($lt['recommand_flag']==1){
                    $list[$k]['recommand_flag'] = '已推荐';
                }else{
                    $list[$k]['recommand_flag'] = '未推荐';
                }

                if ($lt['display']==1){
                    $list[$k]['display'] = '显示';
                }else{
                    $list[$k]['display'] = '不显示';
                }
            }

            $data = array('total'=>$total, 'rows'=>$list);
            $this->ajaxReturn($data);
        }else{
            $menu_db = D('Menu');
            $currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
            $datagrid = array(
                'options'     => array(
                    'title'   => $currentpos,
                    'url'     => U('Teacher/teacherList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_teacherlist_datagrid_toolbar',
                ),
                'fields' => array(
                    '教师姓名'      => array('field'=>'teacher_name','width'=>15,'sortable'=>true),
                    '姓别'      => array('field'=>'sex','width'=>7,'sortable'=>true),
                    '教龄'    => array('field'=>'teaching_age','width'=>7,'sortable'=>true),
                    '身份认证'  => array('field'=>'certification_flag','width'=>15,'sortable'=>true),
                    '学历认证' => array('field'=>'education_flag','width'=>15,'sortable'=>true),
                    '教师资格认证' => array('field'=>'teacher_certification_flag','width'=>25,'sortable'=>true),
                    '科目'    => array('field'=>'discipline','width'=>15,'sortable'=>true),
                    '年级'    => array('field'=>'grade','width'=>15,'sortable'=>true),
                    '课程分类' => array('field'=>'course_category','width'=>15,'sortable'=>true),
                    '排序' => array('field'=>'sort_num','width'=>15,'sortable'=>true),
                    '推荐' => array('field'=>'recommand_flag','width'=>15,'sortable'=>true),
                    '注册时间' => array('field'=>'raw_add_time','width'=>25),
                    '显示' => array('field'=>'display','width'=>7,'sortable'=>true),
                    '教师ID'    => array('field'=>'teacher_id','width'=>55,'sortable'=>false,'formatter'=>'adminMemberListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $admin_role_db = D('AdminRole');
            $rolelist = $admin_role_db->where(array('disabled'=>'0'))->getField('roleid,rolename', true);
            $this->assign('rolelist', $rolelist);
            $this->display('teacher_list');
        }
    }

    /**
     * 添加老师
     */
    public function teacherAdd(){
        if(IS_POST){
            $teacher_db = D('Teacher');
            $member_db = D('member');
            //$memberInfo_db = D('memberInfo');
            $data = I('post.info');
            $memberInfo['mobile'] = $data['mobile'];
            $memberInfo['password'] = $data['password'];
            $memberId = $member_db->add($memberInfo);
            unset($data['mobile']);
            unset($data['password']);
            $id = $teacher_db->add($data);
            if($memberId && $id){
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }else{
            $admin_role_db = D('AdminRole');
            $rolelist = $admin_role_db->where(array('disabled'=>'0'))->getField('roleid,rolename', true);
            $this->assign('rolelist', $rolelist);
            $this->display('teacher_add');
        }
    }

    /**
     * 编辑老师
     */
    public function teacherEdit($id){
        $teacher_db = D('Teacher');

        if(IS_POST){
            $data = I('post.');
            $result = $teacher_db->where(array('teacher_id'=>$id))->save($data);
            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $teacher_db->getTeacherInfo($id);
            $this->assign('info', $info);
            $this->display('teacher_edit');
        }
    }

    /**
     * 删除老师
     */
    public function teacherDelete(){
        $admin_db = D('Teacher');
        $id = I('post.id');
        $result = $admin_db->where(array('teacher_id'=>$id))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }





    public function teacherEditHeadPhoto($id){
        $teacher_db = D('Teacher');
        $teacherInfo = $teacher_db->where(array('teacher_id'=>$id))->field('head_photo')->find();
        if (!empty($teacherInfo['head_photo'])){
            $rs = json_decode($teacherInfo['head_photo'],'r');
            foreach($rs as $key => $img){
                $rs[$key] = $img.'?'.rand(10,20);
            }
        }else{
            $rs = array(
                'bigPic' =>'../flash/default.jpg',
                'middlePic' =>'2.png',
                'smallPic' =>'3.png',
            );
        }
        $this->assign('headPhoto',$rs);
        $this->assign('teacherId', $id);
        $this->display('headPhoto');
    }

    // 处理表单数据
    public function upfile($teacherId) {
        $path = C('IMG_PATH');
        $file_src = "src.png";
        $filename162 = $teacherId."_big.png";
        $filename48 =  $teacherId."_middle.png";
        $filename20 =  $teacherId."_small.png";

        $src=base64_decode($_POST['pic']);
        $pic1=base64_decode($_POST['pic1']);
        $pic2=base64_decode($_POST['pic2']);
        $pic3=base64_decode($_POST['pic3']);

        if($src) {
            file_put_contents($file_src,$src);
        }
        if (file_exist($path.$filename162,$pic1)){
            unlink($path.$filename162);
        }
        if (file_exist($path.$filename48,$pic1)){
            unlink($path.$filename48);
        }
        if (file_exist($path.$filename20,$pic1)){
            unlink($path.$filename20);
        }
        file_put_contents($path.$filename162,$pic1);
        file_put_contents($path.$filename48,$pic2);
        file_put_contents($path.$filename20,$pic3);
        $imgArr['bigPic'] = 'Uploads/'.$filename162;
        $imgArr['middlePic'] = 'Uploads/'.$filename48;
        $imgArr['smallPic'] = 'Uploads/'.$filename20;
        $teacher_db = D('Teacher');
        if ($teacher_db->where(array('teacher_id'=>$teacherId))->save(array('head_photo'=>json_encode($imgArr)))){
            $rs['status'] = 1;
            echo json_encode($rs);
            exit;
        }
        $rs['status'] = 0;
        echo json_encode($rs);
        exit;
    }


    //添加评价
    public function Appraise($id){
        $teacher_db = D('Teacher');
        $teacherInfo = $teacher_db->where(array('teacher_id'=>$id))->field('teacher_id','teacher_name')->find();
        $this->assign('teacherName', $teacherInfo['teacher_name']);
        $this->assign('teacherId',$id);
        $this->display('teacher_appraise');
    }

    //添加评价
    public  function addAppraise(){
        $data = I('post.info');
        $teacherName = $data['teacher_name'];
        $teacherId = $data['teacher_id'];
        $memberName = $data['member_name'];
        unset($data['teacher_name']);
        unset($data['teacher_id']);
        unset($data['member_name']);
        $commentType= array(
            'type1'=>array(
                'type_id'=>1,
                'type'=>'环境与设施',
            ),
            'type2'=>array(
                'type_id'=>2,
                'type'=>'教学过程',
            ),
            'type3'=>array(
                'type_id'=>3,
                'type'=>'教学效果',
            ),
            'type4'=>array(
                'type_id'=>4,
                'type'=>'服务质量',
            ),
            'type5'=>array(
                'type_id'=>5,
                'type'=>'作业与答疑',
            ),
        );
        $serviceComments = D('ServiceComments');
        foreach($data as $key => $v){
            $dataArr['teacher_id'] = $teacherId;
            $dataArr['teacher_name'] = $teacherName;
            $dataArr['member_name'] = $memberName;
            $dataArr['type_id'] = $commentType[$key]['type_id'];
            $dataArr['type'] = $commentType[$key]['type'];
            $dataArr['comments'] = $v['content'];
            $dataArr['score']=$v['score'];
            $result= $serviceComments->add($dataArr);
        }
        if ($result){
            $this->success('添加评论成功');
        }else {
            $this->error('添加评论失败');
        }
        exit;

    }

}