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
            array('s_time'     => array('ELT', $time))
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

        if (is_signed_today($user_id, $time, true)) {
            alert_back('打卡失败！你今天上午已经打过卡了！');
        }

        $ip = get_ip_address(true);

        $sign_data = array(
            'user_id' => $user_id,
            'ip'      => $ip,
            'c_time'  => $time
        );

        $signModel = M('sign_records');
        $sign_res  = $signModel->add($sign_data);

        if ($sign_res === false) {
            alert_back('打卡失败！请联系管理员处理');
        }

        $time_str = date('Y年m月d日 H:i:s', $time);
        $success  = '上午打卡成功！今日打卡时间：' . $time_str;

        alert_back($success);
    }

    public function afternoonHandle()
    {
    	$time = time();
        if (! is_sign_time($time)) {
        	alert_back('现在不是下午打卡时间！');
        }

        $user_id = $this->uid;

        if (is_signed_today($user_id, $time, false)) {
            alert_back('打卡失败！你今天下午已经打过卡了！');
        }

        $ip = get_ip_address(true);

        $sign_data = array(
            'user_id' => $user_id,
            'ip'      => $ip,
            'c_time'  => $time
        );

        $signModel = M('sign_records');
        $sign_res  = $signModel->add($sign_data);

        if ($sign_res === false) {
            alert_back('打卡失败！请联系管理员处理');
        }

        $post_task = $_POST['task'];
        $finished_task_ids = array();

        $taskModel    = M('task');
        $workModel    = M('work');
        $projectModel = M('project');

        if (! empty($post_task)) {
            if (! is_array($post_task)) {
                alert_back('表单数据错误！');
            }

            $task_data = array();
            foreach ($post_task as $task) {
                $id = intval($task['id']);
                $completion = intval($task['completion']);

                $update_arr = array();
                if ($completion == 100) {
                    $finished_task_ids[] = $id;
                    $update_arr = array(
                        'completion' => $completion,
                        'f_time'     => $time,
                        'status'     => 1
                    );
                } else {
                    $update_arr = array('completion' => $completion);
                }

                $res = $taskModel->where(array('id' => $id))->setField($update_arr);

                if ($res === false) {
                   alert_back('进度更新失败！');
                }
            } // end of foreach $post_task

        } // end of if task is not empty

        foreach ($finished_task_ids as $task_id) {
            $project_id = $taskModel->where(array('id' => $task_id))->getField('project_id');
            $work_id    = $taskModel->where(array('id' => $task_id))->getField('work_id');

            if (is_work_finished($work_id)) {
                $res = $workModel->where(array('id' => $work_id))->setField(array('status' => 2, 'f_time' => $time));
                if ($res === false) {
                    alert_back('更新工作状态失败！');
                }
            }

            if (is_project_finished($project_id)) {
                $res = $projectModel->where(array('id' => $project_id))->setField(array('status' => 2, 'f_time' => $time));
                if ($res === false) {
                    alert_back('更新项目状态失败！');
                }
            }
        } // end of foreach $finished_task_id

        $time_str = date('Y年m月d日 H:i:s', $time);
        $success  = '下午打卡成功！今日打卡时间：' . $time_str;

        alert_back($success);
    }
}
