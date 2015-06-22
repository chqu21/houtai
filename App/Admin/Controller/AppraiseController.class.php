<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理通用模块
 * @author wangdong
 */
class AppraiseController extends CommonController {

    public static $classMethod = array(
        '1'=>'一对一(学生上门)',
        '2'=>'一对一(教师外出)',
        '3'=>'小组课（2～5人,学生上门）',
        '4'=>'小班课（6～10人,学生上门）',
        '5'=>'大班课（10人以上,学生上门)',
    );

    /**
     * 评论列表
     */
    public function appraiseList($page = 1, $rows = 10,$search = array(),$sort = 'comments_id', $order = 'desc'){
        if(IS_POST){
            $comments_db = D('ServiceComments');
            //搜索
            $where = array();

            foreach ($search as $k=>$v){
                if(!$v) continue;
                if (in_array($k,$this->compareColumns)){
                    $where[] = "`{$k}` like '%{$v}%'";
                }else{
                    $where[] = "`{$k}` = '{$v}'";
                }

            }
            $where = implode(' and ', $where);
            $total = $comments_db->where($where)->count();
            $limit=(($page - 1) * $rows>$total) ?  '0' : (($page - 1) * $rows);
            $limit = $limit. "," . $rows;
            $order = $sort.' '.$order;
            $column = "`comments_id`,`comments_id` as comments_ids,`type_id`,`type`,`comments`,`score`,`member_id`,`member_name`,`class_time`,`teacher_id`,`teacher_name`,`comment_type`,`order_code`";
            $list = $total ? $comments_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();
            $teacher_db = D('Teacher');
            foreach($list as $k => $lt){
                $typeArr = static::$classMethod;
                $list[$k]['comment_type'] = $typeArr[$lt['comment_type']];
                $teacherInfo = $teacher_db->where(array('teacher_id'=>$lt['teacher_id']))->field('teacher_id','teacher_name')->find();
                $list[$k]['teacher_name'] = $teacherInfo['teacher_name'];
            }
            $data = array('total'=>$total, 'rows'=>$list);
            $this->ajaxReturn($data);
        }else{
            $menu_db = D('Menu');
            $currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
            $datagrid = array(
                'options'     => array(
                    'title'   => $currentpos,
                    'url'     => U('Appraise/appraiseList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_appraiselist_datagrid_toolbar',
                ),
                'fields' => array(
                    '评价ID'    => array('field'=>'comments_id','width'=>10,'sortable'=>true),
                    '类型ID'    => array('field'=>'type_id','width'=>10,'sortable'=>true),
                    '类型'      => array('field'=>'type','width'=>15,'sortable'=>true),
                    '内容'      => array('field'=>'comments','width'=>15,'sortable'=>true),
                    '分数'    => array('field'=>'score','width'=>15,'sortable'=>true),
                    '会员ID'  => array('field'=>'member_id','width'=>15,'sortable'=>true),
                    '会员名'    => array('field'=>'member_name','width'=>15,'sortable'=>true),
                    '课时'    => array('field'=>'class_time','width'=>15,'sortable'=>true),
                    '教师ID'    => array('field'=>'teacher_id','width'=>15,'sortable'=>true),
                    '教师姓名'    => array('field'=>'teacher_name','width'=>10,'sortable'=>true),
                    '订单类型' => array('field'=>'comment_type','width'=>20,'sortable'=>true),
                    '订单号' => array('field'=>'order_code','width'=>15,'sortable'=>true),
                    '操作'    => array('field'=>'comments_ids','width'=>20,'sortable'=>true,'formatter'=>'adminAppraiseListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('appraise_list');
        }
    }

    /**
     * 编辑评论
     */
    public function appraiseEdit($id){
        $comments_db = D('ServiceComments');

        if(IS_POST){
            $data = I('post.info');
            $result = $comments_db->where(array('comments_id'=>$id))->save($data);
            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $comments_db->getCommentsInfo($id);
            $this->assign('info', $info);
            $this->display('appraise_edit');
        }
    }

    /**
     * 删除评论
     */

    public function appraiseDelete($id){
        $comments_db = D('ServiceComments');
        $id = I('post.id');
        $result = $comments_db->where(array('comments_id'=>$id))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }

}