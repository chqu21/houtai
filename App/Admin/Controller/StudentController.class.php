<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理员相关模块
 * @author wangdong
 */
class StudentController extends CommonController {


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
    public function studentList($page = 1, $rows = 10,$search = array(),$sort = 'member_id', $order = 'desc'){
        if(IS_POST){
            $member_info_db = D('MemberInfo');
            $member_db = D('Member');
            //搜索
            $where = array();
            foreach ($search as $k=>$v){
                if(!$v) continue;
                $where[] = "`{$k}` like '%{$v}%'";
            }
            $where = implode(' and ', $where);

            $total = $member_info_db->where($where)->count();
            $limit=(($page - 1) * $rows>$total) ?  '0' : (($page - 1) * $rows);
            $limit = $limit. "," . $rows;
            $order = $sort.' '.$order;
            $column = "`real_name`,`address`,`nick_name`,`sex`,`birthday`,`school`,`grade`,`weixin`,`qq`,`safe_email`,`head_photo`,`mobile`,`member_id`,`member_id` as member_ids,`raw_add_time`,`invite_code`";
            $list = $total ? $member_info_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();
            foreach($list as $k => $lt){
                $mId = $lt['member_id'];
                $mInfo = $member_db->field("`huanxin_user`")->where("member_id=$mId")->select();
                $list[$k]['huanxin_user'] = $mInfo[0]['huanxin_user'];
            }
            $data = array('total'=>$total, 'rows'=>$list);
            $this->ajaxReturn($data);
        }else{
            $menu_db = D('Menu');
            $currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
            $datagrid = array(
                'options'     => array(
                    'title'   => $currentpos,
                    'url'     => U('Student/studentList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_studentlist_datagrid_toolbar',
                ),
                'fields' => array(
                    '学生ID'    => array('field'=>'member_id','width'=>15,'sortable'=>true),
                    '真实姓名'      => array('field'=>'real_name','width'=>15,'sortable'=>true),
                    '姓别'      => array('field'=>'sex','width'=>7,'sortable'=>true),
                    '昵称'    => array('field'=>'nick_name','width'=>7,'sortable'=>true),
                    '学校'  => array('field'=>'school','width'=>15,'sortable'=>true),
                    '微信'    => array('field'=>'weixin','width'=>15,'sortable'=>true),
                    '年级'    => array('field'=>'grade','width'=>15,'sortable'=>true),
                    '安全邮箱' => array('field'=>'safe_email','width'=>15,'sortable'=>true),
                    '手机号' => array('field'=>'mobile','width'=>20,'sortable'=>true),
                    'qq' => array('field'=>'qq','width'=>15,'sortable'=>true),
                    '微博' => array('field'=>'weibo','width'=>25,'sortable'=>true),
                    '注册时间' => array('field'=>'raw_add_time','width'=>25,'sortable'=>true),
                    '环信帐号' => array('field'=>'huanxin_user','width'=>15,'sortable'=>true),
                    '邀请码' =>  array('field'=>'invite_code','width'=>15,'sortable'=>true),
                    '操作'    => array('field'=>'member_ids','width'=>55,'sortable'=>true,'formatter'=>'adminMemberListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('student_list');
        }
    }

    /**
     * 添加老师
     */
    public function studentAdd(){
        if(IS_POST){
            $student_db = D('MemberInfo');
            //$memberInfo_db = D('memberInfo');
            $data = I('post.info');
            $len = strlen($data['mobile']);
            $pw = substr($data['mobile'],($len-6),6);//默认密码，手机号后六位
            $data['password'] = $this->handle_pwd($pw,'kdsjkdeyuewy');
            $rs = $student_db ->addTeacher($data);
            if($rs){
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }else{
            $this->display('student_add');
        }
    }


    /**
     * 编辑老师
     */
    public function studentEdit($id){
        $member_info_db = D('MemberInfo');
        if(IS_POST){
            $data = I('post.info');
            $data['password'] = !empty($data['password']) ? $this->handle_pwd($data['password'],'kdsjkdeyuewy') : '';
            $rs = $member_info_db->editMemberInfo($id,$data);
            if($rs){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $member_info_db->getMemberInfo($id);
            $this->assign('info', $info);
            $this->display('student_edit');
        }
    }

    /**
     * 删除老师
     */
    public function studentDelete(){
        $admin_db = D('MemberInfo');
        $id = I('post.id');
        $result = $admin_db->where(array('member_id'=>$id))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }





    public function editPhoto($id){
        $student_db = D('MemberInfo');
        $teacherInfo = $student_db->where(array('member_id'=>$id))->field('head_photo')->find();
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
    public function upFile($sId) {
        $mId = 's_'.$sId;
        $path = C('IMG_PATH');
        $src=base64_decode($_POST['pic']);
        $pic1=base64_decode($_POST['pic1']);
        $pic2=base64_decode($_POST['pic2']);
        $pic3=base64_decode($_POST['pic3']);

        if($src) {
            $ext = $this->getPicExt($src);
            $filenameSrc =$mId."_src.";
            $filename170 = $mId."_big.";
            $filename130 =  $mId."_middle.";
            $filename20 =  $mId."_small.";

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
                        $pathStr = $path.$mId.$type;
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
                        $pathStr = $path.$mId.$type;
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
                        $pathStr = $path.$mId.$type;
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
            $student_db = D('MemberInfo');
            if ($student_db->where(array('member_id'=>$sId))->save(array('head_photo'=>json_encode($imgArr)))){
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


    /**
     * 验证邮箱是否存在
     */
    public function checkMobile($mobile = ''){
        $student_db = D('MemberInfo');
        $exists = $student_db->where(array('mobile'=>$mobile))->field('mobile')->find();
        if ($exists) {
            $this->success('手机号存在');
        }else{
            $this->error('手机号不存在');
        }
    }

}