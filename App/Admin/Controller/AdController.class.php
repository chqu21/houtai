<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理通用模块
 * @author wangdong
 */
class AdController extends CommonController {
    /**
	 * 后台首页
	 */
	public function index(){
	    $admin_db      = D('Admin');
	    $menu_db       = D('Menu');
	    
	    $userid = session('userid');
		$userInfo = $admin_db->getUserInfo($userid);    //获取用户基本信息
		$menuList = $menu_db->getMenu();                //头部菜单列表
		
		$this->assign('userInfo', $userInfo);
		$this->assign('menuList', $menuList);
		$this->display('index');
	}



    /**
     * 广告位列表
     */
    public function adList($page = 1, $rows = 10,$search = array(),$sort = 'ad_id', $order = 'asc'){
        if(IS_POST){
            $ad_db = D('Ad');
            //搜索
            $where = array();
            foreach ($search as $k=>$v){
                if(!$v) continue;
                $where[] = "`{$k}` like '%{$v}%'";
            }
            $where = implode(' and ', $where);
            $limit=($page - 1) * $rows . "," . $rows;
            $total = $ad_db->where($where)->count();
            $order = $sort.' '.$order;
            $column = "`ad_id`,`ad_id` as ad_ids,`pic`,`type`,`postion_id`,`postion`,`title`,`url`";
            $list = $total ? $ad_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();

            foreach($list as $k => $lt){
                if ($lt['type']==1){
                    $list[$k]['type'] = '幻灯片';
                }else{
                    $list[$k]['type'] = '单张图片';
                }
                //$list[$k]['pic'] = json_decode($lt,'r');

            }
            $data = array('total'=>$total, 'rows'=>$list);
            $this->ajaxReturn($data);
        }else{
            $menu_db = D('Menu');
            $currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
            $datagrid = array(
                'options'     => array(
                    'title'   => $currentpos,
                    'url'     => U('Ad/adList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_adList_datagrid_toolbar',
                ),
                'fields' => array(
                    '广告ID'    => array('field'=>'ad_id','width'=>15,'sortable'=>true),
                    '广告类型'      => array('field'=>'type','width'=>15,'sortable'=>true),
                    '图片'      => array('field'=>'pic','width'=>50,'sortable'=>true,'formatter'=>'adImgFormatter'),
                    '位置ID'    => array('field'=>'postion_id','width'=>20,'sortable'=>true),
                    '位置'    => array('field'=>'postion','width'=>20,'sortable'=>true),
                    '标题'    => array('field'=>'title','width'=>20,'sortable'=>true),
                    '链接地址'    => array('field'=>'url','width'=>40,'sortable'=>true),
                    '操作'    => array('field'=>'ad_ids','width'=>55,'sortable'=>true,'formatter'=>'adminAdListOperateFormatter'),
                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('ad_list');
        }
    }

    /**
     * 广告位列表
     */
    public function postionList($page = 1, $rows = 10,$search = array(),$sort = 'postion_id', $order = 'asc'){
        if(IS_POST){
            $ad_db = D('AdPostion');
            //搜索
            $where = array();
            foreach ($search as $k=>$v){
                if(!$v) continue;
                $where[] = "`{$k}` like '%{$v}%'";
            }
            $where = implode(' and ', $where);
            $limit=($page - 1) * $rows . "," . $rows;
            $total = $ad_db->where($where)->count();
            $order = $sort.' '.$order;
            $column = "`postion_id`,`postion_id` as postion_ids,`postion`,`type`";
            $list = $total ? $ad_db->field($column)->where($where)->order($order)->limit($limit)->select() : array();

            foreach($list as $k => $lt){
                if ($lt['type']==1){
                    $list[$k]['type'] = '幻灯片';
                }else{
                    $list[$k]['type'] = '单张图片';
                }
                //$list[$k]['pic'] = json_decode($lt,'r');

            }
            $data = array('total'=>$total, 'rows'=>$list);
            $this->ajaxReturn($data);
        }else{
            $menu_db = D('Menu');
            $currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
            $datagrid = array(
                'options'     => array(
                    'title'   => $currentpos,
                    'url'     => U('Ad/postionList', array('grid'=>'datagrid')),
                    'toolbar' => 'admin_postionList_datagrid_toolbar',
                ),
                'fields' => array(
                    '广告位ID'    => array('field'=>'postion_id','width'=>15,'sortable'=>true),
                    '广告类型'      => array('field'=>'type','width'=>15,'sortable'=>true),
                    '位置'    => array('field'=>'postion','width'=>20,'sortable'=>true),
                    '操作'    => array('field'=>'postion_ids','width'=>55,'sortable'=>true,'formatter'=>'adminpostionListOperateFormatter'),

                )
            );
            $this->assign('datagrid', $datagrid);
            $this->display('postion_list');
        }
    }


    /**
     * 添加广告位
     */
    public function addPostion(){
        if(IS_POST){
            $ad_postion_db = D('AdPostion');
            //$memberInfo_db = D('memberInfo');
            $data = I('post.info');
            $adInfo['type'] = $data['type'];
            $adInfo['postion'] = $data['postion'];
            $adId = $ad_postion_db->add($adInfo);
            if($adId){
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }else{
            $this->display('postion_add');
        }
    }

    /**
     * 添加广告
     */
    public function adAdd($id){
        $ad_db = D('Ad');
        $postion_db =  D('AdPostion');
        if(IS_POST){
            $data = I('post.');
            $adInfo = $ad_db->where(array('postion_id'=>$id))->find();
            $postionInfo = $postion_db->where(array('postion_id'=>$id))->find();
            if (!empty($adInfo) && ($postionInfo['type'] == 2)){
                $dataInfo['title'] = $data['title'];
                $dataInfo['url'] = $data['url'];
                $dataInfo['pic'] = $data['img_upload'];
                $result = $ad_db->where(array('postion_id'=>$id))->save($dataInfo);
            }else{
                $dataInfo['postion_id'] = $id;
                $dataInfo['postion'] = $postionInfo['postion'];
                $dataInfo['type'] = $postionInfo['type'];
                $dataInfo['title'] = $data['title'];
                $dataInfo['url'] = $data['url'];
                $dataInfo['pic'] = $data['img_upload'];
                $result = $ad_db->add($dataInfo);
            }

            if($result){
                $this->success('添加成功');
            }else {
                $this->error('添加失败');
            }
        }else{
            $info = $ad_db->getAdInfo($id);
            $info['timestamp'] = time();
            $info['token'] = md5('unique_salt'.time());
            $this->assign('info', $info);
            $this->display('ad_edit');
        }
    }






    /**
     * 编辑广告
     */
    public function adEdit($id){
        $ad_db = D('Ad');
        $postion_db =  D('AdPostion');
        if(IS_POST){
            $data = I('post.info');
            $dataInfo['title'] = $data['title'];
            $dataInfo['url'] = str_replace('&amp;','&',$data['url']);
            if (!empty($data['img_upload'])){
                $dataInfo['pic'] = $data['img_upload'];
            }
            $result = $ad_db->where(array('ad_id'=>$id))->save($dataInfo);
            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $ad_db->where(array('ad_id'=>$id))->find();
            $info['timestamp'] = time();
            $info['token'] = md5('unique_salt'.time());
            $this->assign('info', $info);
            $this->display('ad_edit');
        }
    }




    /**
     * 编辑老师
     */
    public function editPostion($id=''){
        $postion_db =  D('AdPostion');
        if(IS_POST){
            $data = I('post.info');
            $dataInfo['postion'] = !empty($data['postion']) ? $data['postion'] : '';
            $dataInfo['type'] = !empty($data['type']) ? $data['type'] : '';
            $result = $postion_db->where(array('postion_id'=>$data['postion_id']))->save($dataInfo);

            if($result){
                $this->success('修改成功');
            }else {
                $this->error('修改失败');
            }
        }else{
            $info = $postion_db->getAdInfo($id);
            $this->assign('info', $info);
            $this->display('postion_edit');
        }
    }


    /**
     * 删除广告图
     */
    public function adDelete(){
        $ad_db = D('Ad');
        $id = I('post.id');
        $result = $ad_db->where(array('ad_id'=>$id))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }

    /**
     * 删除广告位
     */
    public function postionDelete(){
        $ad_db = D('AdPostion');
        $id = I('post.id');
        $result = $ad_db->where(array('postion_id'=>$id))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }

    public function adImage($id){
        $ad_db = D('Ad');
        $postion_db =  D('AdPostion');
        $info = $postion_db->getAdInfo($id);
        if (IS_POST) {
            $targetPath = C('IMG_PATH') . 'ad/';
            $verifyToken = md5('unique_salt' . $_POST['timestamp']);
            if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
                $tempFile = $_FILES['Filedata']['tmp_name'];
                // Validate the file type
                $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
                $fileParts = pathinfo($_FILES['Filedata']['name']);
                $imgName = date(YmdHis) . rand(1000, 900000);
                $targetFile = $targetPath . $imgName . "." . $fileParts['extension'];
                if (in_array($fileParts['extension'], $fileTypes)) {
                    move_uploaded_file($tempFile, $targetFile);
                    exit(json_encode(array('rs'=>1,'pic'=> 'upload/ad/' . $imgName . "." . $fileParts['extension'])));
                } else {
                    echo 'Invalid file type.';
                }
            }
        }else{
            $info['timestamp'] = time();
            $info['token'] = md5('unique_salt'.time());
            $info['postion_id'] = $id;
            $this->assign('info', $info);
            $this->display('ad_add');
        }
        exit;
    }

}