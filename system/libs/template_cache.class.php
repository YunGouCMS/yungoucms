<?php

/**
 * template_cache.class.php    模板解析缓存类
 *
 * @copyright            (C) 2005-2010 战线
 * @license                
 * @lastmodify            2023-07-10 (升级至PHP 7.0+)
 */
 
final class template_cache {
    
    private $content;
    private $module;
    private $template;
    
    /**
    *   模板解析入口    
    *   返回解析了的模板地址
    */
    public function template_init($FileTpl, $FileHtml, $ModuleName = '', $TemplateName = '') {
        $this->module = $ModuleName;
        $this->template = $TemplateName;
        $this->content = file_get_contents($FileHtml);
        $this->template_parse();
        
        if (file_put_contents($FileTpl, $this->content) === false) {
            return false;
        }
        return true;
    }
    
    private function template_parse() {
        // 直接使用标签名替代base64编码
        $stag = 'wc';
        $etag = 'end';
        $foreach = 'loop';
        
        // 安全输出函数（默认 HTML 转义防 XSS；需裸 HTML 时请用 {wc:fun:xxx} 或 {wc:php:start}）
        $safeEcho = function($expression) {
            // 简单变量：统一转义输出
            if (preg_match('/^\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $expression)) {
                return "<?php echo htmlspecialchars((string)({$expression}), ENT_QUOTES, 'UTF-8'); ?>";
            }
            // 复杂表达式（含数组等）：转义后输出，避免 XSS
            if (strpos($expression, 'Getheader') !== false) {
                return "<?php echo $expression; ?>";
            }
            return "<?php echo htmlspecialchars((string)({$expression}), ENT_QUOTES, 'UTF-8'); ?>";
        };
        
        // 替换所有危险的PHP标签
        $this->content = preg_replace_callback(
            "/<\?php(.*)/i", 
            function($m) { return "&lt;?php{$m[1]}"; },
            $this->content
        );
        
        $this->content = preg_replace_callback(
            "/<\?=(.*)\?>/is", 
            function($m) { return "&lt;?={$m[1]}&gt;"; },
            $this->content
        );
        
        // 移除危险的PHP脚本标签
        $this->content = preg_replace([
            "/<script\s+language\s*=\s*php\s*>/is",
            "/<script\s+language\s*=\s*[\'|\"]php\s*[\'|\"]\s*>/is"
        ], "<script>", $this->content);
        
        // 条件语句处理
        $this->content = preg_replace([
            "/\{$stag:templates\s+(.+)\}/",
            "/\{$stag:if\s+(.+?)\}/",
            "/\{$stag:else\}/",
            "/\{$stag:elseif\s+(.+?)\}/",
            "/\{$stag:if:$etag\}/"
        ], [
            "<?php include templates($1);?>",
            "<?php if($1): ?>",
            "<?php else: ?>",
            "<?php elseif ($1): ?>",
            "<?php endif; ?>"
        ], $this->content);
        
        // 分页处理
        $this->content = preg_replace([
            "/\{$stag:page:(\w+)\}/",
            "/\{$stag:page:\\$(\w+)\}/"
        ], [
            "<?php echo \$page->show('$1'); ?>",
            "<?php echo \$page->$1; ?>"
        ], $this->content);
        
        // 循环处理
        $this->content = preg_replace([
            "/\{$stag:$foreach\s+(\S+)\s+(\S+)\}/",
            "/\{$stag:$foreach\s+(\S+)\s+(\S+)\s+(\S+)\}/",
            "/\{$stag:$foreach:$etag\}/"
        ], [
            "<?php \$ln=1; if(is_array($1)) foreach($1 AS $2): ?>",
            "<?php \$ln=1; if(is_array($1)) foreach($1 AS $2 => $3): ?>",
            "<?php endforeach; \$ln++; unset(\$ln); ?>"
        ], $this->content);
        
        // 函数调用
        $this->content = preg_replace_callback(
            "/\{$stag:fun:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/",
            function($m) use ($safeEcho) {
                return $safeEcho($m[1]);
            },
            $this->content
        );
        
        // 富文本原样输出（协议、帮助等后台可控内容，不转义 HTML）
        $this->content = preg_replace_callback(
            "/\{wc:raw:([^}]+)\}/",
            function($m) {
                return "<?php echo (string)(" . trim($m[1]) . "); ?>";
            },
            $this->content
        );
        
        // 变量输出（安全处理）
        $this->content = preg_replace_callback(
            "/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/",
            function($m) use ($safeEcho) {
                return $safeEcho($m[1]);
            },
            $this->content
        );
        
        $this->content = preg_replace_callback(
            "/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*([\+\-\*\/])\s*(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/",
            function($m) use ($safeEcho) {
                return $safeEcho($m[1].$m[2].$m[3]);
            },
            $this->content
        );
        
        // 复杂变量处理
        $this->content = preg_replace_callback(
            "/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)([\$a-zA-Z_0-9\[\]\'\"\$\x7f-\xff]+)\}/",
            function($m) use ($safeEcho) {
                return $safeEcho($m[1].$m[2]);
            },
            $this->content
        );
        
        $this->content = preg_replace_callback(
            "/\{$stag:([a-zA-Z_0-9\[\]\'\"\$\x7f-\xff\+\-\*\/]+)\}/",
            function($m) use ($safeEcho) {
                return $safeEcho($m[1]);
            },
            $this->content
        );
        
        // 自增/自减
        $this->content = preg_replace([
            "/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)--\}/",
            "/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)++\}/"
        ], [
            "<?php echo htmlspecialchars($1--, ENT_QUOTES); ?>",
            "<?php echo htmlspecialchars($1++, ENT_QUOTES); ?>"
        ], $this->content);
        
        // PHP代码块
        $this->content = preg_replace([
            "/\{$stag:php:start\}/",
            "/\{$stag:php:$etag\}/"
        ], [
            "<?php ",
            " ?>"
        ], $this->content);
        
        // 全局变量
        $this->content = preg_replace_callback(
            "/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s",
            function($m) use ($safeEcho) {
                return $safeEcho($m[1]);
            },
            $this->content
        );
        // 保留标签（使用回调）
		$this->content = preg_replace_callback(
			"/\{$stag:(\w+)\s+([^}]+)\}/i",
			function($matches) {
				return self::pc_tag_html($matches[1], $matches[2], $matches[0]);
			},
			$this->content
		);
        $this->content = preg_replace_callback(
            "/\{$stag:(\w+):$etag\}/i",
            function($m) {
                return self::end_html_tag($m[1], $m[0]);
            },
            $this->content
        );
        
        // 模块调用
        $this->content = preg_replace_callback(
            "/\{$stag:m=(\w+) mod=[\"|\'](\w+)[\"|\']([^}]+)\}/i",
            function($m) {
                return self::pc_module_tag($m[1], $m[2], $m[3]);
            },
            $this->content
        );
        
        $this->content = preg_replace_callback(
            "/\{$stag:m=(\w+):$etag\}/i",
            function($m) {
                return self::end_module_tag($m[1], $m[0]);
            },
            $this->content
        );
        
        // 添加安全头部
        $this->content = '<?php defined(\'G_IN_SYSTEM\') or exit(\'No permission resources.\'); ?>' 
                       . $this->content;
    }
    
    private static function pc_module_tag($op, $data, $htmls) {
        preg_match_all("/[\"|\'](.*)[\"|\']/i", stripslashes($htmls), $matches, PREG_SET_ORDER);
        
        if (empty($matches[0][0])) {
            return '';
        }
        
        $htmls = $matches[0][0];
        $htmls = str_replace(" ", "", $htmls);
        $htmls = trim($htmls, ',');
        
        preg_match_all("/return:(\w+)[\"|\']/i", $htmls, $return, PREG_SET_ORDER);
        
        $returnVar = '$data';
        if (!empty($return[0][1])) {
            $returnVar = '$' . $return[0][1];
        }
        
        $html = <<<HTML
        <?php 
        \${$op}_model = System::load_app_model('{$op}', '{$op}');
        {$returnVar} = \${$op}_model->{$data}({$htmls}); 
        ?>
HTML;
        
        return $html;
    }
    
    private static function end_module_tag($op, $htmls) {
        return '';
    }
    
    /**
     * 解析系统保留标签
     * @param string $op 操作方式
     * @param string $data 参数
     * @param string $html 匹配到的所有的HTML代码
     */
    private static function pc_tag_html($op,$data,$htmls) {
		static $display=array("get"=>false,"page"=>false);
		$oparr=array('getpage','getlist','getcount','getone','htmlcache','block');
		$modarr=array('sql','cache','mod','name','return','num','page','type','pageurl','key','func');	
		$datas = array();		
		$op=trim($op);
		$html='';
		
		preg_match_all("/([a-z]+)\=[\"]?([^\"]+)[\"]/i", stripslashes($data),$matches,PREG_SET_ORDER);	
		
		$in = [];
		foreach($matches as $v){
			$datas[$v[1]] = $v[2];
			if(in_array($v[1],$modarr)) {
				$$v[1] = $v[2];
				$in[$v[1]] = $v[2];
				continue;				 
			}
		}

		$datas['mod']=$mod=isset($mod) && trim($mod) ? trim($mod) : 'get';
		$datas['sql']=$sql=isset($in['sql']) && trim($in['sql']) ? trim($in['sql']) : '';		
		$datas['num']=$num=isset($num) ? intval($num) : 20;
		if($num<=0){$datas['num']=$num=20;}			
		$datas['page']=$page='$_GET[\'p\']';			
		$datas['pageurl']=$pageurl=0;
		$datas['key']=$key=isset($key) ? trim($key) : "''";
		
		$datas['cache']=$cache = isset($in['cache']) && intval($in['cache']) ? intval($in['cache']) : 0;
		$datas['return']=$return = isset($in['return']) && trim($in['return']) ? trim($in['return']) : 'data';
		$datas['name']=$name = isset($in['name']) && trim($in['name']) ? trim($in['name']) : 0;
		$datas['type']=$type = isset($in['type']) ? trim($in['type']) : 1;

		if(!in_array($type,array('MYSQL_ASSOC','MYSQL_NUM','MYSQL_BOTH'))){
			$type=1;
		}		
		if(!in_array($op,$oparr)){
			return stripslashes($htmls);
		}
		switch($op){			
			case 'getlist':		
				if(empty($sql)){return stripslashes($htmls);}		
				$html.='<?php '.'$'.$return.'='.'$this->DB()->GetList("'.$sql.'",array("type"=>'.$type.',"key"=>'.$key.',"cache"=>'.$cache.')); ?>';
				break;
			case 'getpage':
				if(empty($sql)){return stripslashes($htmls);}
				$html.='<?php $num='.$num.';$total=$this->DB()->GetCount("'.$sql.'"); ?>';		
				$html.='<?php $page=System::load_sys_class(\'page\');if(isset('.$page.')){$pagenum='.$page.';}else{$pagenum=1;} $page->config($total,$num,$pagenum,"'.$pageurl.'"); ?>';	
				$html.='<?php '.'$'.$return.'='.'$this->DB()->GetPage("'.$sql.'",array("num"=>$num,"page"=>$pagenum,"type"=>'.$type.',"key"=>'.$key.',"cache"=>'.$cache.')); ?>';
				break;
			case 'getone':
				if(empty($sql)){return stripslashes($htmls);}
				$html.='<?php $mysql_model=System::load_sys_class(\'model\'); ?>';
				$html.='<?php '.'$'.$return.'='.'$this->DB()->GetOne("'.$sql.'",array("cache"=>'.$cache.')); ?>';
			break;						
		}
		return $html;
	}
    
    private static function end_html_tag($op, $htmls) {
        $op = trim($op);
        $oparr = ['getpage', 'getlist', 'getcount', 'getone', 'htmlcache', 'block'];
        
        if (!in_array($op, $oparr)) {
            return $htmls;
        }
        
        return '<?php if (defined(\'G_IN_ADMIN\')) { echo \'<div style="padding:8px;background-color:#F93; color:#fff;border:1px solid #f60;text-align:center"><b>This Tag</b></div>\'; } ?>';
    }
    
    /**
     * 转义 // 为 /
     * @param $var    转义的字符
     * @return 转义后的字符
     */
    public function addquote($var) {
        return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
    }
    
    /**
     * 转换数据为HTML代码
     * @param array $data 数组
     */
    private static function arr_to_html($data) {
        if (!is_array($data)) {
            return '';
        }
        
        $str = 'array(';
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $str .= "'$key' => " . self::arr_to_html($val) . ",";
            } else {
                if (strpos($val, '$') === 0) {
                    $str .= "'$key' => $val,";
                } else {
                    if ($key == 'sql') {
                        $str .= "'$key' => \"" . addslashes($val) . "\",";
                    } else {
                        $str .= "'$key' => '" . addslashes($val) . "',";
                    }
                }
            }
        }
        return $str . ')';
    }
}