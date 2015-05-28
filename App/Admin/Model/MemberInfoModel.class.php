<?php
namespace Admin\Model;
use Think\Model;

class MemberInfoModel extends Model{
    protected $tableName = 'member_info';
	protected $pk        = 'member_info_id';
	public    $error;
	
	/**
	 * 登录验证
	 */
	public function login($username, $password){
	    $times_db = M('times');
	    
        //查询帐号
        $r = $this->where(array('username'=>$username))->find();
        if(!$r){
            $this->error = '管理员不存在';
            return false;
        }
		
	    //密码错误剩余重试次数
        $rtime = $times_db->where(array('username'=>$username, 'isadmin'=>'1'))->find();
        if($rtime['times'] >= C('MAX_LOGIN_TIMES')) {
            $minute = C('LOGIN_WAIT_TIME') - floor((time()-$rtime['logintime'])/60);
            if ($minute > 0) {
                $this->error = "密码重试次数太多，请过{$minute}分钟后重新登录！";
                return false;
            }else {
                $times_db->where(array('username'=>$username))->delete();
            }
        }
		
		$password = md5(md5($password).$r['encrypt']);
        $ip = get_client_ip();
        if($r['password'] != $password) {
            if($rtime && $rtime['times'] < C('MAX_LOGIN_TIMES')) {
                $times = C('MAX_LOGIN_TIMES') - intval($rtime['times']);
                $times_db->where(array('username'=>$username))->save(array('ip'=>$ip,'isadmin'=>1));
                $times_db->where(array('username'=>$username))->setInc('times');
            } else {
                $times_db->where(array('username'=>$username,'isadmin'=>1))->delete();
                $times_db->add(array('username'=>$username,'ip'=>$ip,'isadmin'=>1,'logintime'=>SYS_TIME,'times'=>1));
                $times = C('MAX_LOGIN_TIMES');
            }
            $this->error = "密码错误，您还有{$times}次尝试机会！";
            return false;
        }
        
        $times_db->where(array('username'=>$username))->delete();
        $this->where(array('userid'=>$r['userid']))->save(array('lastloginip'=>$ip,'lastlogintime'=>time()));
        
        session('userid', $r['userid']);
        session('roleid', $r['roleid']);
        cookie('username', $username);
        cookie('userid', $r['userid']);
        
        return true;
	}
	
	/**
	 * 获取用户信息
	 */
	public function getTeacherInfo($teacherId){
	    $info = $this->where(array('teacher_id'=>$teacherId))->find();
	    return $info;
	}
    
	/**
	 * 修改密码
	 */
	public function editPassword($userid, $password){
		$userid = intval($userid);
		if($userid < 1) return false;
		$passwordinfo = password($password);
		return $this->where(array('userid'=>$userid))->save($passwordinfo);
	}
}