<?php

namespace app\api\controller;

use \think\Db;

class Recyclebin extends BaseController
{
    /**
     * 查看被删除的查寝记录
     */
    public function checkDeletedRecords()
    {
        // $parameter = ['grade', 'department', 'page'];
        // 输入判断
        if (empty($_POST['grade'])) {
            return json(['error_code' => 1, 'msg' => '请输入年级！']);
        } else if (empty($_POST['department'])) {
            return json(['error_code' => 1, 'msg' => '请输入系！']);
        } else if (empty($_POST['page'])) {
            return json(['error_code' => 1, 'msg' => '请输入页码！']);
        } 

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        $record = Db::table('record')
            ->field('start_time, end_time')   // 指定字段
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.dorm = d.dorm_num')
            ->distinct(true)   // 返回唯一不同的值
            ->where($where)
            ->where('r.deleted', 1)
            ->order('start_time desc')
            ->page($_POST['page'] - 1, 7)    // page('第几页','每页显示的数量')
            ->select();

        if ($record) {
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '显示查寝记录！';
            $return_data['data'] = $record;
            return json($return_data);
        } else {
            return json(['error_code' => 2, 'msg' => '暂无查寝记录！']);
        }
    }

    /**
     * 选择日期查看被删除的查寝记录
     */
    public function specifiedDeletedDate()
    {
        // $parameter = ['grade', 'department', 'date'];
        // 输入判断
        if (empty($_POST['grade'])) {
            return json(['error_code' => 1, 'msg' => '请输入年级！']);
        } else if (empty($_POST['department'])) {
            return json(['error_code' => 1, 'msg' => '请输入系！']);
        } else if (empty($_POST['date'])) {
            return json(['error_code' => 1, 'msg' => '请输入时间！']);
        } 

        $date = $_POST['date'];

        // 判断是否非法日期
        $is_date = strtotime($date) ? strtotime($date) : false;
        if ($is_date === false && $date != "") {
            return json(['error_code' => 3, 'msg' => '日期格式非法！']);
        }

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        // dump($_POST['date']);
        $record = Db::table('record')
            ->field('start_time, end_time')   // 指定字段
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.dorm = d.dorm_num')
            ->distinct(true)   // 返回唯一不同的值
            ->where($where)
            ->where('start_time', 'between time', [$date . ' 00:00:00', $date . ' 23:59:59'])
            ->where('r.deleted', 1)
            ->select();

        if ($record) {
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '显示' . $date . '查寝记录！';
            $return_data['data'] = $record;
            return json($return_data);
        } else {
            $return_data = array();
            $return_data['error_code'] = 2;
            $return_data['msg'] = '没有' . $date . '这天查寝记录！';
            return json($return_data);
        }
    }

    /**
     * 恢复查寝记录
     */
    public function recoverRecord()
    {
        // $parameter = ['department', 'grade', 'start_time', 'end_time'];
        // 输入判断
        if (empty($_POST['grade'])) {
            return json(['error_code' => 1, 'msg' => '请输入年级！']);
        } else if (empty($_POST['department'])) {
            return json(['error_code' => 1, 'msg' => '请输入系！']);
        } else if (empty($_POST['start_time'])) {
            return json(['error_code' => 1, 'msg' => '请输入开始时间！']);
        } else if (empty($_POST['end_time'])) {
            return json(['error_code' => 1, 'msg' => '请输入结束时间！']);
        }

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        $where['r.start_time'] = $_POST['start_time'];
        $where['r.end_time'] = $_POST['end_time'];

        $result = Db::table('record')
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.dorm = d.dorm_num')
            ->where($where)
            ->update(['record.deleted' => 0]);

        if ($result) {
            return json(['error_code' => 0, 'msg' => '恢复查寝记录！']);
        } else {
            return json(['error_code' => 2, 'msg' => '无此时间段的查寝记录！']);
        }
    }

    /**
     * 查看被删除的详细
     */
    public function viewDeletedDetails()
    {
        // $parameter = ['grade', 'department', 'start_time', 'end_time'];
        // 输入判断
        if (empty($_POST['grade'])) {
            return json(['error_code' => 1, 'msg' => '请输入年级！']);
        } else if (empty($_POST['department'])) {
            return json(['error_code' => 1, 'msg' => '请输入系！']);
        } else if (empty($_POST['start_time'])) {
            return json(['error_code' => 1, 'msg' => '请输入开始时间！']);
        } else if (empty($_POST['end_time'])) {
            return json(['error_code' => 1, 'msg' => '请输入结束时间！']);
        }

        // 查询条件
        $where = array();
        $where['s.grade'] = $_POST['grade'];
        $where['s.department'] = $_POST['department'];
        $where['r.start_time'] = $_POST['start_time'];
        $where['r.end_time'] = $_POST['end_time'];
        
        $record = Db::table('record')
            ->field('start_time, end_time, photo, d.dorm_num, r.rand_num')   // 指定字段
            ->alias('r')    // 别名
            ->join('dorm d', 'd.id = r.dorm_id')
            ->join('student s', 's.dorm = d.dorm_num')
            ->where($where)
            ->where('r.deleted', 1)
            ->select();

        if ($record) {
            $return_data = array();
            $return_data['error_code'] = 0;
            $return_data['msg'] = '显示查寝记录';
            $return_data['data'] = $record;
            return json($return_data);
        } else {
            return json(['error_code' => 2, 'msg' => '无该天的查寝记录！']);
        }
    }
}
