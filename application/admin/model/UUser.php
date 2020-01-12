<?php

namespace app\admin\model;
use think\Model;
use think\Cookie;
use gmars\rbac\Rbac;
// use app\fc\model\FcFile;
// use app\admin\model\AdLog;

class UUser extends Model
{
    protected $autoWriteTimestamp = true;

    public function login($user_name, $password){
    	if ($user_name) {
    		$where['user_name'] = $user_name;
    	}else{
    		throw new \Exception("参数user_name不能为空", 400);
    	}
    	if ($password) {
    		$where['password'] = md5($password);
    	}else{
    		throw new \Exception("参数password不能为空", 400);
    	}

		$user_info = db("u_user")->field('id,user_name,status')->where($where)->find();
		if ($user_info) {
			if ($user_info['status'] == 0) {
				throw new \Exception("账号已被禁用", 400);
			}elseif($user_info['status'] == 1){
				$rbac   = new Rbac;
				$result = $rbac->cachePermission($user_info['id']);
		    	if (!$result) {
		    		throw new \Exception("权限缓存失败", 400);
		    	}else{
                    // AdLog::log($user_info['id'],AdLog::USER_MANAGE, $user_info['id'], '管理员登录');
		    		unset($user_info['id']);
		    		unset($user_info['status']);
		    		// $user_info['avatar'] = FcFile::getPath($user_info['avatar']);
		    		return $user_info;
		    	}
			}
		}else{
			throw new \Exception("账号或密码错误", 400);
		}
    }

    public function edit($id, $password, $mobile, $status, $avatar){
    	if ($password == true && $mobile == true) {
			throw new \Exception("同时只能修改一个参数", 400);
		}
		if (!db("u_user")->where('id', $id)->find()) {
			throw new \Exception("参数id不存在", 400);
		}
		if (!$user_name) {
			throw new \Exception("参数user_name不能为空", 1);
		}
		if (!$password) {
			throw new \Exception("参数password不能为空", 1);
		}
		if (!$mobile) {
			throw new \Exception("参数mobile不能为空", 1);
		}
		$where['id']       = $id;
		$data['password']  = md5($password);
		$data['mobile']    = $mobile;
		$data['status']    = $status;
		$data['avatar']	   = $avatar;
		$result            = $this->save($data, $where);
		if ($result) {
			throw new \Exception("编辑成功", 200);
		}else{
			throw new \Exception("编辑用户失败", 400);
		}
    }

    public function getUidStatus($uid){
    	$result = self::get(['uid' => $uid]);
    	if ($result) {
    		if ($result->getAttr('status') == 1) {
    			return 1;
    		}else{
    			return 0;
    		}
    	}else{
    		return -1;
    	}
    }

    public function getList($keywords, $date_time, $page, $length){
    	if ($keywords) {
    		$where['user_name|mobile'] = ['like', '%'.$keywords.'%'];
    	}
        if ($date_time) {
            $tmp = explode("~", $date_time);
            $start_time['create_time'] = ['>=', $tmp[0]." 00:00:00"];
            $end_time['create_time']   = ['<=', $tmp[1]." 23:59:59"];
        }else{
            $start_time = '1=1';
            $end_time   = '1=1';
        }
        if (!isset($where)) {
            $where = '1=1';
        }
		$result           = db("u_user")->alias("u")->where($where)->where($start_time)->where($end_time)->field('u.id,u.user_name,u.mobile,u.last_login_time,u.status,u.region_auth,u.create_time,r.shortname')->limit($page * $length, $length)->join('d_region r', 'u.region_id = r.id', 'left')->order("create_time desc")->select();
        $sn = 1;
        foreach ($result as $key => $value) {
            $result[$key][$key] = $sn;
            $result[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            ++$sn;
        }
        return $result;
    }
    
}