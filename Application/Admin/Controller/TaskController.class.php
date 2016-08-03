<?php
namespace Admin\Controller;
use Think\Controller;

class TaskController extends CommonController
{
    public function _initialize()
	{
		parent::_initialize();
		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}
    }

    public function sign()
    {
        $time = time();
        $time_str = date('Y-m-d ', $time);
    	$m_time = strtotime($time_str . '12:00:00');

    	$is_morning = true;
    	if ($time >= $m_time) {
    		$is_morning = false;
    	}

        $uid = $this->uid;
        $where = array(
            array('member_uid' => array('EQ', $uid)),
            array('status'     => array('EQ', 0)),
            array('s_time'     => array('ELT', $time)),
            array('e_time'     => array('EGT', $time))
        );

        $taskModel = M('task');
        $taskArr   = $taskModel->where($where)->select();

        $leader_uids = makeImplode($taskArr, 'leader_uid');
        $project_ids = makeImplode($taskArr, 'project_id');
        $work_ids    = makeImplode($taskArr, 'work_id');

        $userModel    = M('user');
        $projectModel = M('project');
        $workModel    = M('work');

        $leaders_arr  = $userModel->where(array('id' => array('IN', "$leader_uids")))->getField('id, truename', true);
        $projects_arr = $projectModel->where(array('id' => array('IN', "$project_ids")))->getField('id, project_name', true);
        $works_arr    = $workModel->where(array('id' => array('IN', "$work_ids")))->getField('id, work_name', true);

        $data = array();
        foreach ($taskArr as $task) {
            $leader_uid = $task['leader_uid'];
            $project_id = $task['project_id'];
            $work_id    = $task['work_id'];

            $leader_truename = '';
            if (isset($leaders_arr[$leader_uid])) {
                $leader_truename = $leaders_arr[$leader_uid];
            }

            $project_name = '';
            if (isset($projects_arr[$project_id])) {
                $project_name = $projects_arr[$project_id];
            }

            $work_name = '';
            if (isset($works_arr[$work_id])) {
                $work_name = $works_arr[$work_id];
            }

            $tmp = array();

            $tmp = $task;
            $tmp['leader_truename'] = $leader_truename;
            $tmp['project_name']    = $project_name;
            $tmp['work_name']       = $work_name;

            $data[] = $tmp;
        }

        $this->assign('data',  $data);
        $this->assign('index', 1);

        if ($is_morning) {
            $this->display('morning');
        } else {
            $this->display('afternoon');
        }
    }

    public function morningHandle()
    {
        $time = time();
        if (! is_sign_time($time)) {
            alert_back('现在不是上午打卡时间！');
        }

        $user_id = $this->uid;
        $ip = get_ip_address(true);

        $signModel = M('sign_records');

        $time_str = date('Y-m-d ', $time);

        $s_timestamp = strtorime($time_str . '00:00:00');
        $e_timestamp = strtorime($time_str . '23:59:59');

        $total_where = array(
            'user_id' => array('EQ', $user_id),
            'c_time'  => array('EGT', $s_timestamp),
            'c_time'  => array('ELT', $e_timestamp)
        );

        $total = $signModel->where($total_where)->count();
        if ($total > 0) {
            alert_back('打卡失败！今日你已经打过卡了！');
        }

        $sign_data = array(
            'user_id' => $user_id,
            'ip'      => $ip,
            'c_time'  => $time
        );

        $sign_res  = $signModel->add($sign_data);

        if ($sign_res === false) {
            alert_go('打卡失败！请联系管理员处理', 'admin/home/sign');
        }

        $time_str = date('Y年m月d日 H:i:s', $time);
        $success  = '打卡成功！今日打卡时间：' . $time_str;

        alert_back($success);
    }

    public function afternoonHandle()
    {
    	$time = time();
        if (! is_sign_time($time)) {
        	alert_back('现在不是下午打卡时间！');
        }

        $post_task = $_POST['task'];
        $finished_task_ids = array();

        if (! empty($post_task)) {
            if (! is_array($post_task)) {
                alert_back('表单数据错误！');
            }

            $task_data = array();
            foreach ($post_task as $task) {
                $id = intval($task['id']);
                $completion = intval($task['completion']);

                if ($completion === 100) {
                    $finished_task_ids[] = $id;
                }

                $task_data[] = array(
                    // ''
                );
            }
        } // end of if task is not empty
    }
}
