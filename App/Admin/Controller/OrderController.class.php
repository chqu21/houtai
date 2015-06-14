<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理通用模块
 * @author wangdong
 */
class OrderController extends CommonController {

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
     * 老师列表
     */
    public function orderList($page = 1, $rows = 10,$search = array(),$sort = 'order_id', $order = 'desc'){
        if(IS_POST){
            $master_db = D('OrderMaster');
            $detail_db = D('OrderDetail');
            $member_info_db = D('MemberInfo');
            //搜索
            $where = array();
            if (!empty($search['payment_status']) && $search['payment_status']==2){
                $search['payment_status'] = 0;
            }
            $newMember = 0;
            $mIds = array();
            if (!empty($search['new_member'])){
                $newMember = 1;
                unset($search['new_member']);
                if (!empty($search['raw_add_time'])){
                    $member_info = $member_info_db->where("`raw_add_time` like '%".$search['raw_add_time']."%'")->select();
                    foreach($member_info as $m){
                        $mIds[] = $m['member_id'];
                    }
                }
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
            if (!empty($mIds)){
                $where = $where.' and member_id in ('.implode(',',$mIds).')';
            }
            $limit=($page - 1) * $rows . "," . $rows;
            $total = $master_db->where($where)->count();
            $order = $sort.' '.$order;
            $column = "`order_code`,`total_price`,`payment_price`,`total_time`,`payment_status`,`order_status`,`coupon_id`,`coupon_price`,`member_id`,`member_name`,`payment_method`,`teacher_id`,`teacher_name`,`order_id` as order_ids,`cancel_reason`,`raw_add_time`";
            $list = $total ? $master_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();
            foreach($list as $k => $lt){
                $orderCode = $lt['order_code'];
                $mInfo =$detail_db->field("`class_method`,`class_address`,`student_name`,`student_mobile`,`course_name`,`course_price`,`course_time`,`class_begin_time`,`time_quantum`,`teacher_area`")->where("order_code=$orderCode")->select();
                $list[$k] = array_merge($lt,$mInfo[0]);
                if ($lt['payment_status']==1){
                    $list[$k]['payment_status'] = '已付款';
                }else{
                    $list[$k]['payment_status'] = '未付款';
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
                    'url'     => U('Order/orderList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_orderlist_datagrid_toolbar',
                ),
                'fields' => array(
                    '订单号'    => array('field'=>'order_code','width'=>15,'sortable'=>true),
                    '课程名'      => array('field'=>'course_name','width'=>15,'sortable'=>true),
                    '上课老师'      => array('field'=>'teacher_name','width'=>7,'sortable'=>true),
                    '学生名'    => array('field'=>'student_name','width'=>7,'sortable'=>true),
                    '学生手机号'  => array('field'=>'student_mobile','width'=>15,'sortable'=>true),
                    '课时'    => array('field'=>'course_time','width'=>15,'sortable'=>true),
                    '开始时间'    => array('field'=>'class_begin_time','width'=>15,'sortable'=>true),
                    '时间段'    => array('field'=>'time_quantum','width'=>15,'sortable'=>true),
                    '付款状态' => array('field'=>'payment_status','width'=>15,'sortable'=>true),
                    '订单状态' => array('field'=>'order_status','width'=>15,'sortable'=>true),
                    '优惠券ID' => array('field'=>'coupon_id','width'=>15,'sortable'=>true),
                    '优惠券价格' => array('field'=>'coupon_price','width'=>25,'sortable'=>true),
                    '下单时间' => array('field'=>'raw_add_time','width'=>25,'sortable'=>true),
                    '订单总价' => array('field'=>'total_price','width'=>15,'sortable'=>true),
                    '付款总价' => array('field'=>'total_price','width'=>15,'sortable'=>true),
                    '操作'    => array('field'=>'order_id','width'=>55,'sortable'=>true,'formatter'=>'adminMemberListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('order_list');
        }
    }

}