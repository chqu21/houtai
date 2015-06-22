<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理员相关模块
 * @author wangdong
 */
class TeacherController extends CommonController {


    public $timeArr = array(
                '07:00-07:30',
                '07:30-08:00',
                '08:00-08:30',
                '08:30-09:00',
                '09:00-09:30',
                '09:30-10:00',
                '10:00-10:30',
                '10:30-11:00',
                '11:00-11:30',
                '11:30-12:00',
                '12:00-12:30',
                '12:30-13:00',
                '13:00-13:30',
                '13:30-14:00',
                '14:00-14:30',
                '14:30-15:00',
                '15:00-15:30',
                '15:30-16:00',
                '16:00-16:30',
                '16:30-17:00',
                '17:00-17:30',
                '17:30-18:00',
                '18:00-18:30',
                '18:30-19:00',
                '19:00-19:30',
                '19:30-20:00',
                '20:00-20:30',
                '20:30-21:00',
                '21:00-21:30',
                '21:30-22:00',
        );


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
            $total = $teacher_db->where($where)->count();
            $limit=(($page - 1) * $rows>$total) ?  '0' : (($page - 1) * $rows);
            $limit = $limit. "," . $rows;
            $order = $sort.' '.$order;
            $column = "`real_name`,`teacher_name`,`sex`,`teaching_age`,`certification_flag`,`education_flag`,`teacher_certification_flag`,`discipline`,`grade`,`course_category`,`sort_num`,`recommand_flag`,`raw_add_time`,`display`,`teacher_id`,`teacher_id` as teacher_ids,`teacher_id` as teacher_idss,`mobile`";
            $list = $total ? $teacher_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();

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
                    '选择'    => array('field'=>'teacher_idss','width'=>15,'checkbox'=>"true"),
                    '教师ID'    => array('field'=>'teacher_id','width'=>10,'sortable'=>true),
                    '真实姓名'      => array('field'=>'real_name','width'=>15,'sortable'=>true),
                    '显示姓名'      => array('field'=>'teacher_name','width'=>15,'sortable'=>true),
                    '姓别'      => array('field'=>'sex','width'=>7,'sortable'=>true),
                    '教龄'    => array('field'=>'teaching_age','width'=>7,'sortable'=>true),
                    '身份认证'  => array('field'=>'certification_flag','width'=>15,'sortable'=>true),
                    '学历认证' => array('field'=>'education_flag','width'=>15,'sortable'=>true),
                    '教师资格认证' => array('field'=>'teacher_certification_flag','width'=>20,'sortable'=>true),
                    '科目'    => array('field'=>'discipline','width'=>15,'sortable'=>true),
                    '年级'    => array('field'=>'grade','width'=>15,'sortable'=>true),
                    '课程分类' => array('field'=>'course_category','width'=>15,'sortable'=>true),
                    '排序' => array('field'=>'sort_num','width'=>15,'sortable'=>true),
                    '推荐' => array('field'=>'recommand_flag','width'=>15,'sortable'=>true),
                    '注册时间' => array('field'=>'raw_add_time','width'=>25),
                    '显示' => array('field'=>'display','width'=>7,'sortable'=>true),
                    '手机号' => array('field'=>'mobile','width'=>15,'sortable'=>true),
                    '操作'    => array('field'=>'teacher_ids','width'=>60,'sortable'=>true,'formatter'=>'adminMemberListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('teacher_list');
        }
    }

    /**
     * 添加老师
     */
    public function teacherAdd(){
        if(IS_POST){
            $teacher_db = D('Teacher');
            //$memberInfo_db = D('memberInfo');
            $data = I('post.info');
            $len = strlen($data['mobile']);
            $pw = substr($data['mobile'],($len-6),6);//默认密码，手机号后六位
            $data['password'] = $this->handle_pwd($pw,'kdsjkdeyuewy');
            $rs = $teacher_db ->addTeacher($data);
            if($rs){
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }else{
            $this->display('teacher_add');
        }
    }


    /**
     * 编辑老师
     */
    public function teacherEdit($id){
        $teacher_db = D('Teacher');

        if(IS_POST){
            $data = I('post.info');
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





    public function editPhoto($id){
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
public function getPicExt($fileStream){
    $strInfo = @unpack("C2chars", $fileStream);
    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
    $fileType = '';
    switch ($typeCode) {
        case 7790: $fileType = 'exe'; break;
        case 7784: $fileType = 'midi'; break;
        case 8297: $fileType = 'rar'; break;
        case 255216: $fileType = 'jpg'; break;
        case 7173: $fileType = 'gif'; break;
        case 6677: $fileType = 'bmp'; break;
        case 13780: $fileType = 'png'; break;
        default: echo'unknown';
    }
    return $fileType;
}






    // 处理表单数据
    public function upFile($teacherId) {
        $path = C('IMG_PATH');
        $src=base64_decode($_POST['pic']);
        $pic1=base64_decode($_POST['pic1']);
        $pic2=base64_decode($_POST['pic2']);
        $pic3=base64_decode($_POST['pic3']);

        if($src) {
            $ext = $this->getPicExt($src);
            $filenameSrc = $teacherId."_src.";
            $filename170 = $teacherId."_big.";
            $filename130 =  $teacherId."_middle.";
            $filename20 =  $teacherId."_small.";

            if (file_exist($path.$filenameSrc.'png')){
                unlink($path.$filenameSrc.'png');
            }
            if (file_exist($path.$filename170.'png')){
                unlink($path.$filename170.'png');
            }
            if (file_exist($path.$filename130.'png')){
                unlink($path.$filename130.'png');
            }
            if (file_exist($path.$filename20.'png')){
                unlink($path.$filename20.'png');
            }
            file_put_contents($path.$filenameSrc.$ext,$src);
            file_put_contents($path.$filename170.$ext,$pic1);
            file_put_contents($path.$filename130.$ext,$pic2);
            file_put_contents($path.$filename20.$ext,$pic3);
            $imgType = array('_src','_big','_middle','_small');
            switch($ext){
                case 'jpg':
                    foreach($imgType as $type){
                        $pathStr = $path.$teacherId.$type;
                        $src = $pathStr.'.jpg';
                        $image = ImageCreateFromJPEG($src);
                        $output = $pathStr.'.png';
                        imagepng($image,$output);
                        imagedestroy($image);
                        unlink($src);
                    }
                    break;
                case 'gif':
                    foreach($imgType as $type){
                        $pathStr = $path.$teacherId.$type;
                        $src = $pathStr.'.gif';
                        $image = ImageCreateFromJPEG($src);
                        $output = $pathStr.'.png';
                        imagepng($image,$output);
                        imagedestroy($image);
                        unlink($src);
                    }
                    break;
                case 'bmp':
                    foreach($imgType as $type){
                        $pathStr = $path.$teacherId.$type;
                        $src = $pathStr.'.bmp';
                        $image = ImageCreateFromJPEG($src);
                        $output = $pathStr.'.png';
                        imagepng($image,$output);
                        imagedestroy($image);
                        unlink($src);
                    }
                    break;

            }
            $imgArr['srcPic'] = 'upload/'.$filenameSrc.'png';
            $imgArr['bigPic'] = 'upload/'.$filename170.'png';
            $imgArr['middlePic'] = 'upload/'.$filename130.'png';
            $imgArr['smallPic'] = 'upload/'.$filename20.'png';
            $teacher_db = D('Teacher');
            if ($teacher_db->where(array('teacher_id'=>$teacherId))->save(array('head_photo'=>json_encode($imgArr)))){
                $rs['status'] = 1;
                echo json_encode($rs);
                exit;
            }
            $rs['status'] = 0;
        }
        $rs['status'] = 1;
        echo json_encode($rs);
        exit;
    }


    //评价列表
    public function appraiseList($id){

    }
    //生成唯一订单号
    public  function getOrderCode(){
        @date_default_timezone_set("PRC");
        $order_id_main = date('YmdHis') . rand(10000000, 99999999);
        //订单号码主体长度
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        return $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
    }
    //添加评价
    public  function addAppraise($id){
        if(IS_POST) {
            $data = I('post.info');
            $teacherName = $data['teacher_name'];
            $memberName = $data['member_name'];
            $addTime = $data['raw_add_time'];
            unset($data['raw_add_time']);
            unset($data['member_name']);
            $commentType = array(
                'type1' => array(
                    'type_id' => 1,
                    'type' => '环境与设施',
                ),
                'type2' => array(
                    'type_id' => 2,
                    'type' => '教学过程',
                ),
                'type3' => array(
                    'type_id' => 3,
                    'type' => '教学效果',
                )
            );
            $serviceComments = D('ServiceComments');
            $teacher_db = D('Teacher');
            $teacherInfo = $teacher_db->where(array('teacher_id'=>$id))->field('teacher_id','teacher_name')->find();
            foreach ($data as $key => $v) {
                $dataArr['teacher_id'] = $id;
                $dataArr['teacher_name'] = $teacherInfo['teacher_name'];
                $dataArr['member_name'] = $memberName;
                $dataArr['type_id'] = $commentType[$key]['type_id'];
                $dataArr['type'] = $commentType[$key]['type'];
                $dataArr['comments'] = $v['content'];
                $dataArr['score'] = $v['score'];
                $dataArr['appraise_code'] = $this->getOrderCode();
                $dataArr['raw_add_time'] = $addTime.' '.date('H:i:s');
                $result = $serviceComments->add($dataArr);
            }
            if ($result) {
                $this->success('添加评论成功');
            } else {
                $this->error('添加评论失败');
            }
            exit;
        }else{
            $this->assign('teacherId',$id);
            $this->display('teacher_appraise');
        }
    }

    /**
     * 时间列表
     */
    public function timeList($page = 1, $rows = 10,$search = array(),$sort = 'course_time_id', $order = 'asc'){
        if(IS_POST){
            $teacher_db = D('CourseTime');
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
                if ($lt['teacher_no_time']==1){
                    $list[$k]['teacher_no_time'] = '未开放';
                }else{
                    $list[$k]['teacher_no_time'] = '已开放';
                }

                if ($lt['student_selected']==1){
                    $list[$k]['student_selected'] = '学生已定';
                }else{
                    $list[$k]['student_selected'] = '学生未定';
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
                    'url'     => U('Teacher/timeList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_timelist_datagrid_toolbar',
                ),
                'fields' => array(
                    '教师ID' => array('field'=>'teacher_id','width'=>25),
                    '教师名'      => array('field'=>'teacher_name','width'=>15,'sortable'=>true),
                    '时间'      => array('field'=>'class_date','width'=>7,'sortable'=>true),
                    '时段'    => array('field'=>'class_time','width'=>7,'sortable'=>true),
                    '是否开放'  => array('field'=>'teacher_no_time','width'=>15,'sortable'=>true),
                    '是否占用' => array('field'=>'student_selected','width'=>15,'sortable'=>true),
                    '购课订单号' => array('field'=>'course_category','width'=>15,'sortable'=>true),
                    '学生ID' => array('field'=>'sort_num','width'=>15,'sortable'=>true),
                    '学生名' => array('field'=>'student_name','width'=>15,'sortable'=>true),
                    '课程时间ID'    => array('field'=>'course_time_id','width'=>15,'sortable'=>false,'formatter'=>'adminMemberListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('time_list');
        }
    }
    //编辑时间
    public function timeEdit($id){
        $courseTime = D('CourseTime');
        if(IS_POST){
            $data = I('post.');
            $result = $courseTime->where(array('course_time_id'=>$id))->save($data);
            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else {
            $teacherInfo = $courseTime->where(array('course_time_id' => $id))->find();
            $this->assign('info', $teacherInfo);
            $this->display('time_edit');
        }
    }

    //添加时间
    public function timeAdd(){

        if(IS_POST) {
            $weekarray=array("日","一","二","三","四","五","六");
            $data = I('post.info');
            $k = date('w',strtotime($data['class_date']));
            $week = '周'.$weekarray[$k];
            $result = array();
            $date = $data['class_date'];
            if ($data['teacherAll']==1 && $data['classTimeAll']==1) {
                $teacherDb = D('Teacher');
                $courseTime = D('CourseTime');
                $teacherList = $teacherDb->select();
                foreach ($teacherList as $lt) {
                    foreach ($this->timeArr as $time){
                        $dataArr['teacher_id'] = $lt['teacher_id'];
                        $dataArr['teacher_name'] = $lt['teacher_name'];
                        $dataArr['class_date'] = $date;
                        $dataArr['class_time'] = $time;
                        $dataArr['class_week'] = $week;
                        $dataArr['teacher_no_time'] = 0;
                        $result = $courseTime->add($dataArr);
                    }
                 }
            }

            if ($data['teacherAll']==1 && $data['classTimeAll']==0) {
                $teacherDb = D('Teacher');
                $courseTime = D('CourseTime');
                $teacherList = $teacherDb->select();
                foreach ($teacherList as $lt) {
                        $dataArr['teacher_id'] = $lt['teacher_id'];
                        $dataArr['teacher_name'] = $lt['teacher_name'];
                        $dataArr['class_date'] = $date;
                        $dataArr['class_time'] = json_encode($data['class_time']);
                        $dataArr['class_week'] = $week;
                        $dataArr['teacher_no_time'] = 0;
                        $result = $courseTime->add($dataArr);
                }
            }

            if ($data['teacherAll']==0 && $data['classTimeAll']==1) {
                $teacherDb = D('Teacher');
                $courseTime = D('CourseTime');
                $teacherInfo = $teacherDb->where(array(teacher_id=>$data['teacher_id']))->select();
                $teacherName = $teacherInfo[0]['teacher_name'];
                    $dataArr['teacher_id'] = $data['teacher_id'];
                    $dataArr['teacher_name'] = $teacherName;
                    $dataArr['class_date'] = $date;
                    $dataArr['class_time'] = json_encode($this->timeArr);
                    $dataArr['class_week'] = $week;
                    $dataArr['teacher_no_time'] = 0;
                    $result = $courseTime->add($dataArr);
            }
            if ($data['teacherAll']==0 && $data['classTimeAll']==0) {
                $teacherDb = D('Teacher');
                $courseTime = D('CourseTime');
                $teacherInfo = $teacherDb->where(array(teacher_id=>$data['teacher_id']))->select();
                $teacherName = $teacherInfo[0]['teacher_name'];
                $dataArr['teacher_id'] = $data['teacher_id'];
                $dataArr['teacher_name'] = $teacherName;
                $dataArr['class_date'] = $data['class_date'];
                $dataArr['class_time'] = json_encode($data['class_time']);
                $dataArr['class_week'] = $week;
                $dataArr['teacher_no_time'] = 0;
                $result = $courseTime->add($dataArr);
            }
            if($result){
                $this->success('修改成功');
                exit;
            }else {
                $this->error('修改失败');
            }
        }else {
            $this->display('time_add');
        }
    }

    /**
     * 验证邮箱是否存在
     */
    public function checkMobile($mobile = ''){
        $teacher_db = D('Teacher');
        $exists = $teacher_db->where(array('mobile'=>$mobile))->field('mobile')->find();
        if ($exists) {
            $this->success('手机号存在');
        }else{
            $this->error('手机号不存在');
        }
    }

}