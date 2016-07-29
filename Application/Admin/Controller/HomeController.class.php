<?php
namespace Admin\Controller;
use Think\Controller;

class HomeController extends CommonController
{
    public function _initialize()
	{
		parent::_initialize();
    }

    public function sign()
    {
        $this->display();
    }

    public function signHandle()
    {
        $user_id = session('user_id');
        $ip = get_ip_address(true);
        $time = time();

        $signModel = M('sign_records');

        $date_arr = getdate($time);
        $s_y = $date_arr['year'];
        $s_m = $date_arr['mon'];
        $s_d = $date_arr['mday'];

        $s_timestamp = mktime(0, 0, 0, $s_m, $s_d, $s_y);
        $e_timestamp = $s_timestamp + (24 * 60 * 60);

        $total_where = array(
            'user_id' => array('EQ', $user_id),
            'c_time'  => array('EGT', $s_timestamp),
            'c_time'  => array('ELT', $e_timestamp)
        );

        $total = $signModel->where($total_where)->count();
        if ($total > 0) {
            alert_go('打卡失败！今日你已经打过卡了！', 'admin/home/sign');
        }

        $data = array(
            'user_id' => $user_id,
            'ip'      => $ip,
            'c_time'  => $time
        );

        $sign_res  = $signModel->add($data);

        if ($sign_res === false) {
            alert_go('打卡失败！请联系管理员处理', 'admin/home/sign');
        }

        $time_str = date('Y年m月d日 H:i:s');
        $success  = '打卡成功！今日打卡时间：' . $time_str;

        alert_go($success, 'admin/home/sign');
    }
}
