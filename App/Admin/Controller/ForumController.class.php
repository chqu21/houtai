<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理通用模块
 * @author wangdong
 */
class ForumController extends CommonController {

    public $orderStatus = array(
        '1' => '取消',
        '2' => '待确认课酬',
        '3' => '已确认课酬',
        '4' => '待评价',
        '5' => '已评价',
        '6' => '未付款'
    );
    public $compareColumns = array(
        'teacher_name','raw_add_time'
    );

    /**
     * 主题列表
     */
    public function subjectList($page = 1, $rows = 10,$search = array(),$sort = 'subject_id', $order = 'desc'){
        if(IS_POST){
            $subject_db = D('Subject');

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
            if (!empty($mIds)){
                $where = $where.' and member_id in ('.implode(',',$mIds).')';
            }
            $total = $subject_db->where($where)->count();
            $limit=(($page - 1) * $rows>$total) ?  '0' : (($page - 1) * $rows);
            $limit = $limit. "," . $rows;
            $order = $sort.' '.$order;
            $column = "`subject_id`,`subject_id` as subject_ids,`subject`,`member_id`,`author`,`content`,`raw_add_time`,`sort_num`,`display`";
            $list = $total ? $subject_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();
            foreach($list as $k => $lt){
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
                    'url'     => U('Forum/subjectList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_subjectlist_datagrid_toolbar',
                ),
                'fields' => array(
                    '主题ID'    => array('field'=>'subject_id','width'=>15,'sortable'=>true),
                    '主题标题'      => array('field'=>'subject','width'=>15,'sortable'=>true),
                    '发布人ID'      => array('field'=>'member_id','width'=>15,'sortable'=>true),
                    '作者'    => array('field'=>'author','width'=>15,'sortable'=>true),
                    '内容'  => array('field'=>'content','width'=>15,'sortable'=>true),
                    '添加时间'    => array('field'=>'raw_add_time','width'=>15,'sortable'=>true),
                    '排序号'    => array('field'=>'sort_num','width'=>15,'sortable'=>true),
                    '是否显示'    => array('field'=>'display','width'=>15,'sortable'=>true),
                    '操作'    => array('field'=>'subject_ids','width'=>55,'sortable'=>true,'formatter'=>'adminSubjectListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('subject_list');
        }
    }

    /**
     * 编辑课程
     */
    public function subjectEdit($id){
        $subject_db = D('Subject');
        if(IS_POST){
            $data = I('post.info');
            $result = $subject_db->where(array('subject_id'=>$id))->save($data);
            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $subject_db->getSubjectInfo($id);
            $this->assign('info', $info);
            $this->display('subject_edit');
        }
    }

    /**
     * 删除主题
     */

    public function subjectDelete($id){
        $comments_db = D('Subject');
        $id = I('post.id');
        $result = $comments_db->where(array('subject_id'=>$id))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }

    /**
     * 添加评论
     */
    public function appraiseAdd($id){
        if(IS_POST){
            $appraise_db = D('Appraise');
            $data = I('post.info');
            $data['subject_id'] = $id;
            $rs = $appraise_db->addAppraise($id,$data);
            if($rs){
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }else{
            $this->assign('subjectId', $id);
            $this->display('appraise_add');
        }
    }

    /**
     * 编辑评论
     */
    public function appraiseEdit($id){
        $appraise_db = D('Appraise');
        if(IS_POST){
            $data = I('post.info');
            $result = $appraise_db->where(array('appraise_id'=>$id))->save($data);
            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $appraise_db->getAppraiseInfo($id);
            $info['content'] = !empty($info['content']) ? trim($info['content']) : '';
            $this->assign('info', $info);
            $this->display('appraise_edit');
        }
    }

    /**
     * 删除评论
     */

    public function appraiseDelete($id){
        $appraise_db = D('Appraise');
        $id = I('post.id');
        $result = $appraise_db->where(array('appraise_id'=>$id))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }

    /**
     * 帖子列表
     */
        public function appraiseList($page = 1, $rows = 10,$search = array(),$sort = 'appraise_id', $order = 'desc'){
            if(IS_POST){
                $subject_db = D('Appraise');

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
                if (!empty($mIds)){
                    $where = $where.' and member_id in ('.implode(',',$mIds).')';
                }
                $total = $subject_db->where($where)->count();
                $limit=(($page - 1) * $rows>$total) ?  '0' : (($page - 1) * $rows);
                $limit = $limit. "," . $rows;
                $order = $sort.' '.$order;
                $column = "`appraise_id`,`appraise_id` as appraise_ids,`member_id`,`member_name`,`subject_id`,`content`,`raw_add_time`,`raw_update_time`";
                $list = $total ? $subject_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();
                $data = array('total'=>$total, 'rows'=>$list);
                $this->ajaxReturn($data);
            }else{
                $menu_db = D('Menu');
                $currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
                $datagrid = array(
                    'options'     => array(
                        'title'   => $currentpos,
                        'url'     => U('Forum/appraiseList', array('grid'=>'datagrid')),
                        'toolbar' => 'admin_appraiselist_datagrid_toolbar',
                    ),
                    'fields' => array(
                        '评论ID'    => array('field'=>'appraise_id','width'=>15,'sortable'=>true),
                        '评价人ID'      => array('field'=>'member_id','width'=>15,'sortable'=>true),
                        '评价人'      => array('field'=>'member_name','width'=>15,'sortable'=>true),
                        '主题ID'      => array('field'=>'subject_id','width'=>15,'sortable'=>true),
                        '内容'  => array('field'=>'content','width'=>15,'sortable'=>true),
                        '添加时间'    => array('field'=>'raw_add_time','width'=>15,'sortable'=>true),
                        '操作'    => array('field'=>'appraise_ids','width'=>55,'sortable'=>true,'formatter'=>'adminSubjectListOperateFormatter'),
                    )
                );
                $this->assign('datagrid', $datagrid);
                $this->display('appraise_list');
            }
        }

}