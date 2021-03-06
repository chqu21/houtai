<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理通用模块
 * @author wangdong
 */
class CourseController extends CommonController {

    /**
     * 一对一课程列表
     */
    public function oneCourse($page = 1, $rows = 10,$search = array(),$sort = 'course_id', $order = 'desc'){
        if(IS_POST){
            $course_db = D('Course');
            //搜索
            $where = array();
            if (!empty($search['payment_status']) && $search['payment_status']==2){
                $search['payment_status'] = 0;
            }
            foreach ($search as $k=>$v){
                if(!$v) continue;
                if (in_array($k,$this->compareColumns)){
                    $where[] = "`{$k}` like '%{$v}%'";
                }else{
                    $where[] = "`{$k}` = '{$v}'";
                }

            }
            $where = implode(' and ', $where);
            $total = $course_db->where($where)->count();
            $limit=(($page - 1) * $rows>$total) ?  '0' : (($page - 1) * $rows);
            $limit = $limit. "," . $rows;
            $order = $sort.' '.$order;
            $column = "`course_id`,`teacher_id`,`teacher_name`,`course_name`,`course_category`,`price`,`course_time`,`class_method_id`,`class_method`,`class_address`,`person_num`,`begin_time`,`end_time`,`area`,`registration_number`,`time_quantum`,`discipline_id`,`sort_num`,`display`";
            $list = $total ? $course_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();
            foreach($list as $k => $lt){
                if ($lt['display']==1){
                    $list[$k]['display'] = '显示';
                }else{
                    $list[$k]['display'] = '不显示';
                }
                $list[$k]['order_status'] = $this->orderStatus[$lt['order_status']];
            }
            $data = array('total'=>$total, 'rows'=>$list);
            $this->ajaxReturn($data);
        }else{
            $menu_db = D('Menu');
            $currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
            $datagrid = array(
                'options'     => array(
                    'title'   => $currentpos,
                    'url'     => U('Course/oneCourse', array('grid'=>'datagrid')),
                    'toolbar' => 'course_onecourse_datagrid_toolbar',
                ),
                'fields' => array(
                    '课程名'    => array('field'=>'course_name','width'=>25,'sortable'=>true),
                    '老师ID'      => array('field'=>'teacher_id','width'=>10,'sortable'=>true),
                    '老师名'      => array('field'=>'teacher_name','width'=>15,'sortable'=>true),
                    '课程分类'    => array('field'=>'course_category','width'=>7,'sortable'=>true),
                    '价格'  => array('field'=>'price','width'=>15,'sortable'=>true),
                    '课时'    => array('field'=>'course_time','width'=>15,'sortable'=>true),
                    '开始时间'    => array('field'=>'begin_time','width'=>30,'sortable'=>true),
                    '时间段'    => array('field'=>'time_quantum','width'=>15,'sortable'=>true),
                    '课程类型' => array('field'=>'class_method','width'=>25,'sortable'=>true),
                    '上课区域' => array('field'=>'area','width'=>15,'sortable'=>true),
                    '总人数' => array('field'=>'person_num','width'=>15,'sortable'=>true),
                    '报名人数' => array('field'=>'registration_number','width'=>25,'sortable'=>true),
                    '排序数字' => array('field'=>'sort_num','width'=>25,'sortable'=>true),
                    '前台显示' => array('field'=>'display','width'=>15,'sortable'=>true),
                    '操作'    => array('field'=>'course_id','width'=>25,'sortable'=>true,'formatter'=>'adminMemberListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('course_list');
        }
    }


    /**
     * 编辑课程
     */
    public function courseEdit($id){
        $course_db = D('Course');

        if(IS_POST){
            $data = I('post.info');
            $data['class_method'] = $course_db->classMethod[$data['class_method_id']];
            $result = $course_db->where(array('course_id'=>$id))->save($data);
            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $course_db->getCourseInfo($id);
            $this->assign('info', $info);
            $this->display('course_edit');
        }
    }


    /**
     * 添加课程
     */
    public function courseAdd($id){
        $course_db = D('Course');
        if(IS_POST){
            //$memberInfo_db = D('memberInfo');
            $data = I('post.info');
            $rs = $course_db->addCourseByClassMethodId($id,$data['class_method_id']);
            if($rs){
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }else{
            $courseList = $course_db->getCourseList($id);
            foreach($courseList as $ifo){
                $class_method_ids[] = $ifo['class_method_id'];
            }
            $info['teacher_id'] = $id;
            $info['check1'] = in_array(1,$class_method_ids) ?  'checked' : '';
            $info['check2'] = in_array(2,$class_method_ids) ?  'checked' : '';
            $info['check3'] = in_array(3,$class_method_ids) ?  'checked' : '';
            $info['check4'] = in_array(4,$class_method_ids) ?  'checked' : '';
            $info['check5'] = in_array(5,$class_method_ids) ?  'checked' : '';
            $this->assign('info', $info);
            $this->display('course_add');
        }
    }
}