<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->displayCarousel();
        $this->display();
    }

    public function boss()
    {
        $this->displayProjectPie();
        $this->displayWorkPie();
        $this->displayTaskPie();
        $this->displayTasksBar();
        $this->display();
    }

    private function displayProjectPie()
    {
        $projectModel = M('project');
        $projectArr = $projectModel->group('status')->getField('status, count(id)');
        $project_pie_data = array();
        foreach ($projectArr as $status => $val) {
            $arr = array();
            switch ($status) {
                case 0:
                    $arr = array('name' => '未启动', 'value' => $val);
                    break;

                case 1:
                    $arr = array('name' => '进行中', 'value' => $val);
                    break;

                case 2:
                    $arr = array('name' => '已结束', 'value' => $val);
                    break;

                case 3:
                    $arr = array('name' => '禁用', 'value' => $val);
                    break;

                case 4:
                    $arr = array('name' => '已打分', 'value' => $val);
                    break;
            }
            $project_pie_data[] = $arr;
        }
        $project_pie_name = array('未启动','进行中','已结束','禁用','已打分',);
        $this->assign('project_pie_data', json_encode($project_pie_data));
        $this->assign('project_pie_name', json_encode($project_pie_name));
    }

    private function displayWorkPie()
    {
        $workModel = M('work');
        $workArr = $workModel->group('status')->getField('status, count(id)');
        $work_pie_data = array();
        foreach ($workArr as $status => $val) {
            $arr = array();
            switch ($status) {
                case 0:
                    $arr = array('name' => '未启动', 'value' => $val);
                    break;

                case 1:
                    $arr = array('name' => '进行中', 'value' => $val);
                    break;

                case 2:
                    $arr = array('name' => '已结束', 'value' => $val);
                    break;

                case 3:
                    $arr = array('name' => '已总结', 'value' => $val);
                    break;
            }
            $work_pie_data[] = $arr;
        }
        $work_pie_name = array('未启动', '进行中', '已结束', '已总结');
        $this->assign('work_pie_data', json_encode($work_pie_data));
        $this->assign('work_pie_name', json_encode($work_pie_name));
    }

    private function displayTaskPie()
    {
        $taskModel = M('task');
        $taskArr = $taskModel->group('status')->getField('status, count(id)');
        $task_pie_data = array();
        foreach ($taskArr as $status => $val) {
            $arr = array();
            switch ($status) {
                case 0:
                    $arr = array('name' => '进行中', 'value' => $val);
                    break;

                case 1:
                    $arr = array('name' => '已结束', 'value' => $val);
                    break;

                case 2:
                    $arr = array('name' => '禁用', 'value' => $val);
                    break;
            }
            $task_pie_data[] = $arr;
        }
        $task_pie_name = array('进行中', '已结束', '禁用');
        $this->assign('task_pie_data', json_encode($task_pie_data));
        $this->assign('task_pie_name', json_encode($task_pie_name));
    }

    private function displayTasksBar()
    {
        $taskModel = M('task');
        $taskArr = $taskModel->where(array('status' => 0))->field('task_name, completion')->select();
        $name = $data = array();
        foreach ($taskArr as $task) {
            $name[] = $task['task_name'];
            // $data[] = array('name' => $task['task_name'], 'value' => $task['completion']);
            $data[] = intval($task['completion']);
        }
        $this->assign('task_name',       json_encode($name));
        $this->assign('task_completion', json_encode($data));
    }

    private function displayCarousel()
    {

    }
}
