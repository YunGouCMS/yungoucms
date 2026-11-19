<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');

System::load_app_class('base', 'member', 'no');
System::load_sys_fun('user');

class index extends base
{
    private $channel_uid;
    private $channel_info;

    public function __construct()
    {
        $this->db = System::load_sys_class("model");
        parent::__construct();
        $this->checkLogin();
    }

    /**
     * 检查登录状态
     */
    private function checkLogin()
    {
        // 优先使用 $_COOKIE，如果不存在再使用 _getcookie
        $this->channel_uid = isset($_COOKIE['channel_uid']) ? $_COOKIE['channel_uid'] : _getcookie("channel_uid");
        $channel_shell = isset($_COOKIE['channel_shell']) ? $_COOKIE['channel_shell'] : _getcookie("channel_shell");

        if (!$this->channel_uid || !$channel_shell) {
            header("Location: " . G_WEB_PATH . "/channel_admin/login");
            exit;
        }

        // 验证用户信息
        $this->channel_info = $this->db->GetOne("SELECT * FROM `@#_member` WHERE `uid`='{$this->channel_uid}' AND `level`=2");

        if (!$this->channel_info) {
            _setcookie("channel_uid", "", time() - 3600);
            _setcookie("channel_shell", "", time() - 3600);
            header("Location: " . G_WEB_PATH . "/channel_admin/login");
            exit;
        }

        // 验证cookie有效性 - 使用 DECODE 模式解密后比较（与系统其他模块一致）
        $mobile = isset($this->channel_info['mobile']) && $this->channel_info['mobile'] !== null ? (string)$this->channel_info['mobile'] : '';
        $email = isset($this->channel_info['email']) && $this->channel_info['email'] !== null ? (string)$this->channel_info['email'] : '';
        $uid = (string)$this->channel_info['uid'];
        $password = (string)$this->channel_info['password'];
        
        // 解密 cookie 值
        $decoded_shell = _encrypt($channel_shell, 'DECODE');
        
        // 计算期望的值
        $expected_shell = md5($uid . $password . $mobile . $email);
        
        // 如果验证失败，清除cookie并重定向
        if ($decoded_shell != $expected_shell) {
            _setcookie("channel_uid", "", time() - 3600);
            _setcookie("channel_shell", "", time() - 3600);
            header("Location: " . G_WEB_PATH . "/channel_admin/login");
            exit;
        }
    }

    /**
     * 获取时间范围
     */
    private function getTimeRange()
    {
        $time_type = isset($_GET['time_type']) ? trim($_GET['time_type']) : '';
        $start_time = isset($_GET['start_time']) ? trim($_GET['start_time']) : '';
        $end_time = isset($_GET['end_time']) ? trim($_GET['end_time']) : '';
        
        $now = time();
        $today_start = strtotime(date('Y-m-d 00:00:00', $now));
        $today_end = strtotime(date('Y-m-d 23:59:59', $now));
        
        switch ($time_type) {
            case 'today':
                return array('start' => $today_start, 'end' => $today_end);
            case 'week':
                $week_start = strtotime('monday this week', $now);
                return array('start' => $week_start, 'end' => $today_end);
            case 'month':
                $month_start = strtotime(date('Y-m-01 00:00:00', $now));
                return array('start' => $month_start, 'end' => $today_end);
            case 'custom':
                if ($start_time && $end_time) {
                    $start = strtotime($start_time . ' 00:00:00');
                    $end = strtotime($end_time . ' 23:59:59');
                    if ($start && $end && $start <= $end) {
                        return array('start' => $start, 'end' => $end);
                    }
                }
                break;
        }
        
        // 默认返回所有时间
        return array('start' => 0, 'end' => $now);
    }

    /**
     * 从 QUERY_STRING 中解析参数（因为路由系统可能不会正确解析 & 后的参数）
     */
    private function parseQueryParams()
    {
        $params = array();
        if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
            $query_string = $_SERVER['QUERY_STRING'];
            // 查找路由后的参数部分（& 后面的内容）
            $route_pattern = '/\?\/channel_admin\/index/';
            if (preg_match($route_pattern, $query_string, $matches, PREG_OFFSET_CAPTURE)) {
                $after_route = substr($query_string, $matches[0][1] + strlen($matches[0][0]));
                // 移除开头的 & 或 ?
                $after_route = ltrim($after_route, '&?');
                if ($after_route) {
                    parse_str($after_route, $params);
                }
            }
        }
        // 合并 $_GET 中的参数（优先级更高）
        return array_merge($params, $_GET);
    }

    /**
     * 首页 - 显示下级数据
     */
    public function init()
    {
        $uid = $this->channel_uid;
        
        // 解析查询参数
        $query_params = $this->parseQueryParams();
        
        // 获取时间范围
        $time_type = isset($query_params['time_type']) ? trim($query_params['time_type']) : '';
        $start_time = isset($query_params['start_time']) ? trim($query_params['start_time']) : '';
        $end_time = isset($query_params['end_time']) ? trim($query_params['end_time']) : '';
        
        $now = time();
        $today_start = strtotime(date('Y-m-d 00:00:00', $now));
        $today_end = strtotime(date('Y-m-d 23:59:59', $now));
        
        switch ($time_type) {
            case 'today':
                $time_start = $today_start;
                $time_end = $today_end;
                break;
            case 'week':
                $time_start = strtotime('monday this week', $now);
                $time_end = $today_end;
                break;
            case 'month':
                $time_start = strtotime(date('Y-m-01 00:00:00', $now));
                $time_end = $today_end;
                break;
            case 'custom':
                if ($start_time && $end_time) {
                    $time_start = strtotime($start_time . ' 00:00:00');
                    $time_end = strtotime($end_time . ' 23:59:59');
                    if (!$time_start || !$time_end || $time_start > $time_end) {
                        $time_start = 0;
                        $time_end = $now;
                    }
                } else {
                    $time_start = 0;
                    $time_end = $now;
                }
                break;
            default:
                $time_start = 0;
                $time_end = $now;
                break;
        }
        
        // 获取搜索关键词
        $search_keyword = isset($query_params['search']) ? trim($query_params['search']) : '';
        
        // 获取排序字段和方向
        $sort_field = isset($query_params['sort']) ? trim($query_params['sort']) : '';
        $sort_order = isset($query_params['order']) && strtolower($query_params['order']) == 'asc' ? 'ASC' : 'DESC';
        
        // 构建WHERE条件
        $where_conditions = array("m.yaoqing='$uid'");
        
        // 添加时间筛选条件（筛选注册时间）
        if ($time_start > 0) {
            $where_conditions[] = "m.time >= $time_start";
        }
        if ($time_end > 0) {
            $where_conditions[] = "m.time <= $time_end";
        }
        
        // 添加搜索条件
        if ($search_keyword) {
            if (is_numeric($search_keyword)) {
                $where_conditions[] = "m.uid = '" . intval($search_keyword) . "'";
            } else {
                $search_keyword_escaped = addslashes($search_keyword);
                $where_conditions[] = "(m.username LIKE '%$search_keyword_escaped%' OR m.mobile LIKE '%$search_keyword_escaped%')";
            }
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        // 查询下级用户数据
        $field = "COALESCE(SUM(CASE WHEN r.id is not null and r.content <> '使用佣金充值到账户' THEN r.money ELSE 0 END), 0) as rechange_total,
                COALESCE(SUM(CASE WHEN w.id is not null THEN w.money ELSE 0 END), 0) as cashout_total";

        $sql = "SELECT m.uid,m.username,m.mobile,m.money,m.time,latest_login.login_time,$field FROM `@#_member` m 
            left join `@#_member_rechange` r on r.uid=m.uid 
            left join `@#_member_cashout` W on W.uid=m.uid 
            LEFT JOIN (
                SELECT 
                mll1.user_id,
                mll1.ip,
                mll1.login_time
                FROM go_member_login_log mll1
                JOIN (
                SELECT user_id, MAX(id) AS max_id
                FROM go_member_login_log
                WHERE type = '1'
                GROUP BY user_id
                ) mll2 ON mll1.id = mll2.max_id
            ) latest_login ON m.uid = latest_login.user_id
            where $where_clause group by m.uid";
        
        // 添加排序（默认按注册时间降序）
        if ($sort_field != 'sub_recharge' && $sort_field != 'sub_register') {
            $order_by = "m.time DESC";
            $sql .= " ORDER BY $order_by";
        }

        $id_list = $this->db->Getlist($sql);
        $info = array();

        foreach ($id_list as $key => $value) {
            $info[$key]['id'] = $value['uid'];
            $info[$key]['uid'] = $value['uid'];
            $info[$key]['money'] = $value['money'];
            $info[$key]['time'] = $value['time'];
            $info[$key]['login_time'] = $value['login_time'];
            if ($value['username']) {
                $info[$key]['name'] = $value['username'];
            } else {
                $info[$key]['name'] = $value['mobile'] ?: '未设置';
            }
            $money = $this->db->Getone("SELECT sum(money) as m FROM `@#_member_recodes` where uid = {$value['uid']} and type='1'");
            $info[$key]['total_money'] = sprintf("%.2f", $money['m'] ?: 0);
            $info[$key]['rechange_total'] = sprintf("%.2f", $value['rechange_total'] ?: 0);
            $info[$key]['cashout_total'] = sprintf("%.2f", $value['cashout_total'] ?: 0);
            
            // 计算下级总充值（该用户的所有下级用户的充值总额）
            $sub_recharge = $this->db->Getone("SELECT COALESCE(SUM(r.money), 0) as total FROM `@#_member_rechange` r 
                INNER JOIN `@#_member` m ON r.uid = m.uid 
                WHERE m.yaoqing = '{$value['uid']}' AND r.content <> '使用佣金充值到账户'");
            $info[$key]['sub_recharge_total'] = sprintf("%.2f", $sub_recharge['total'] ?: 0);
            
            // 计算下级总注册（该用户的下级用户总数）
            $sub_register = $this->db->Getone("SELECT COUNT(*) as count FROM `@#_member` WHERE yaoqing = '{$value['uid']}'");
            $info[$key]['sub_register_count'] = intval($sub_register['count'] ?: 0);
        }
        
        // 如果按子级数据排序，需要重新排序数组
        if ($sort_field == 'sub_recharge' || $sort_field == 'sub_register') {
            $sort_key = $sort_field == 'sub_recharge' ? 'sub_recharge_total' : 'sub_register_count';
            usort($info, function($a, $b) use ($sort_key, $sort_order) {
                $val_a = $sort_key == 'sub_recharge_total' ? floatval($a[$sort_key]) : intval($a[$sort_key]);
                $val_b = $sort_key == 'sub_recharge_total' ? floatval($b[$sort_key]) : intval($b[$sort_key]);
                if ($sort_order == 'ASC') {
                    return $val_a <=> $val_b;
                } else {
                    return $val_b <=> $val_a;
                }
            });
        }

        // 统计总数（根据筛选条件）
        $total_count = count($info);
        $total_recharge = array_sum(array_column($info, 'rechange_total'));
        $total_cashout = array_sum(array_column($info, 'cashout_total'));
        
        // 计算筛选时间范围内的充值
        $total_recharge_filtered = 0;
        if ($time_start > 0 || $time_end > 0) {
            $time_where = "m.yaoqing='$uid'";
            if ($time_start > 0) {
                $time_where .= " AND r.time >= $time_start";
            }
            if ($time_end > 0) {
                $time_where .= " AND r.time <= $time_end";
            }
            $filtered_recharge = $this->db->Getone("SELECT COALESCE(SUM(r.money), 0) as total FROM `@#_member_rechange` r 
                INNER JOIN `@#_member` m ON r.uid = m.uid 
                WHERE $time_where AND r.content <> '使用佣金充值到账户'");
            $total_recharge_filtered = sprintf("%.2f", $filtered_recharge['total'] ?: 0);
        } else {
            $total_recharge_filtered = $total_recharge;
        }

        // 传递变量到模板
        $channel_uid = $this->channel_uid;
        $channel_info = $this->channel_info;
        
        // 传递筛选参数
        $filter_params = array(
            'time_type' => $time_type,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'search' => $search_keyword,
            'sort' => $sort_field,
            'order' => strtolower($sort_order)
        );
        
        $tpl_path = dirname(__FILE__) . '/tpl/index.tpl.php';
        include $tpl_path;
    }
}

