<?php
/**
 * Single-file Installation Wizard (Standalone, independent of project code)
 * Features:
 * 1) Environment check: PHP version, extensions, disabled functions
 * 2) Database connection: Configure database, account, password and test connection
 * 3) Data initialization: Create sample tables and insert initial data
 * 4) Data retrieval: Get library data from remote server via API key and write to database
 *
 * Usage:
 * - Place this file in a web-accessible directory
 * - Access via browser and follow steps to complete installation
 *
 * Note:
 * - Data initialization will create tables in your specified database (skip or update if exists)
 */

declare(strict_types=1);
$system_path = 'system';
$statics_path = 'statics';
define('G_APP_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
include_once "./system/config/global.php";
include_once "./system/modules/".G_ADMIN_DIR."/certification.action.php";
$certificate = new certification();
$a = $certificate->certificationinfo();

// Increase execution time and memory limits
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

session_start();
header('Content-Type: text/html; charset=utf-8');

// ---------------------------
// Language Configuration
// ---------------------------
$LANGUAGES = [
    'zh' => '中文',
    'en' => 'English'
];

// ---------------------------
// Country/Language Map (align with import.action.php)
// ---------------------------
$COUNTRY_LANGUAGE_MAP = [
    "CN" => ["name" => "中国", "lan" => "Chinese"],
    "US" => ["name" => "美国", "lan" => "English"],
    "JP" => ["name" => "日本", "lan" => "Japanese"],
    "KR" => ["name" => "韩国", "lan" => "Korean"],
];

// Get current language from session or default to Chinese
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'zh';
}

// Handle language switch
if (isset($_GET['lang']) && in_array($_GET['lang'], ['zh', 'en'])) {
    $_SESSION['language'] = $_GET['lang'];
    // Redirect to remove lang parameter from URL
    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
    $queryParams = $_GET;
    unset($queryParams['lang']);
    if (!empty($queryParams)) {
        $redirectUrl .= '?' . http_build_query($queryParams);
    }
    header('Location: ' . $redirectUrl);
    exit;
}

$lang = $_SESSION['language'];

// ---------------------------
// Language Strings
// ---------------------------
$LANG = [
    'zh' => [
        // General
        'title' => '安装向导',
        'step_1' => '环境检测',
        'step_2' => '数据库连接',
        'step_3' => '初始化数据',
        'step_4' => '获取数据',
        'next' => '下一步',
        'back' => '返回',
        'reset' => '重置安装记录',
        'save' => '保存',
        'test' => '测试',
        'complete' => '完成',
        'warning' => '警告',
        'error' => '错误',
        'info' => '信息',
        
        // Step 1: Environment Check
        'env_check' => '环境检测',
        'env_status' => '环境状态',
        'env_ok' => '环境符合要求',
        'env_not_ok' => '环境不满足要求',
        'env_fix_prompt' => '如有不通过项，请先在服务器上修复后再继续。',
        'item' => '项目',
        'result' => '检测结果',
        'php_version' => 'PHP 版本',
        'required_version' => '要求 ≥',
        'required_extensions' => '必需扩展',
        'loaded' => '已加载',
        'not_loaded' => '未加载',
        'disabled_functions' => '需删除的禁用函数',
        'unavailable' => '不可用/已禁用',
        'available' => '可用',
        'suggestions' => '建议',
        'recheck' => '重新检测',
        
        // Step 2: Database Configuration
        'db_config' => '数据库和Redis配置',
        'db_config_desc' => '填写数据库和Redis连接信息',
        'db_config_tip' => '建议使用新建的空数据库，并确保账号具备建表权限。',
        'db_config_title' => '数据库配置',
        'db_host' => '数据库主机',
        'db_host_placeholder' => '例如 127.0.0.1 或 localhost',
        'db_port' => '端口',
        'db_port_placeholder' => '3306',
        'db_name' => '数据库名称',
        'db_name_placeholder' => '例如 taolong_shop',
        'db_charset' => '字符集',
        'db_user' => '账号',
        'db_user_placeholder' => '例如 root 或 dbuser',
        'db_password' => '密码',
        'db_password_placeholder' => '数据库密码',
        'redis_config_title' => 'Redis配置',
        'redis_host' => 'Redis主机',
        'redis_host_placeholder' => '例如 127.0.0.1',
        'redis_port' => 'Redis端口',
        'redis_port_placeholder' => '6379',
        'redis_password' => 'Redis密码',
        'redis_password_placeholder' => '留空表示无密码',
        'saved_config' => '已保存的配置',
        'saved_config_desc' => '已保存到文件：system/config/database.inc.php',
        'test_connection' => '测试数据库连接',
        'save_and_next' => '保存并下一步',
        'connection_success' => '数据库连接成功，配置已保存。',
        'connection_failed' => '数据库连接失败：',
        
        // Step 3: Data Initialization
        'init_data' => '初始化数据',
        'init_desc' => '请点击下方按钮开始初始化数据。',
        'init_completed' => '初始化已完成！可以继续下一步获取数据。',
        'init_not_configured' => '未配置数据库',
        'init_configured' => '已检测到数据库配置',
        'init_waiting' => '等待开始...',
        'init_start' => '开始初始化数据',
        'init_retry' => '重试',
        'init_progress' => '初始化进度',
        'init_preparing' => '准备开始...',
        'init_processing' => '处理中...',
        'init_complete_message' => '初始化已完成！',
        'init_error' => '初始化失败：',
        'init_sql_files' => '将执行的初始化内容',
        'init_sql_files_desc' => '从 <code>database</code> 文件夹导入SQL文件，创建数据库表结构并初始化数据。',
        'init_files_list' => '将要导入的SQL文件（按顺序）：',
        'init_no_files' => '警告：未找到SQL文件，请确保 <code>database</code> 文件夹中存在 .sql 文件。',
        'init_go_to_db' => '去配置数据库',
        
        // Step 4: Data Retrieval
        'get_data' => '获取数据',
        'get_data_desc' => '输入API Key，先获取可用的国家/语言代码列表，然后选择要获取的数据。',
        'get_data_instruction' => '说明',
        'get_data_types' => '可以分别获取以下类型的数据：',
        'get_address' => '全球地址库',
        'get_product' => '全球商品库',
        'get_bank' => '全球银行库 - 将自动保存到 <code>go_bank</code> 表',
        'get_social' => '社交用户库',
        'api_key_config' => 'API Key 配置',
        'api_key' => 'API Key',
        'api_key_placeholder' => '请输入API密钥',
        'get_countries' => '🌍 获取可用的国家/语言代码列表',
        'available_countries' => '可用的国家/语言代码',
        'data_retrieval' => '数据获取',
        'data_retrieval_desc' => '选择要获取的数据类型和国家/语言代码',
        'country_code' => '选择国家/语言代码',
        'get_data_button' => '获取数据',
        'use_default' => '获取数据（使用默认国家代码）',
        'fetch_results' => '获取结果',
        'type' => '类型',
        'name' => '名称',
        'country' => '国家/语言代码',
        'status' => '状态',
        'count' => '获取条数',
        'saved' => '保存条数',
        'success' => '成功',
        'failed' => '失败',
        'no_data' => '暂无数据',
        'records' => '条记录',
        'total_records' => '本次共获取 <strong>%s</strong> 条数据。可以继续获取其他类型的数据。',
        'bank_saved' => '✅ 银行数据已成功保存到 <code>go_bank</code> 表，共保存 <strong>%s</strong> 条记录。',
        'social_saved' => '✅ 社交用户数据已成功保存到 <code>go_member</code> 表，共保存 <strong>%s</strong> 条记录。',
        'address_saved' => '✅ 地址数据已成功保存到 <code>go_area</code> 和 <code>go_area_details</code> 表，共保存 <strong>%s</strong> 条记录（省市区县）。',
        'api_key_required' => '请先输入API Key',
        'country_list_prompt' => '💡 建议先点击上方的"获取可用的国家/语言代码列表"按钮，查看每种数据类型支持哪些国家/语言代码，然后再选择获取。',
        
        // Alerts and Messages
        'config_reset' => '已清空安装记录（仅本浏览器会话）。',
        'countries_fetched' => '成功获取可用的国家/语言代码列表。',
        'countries_fetch_failed' => '获取国家列表失败：',
        'data_fetched' => '成功获取数据，共 %s 条。',
        'data_fetched_with_saved' => '成功获取数据，共 %s 条。已保存银行数据 %s 条到数据库。',
        'data_fetch_empty' => '数据获取为空或失败。',
        'data_fetch_failed' => '获取数据失败：',
    ],
    'en' => [
        // General
        'title' => 'Installation Wizard',
        'step_1' => 'Environment Check',
        'step_2' => 'Database Connection',
        'step_3' => 'Initialize Data',
        'step_4' => 'Get Data',
        'next' => 'Next',
        'back' => 'Back',
        'reset' => 'Reset Installation',
        'save' => 'Save',
        'test' => 'Test',
        'complete' => 'Complete',
        'warning' => 'Warning',
        'success' => 'Success',
        'error' => 'Error',
        'info' => 'Info',
        
        // Step 1: Environment Check
        'env_check' => 'Environment Check',
        'env_status' => 'Environment Status',
        'env_ok' => 'Environment meets requirements',
        'env_not_ok' => 'Environment does not meet requirements',
        'env_fix_prompt' => 'If any items fail, please fix them on the server before continuing.',
        'item' => 'Item',
        'result' => 'Result',
        'php_version' => 'PHP Version',
        'required_version' => 'Required ≥',
        'required_extensions' => 'Required Extensions',
        'loaded' => 'Loaded',
        'not_loaded' => 'Not Loaded',
        'disabled_functions' => 'Functions to Remove from Disabled List',
        'unavailable' => 'Unavailable/Disabled',
        'available' => 'Available',
        'suggestions' => 'Suggestions',
        'recheck' => 'Recheck',
        
        // Step 2: Database Configuration
        'db_config' => 'Database and Redis Configuration',
        'db_config_desc' => 'Fill in database and Redis connection information',
        'db_config_tip' => 'It is recommended to use a new empty database and ensure the account has table creation privileges.',
        'db_config_title' => 'Database Configuration',
        'db_host' => 'Database Host',
        'db_host_placeholder' => 'e.g., 127.0.0.1 or localhost',
        'db_port' => 'Port',
        'db_port_placeholder' => '3306',
        'db_name' => 'Database Name',
        'db_name_placeholder' => 'e.g., taolong_shop',
        'db_charset' => 'Charset',
        'db_user' => 'Username',
        'db_user_placeholder' => 'e.g., root or dbuser',
        'db_password' => 'Password',
        'db_password_placeholder' => 'Database password',
        'redis_config_title' => 'Redis Configuration',
        'redis_host' => 'Redis Host',
        'redis_host_placeholder' => 'e.g., 127.0.0.1',
        'redis_port' => 'Redis Port',
        'redis_port_placeholder' => '6379',
        'redis_password' => 'Redis Password',
        'redis_password_placeholder' => 'Leave empty for no password',
        'saved_config' => 'Saved Configuration',
        'saved_config_desc' => 'Saved to file: system/config/database.inc.php',
        'test_connection' => 'Test Database Connection',
        'save_and_next' => 'Save and Next',
        'connection_success' => 'Database connection successful, configuration saved.',
        'connection_failed' => 'Database connection failed: ',
        
        // Step 3: Data Initialization
        'init_data' => 'Data Initialization',
        'init_desc' => 'Please click the button below to start data initialization.',
        'init_completed' => 'Initialization completed! You can proceed to the next step to get data.',
        'init_not_configured' => 'Database not configured',
        'init_configured' => 'Database configuration detected',
        'init_waiting' => 'Waiting to start...',
        'init_start' => 'Start Data Initialization',
        'init_retry' => 'Retry',
        'init_progress' => 'Initialization Progress',
        'init_preparing' => 'Preparing to start...',
        'init_processing' => 'Processing...',
        'init_complete_message' => 'Initialization completed!',
        'init_error' => 'Initialization failed: ',
        'init_sql_files' => 'Initialization Content to Execute',
        'init_sql_files_desc' => 'Import SQL files from the <code>database</code> folder to create database table structures and initialize data.',
        'init_files_list' => 'SQL files to be imported (in order):',
        'init_no_files' => 'Warning: No SQL files found. Please ensure .sql files exist in the <code>database</code> folder.',
        'init_go_to_db' => 'Go to Database Configuration',
        
        // Step 4: Data Retrieval
        'get_data' => 'Get Data',
        'get_data_desc' => 'Enter API Key, get the list of available country/language codes, then select the data to retrieve.',
        'get_data_instruction' => 'Instructions',
        'get_data_types' => 'You can retrieve the following types of data:',
        'get_address' => 'Global Address Library',
        'get_product' => 'Global Product Library',
        'get_bank' => 'Global Bank Library - Will be automatically saved to <code>go_bank</code> table',
        'get_social' => 'Social User Library',
        'api_key_config' => 'API Key Configuration',
        'api_key' => 'API Key',
        'api_key_placeholder' => 'Enter API key',
        'get_countries' => '🌍 Get Available Country/Language Codes',
        'available_countries' => 'Available Country/Language Codes',
        'data_retrieval' => 'Data Retrieval',
        'data_retrieval_desc' => 'Select the data type and country/language code to retrieve',
        'country_code' => 'Select Country/Language Code',
        'get_data_button' => 'Get Data',
        'use_default' => 'Get Data (Use Default Country Code)',
        'fetch_results' => 'Retrieval Results',
        'type' => 'Type',
        'name' => 'Name',
        'country' => 'Country/Language Code',
        'status' => 'Status',
        'count' => 'Records Fetched',
        'saved' => 'Records Saved',
        'failed' => 'Failed',
        'no_data' => 'No data',
        'records' => 'records',
        'total_records' => 'Total <strong>%s</strong> records fetched. You can continue to retrieve other data types.',
        'bank_saved' => '✅ Bank data successfully saved to <code>go_bank</code> table, total <strong>%s</strong> records saved.',
        'social_saved' => '✅ Social user data successfully saved to <code>go_member</code> table, total <strong>%s</strong> records saved.',
        'address_saved' => '✅ Address data successfully saved to <code>go_area</code> and <code>go_area_details</code> tables, total <strong>%s</strong> records saved (provinces/cities/districts).',
        'api_key_required' => 'Please enter API Key first',
        'country_list_prompt' => '💡 Tip: It is recommended to click the "Get Available Country/Language Codes" button above first to see which country/language codes are supported for each data type, then select and retrieve.',
        
        // Alerts and Messages
        'config_reset' => 'Installation records cleared (this browser session only).',
        'countries_fetched' => 'Successfully retrieved list of available country/language codes.',
        'countries_fetch_failed' => 'Failed to retrieve country list: ',
        'data_fetched' => 'Successfully retrieved data, total %s records.',
        'data_fetched_with_saved' => 'Successfully retrieved data, total %s records. Saved %s bank records to database.',
        'data_fetch_empty' => 'Data retrieval empty or failed.',
        'data_fetch_failed' => 'Data retrieval failed: ',
    ]
];

// Helper function for translation
function __(string $key, array $params = []): string {
    global $LANG, $lang;
    $text = $LANG[$lang][$key] ?? $LANG['zh'][$key] ?? $key;
    if (!empty($params)) {
        $text = vsprintf($text, $params);
    }
    return $text;
}

// ---------------------------
// Configuration: Environment Requirements (adjustable)
// ---------------------------
$REQUIRED_PHP = '8.2.0';
$REQUIRED_EXTENSIONS = [
    'pdo','pdo_mysql','json','mbstring','openssl','curl','redis',
    'gd','fileinfo','zip','gmp',
];
$CHECK_FUNCTIONS = [
    'exec','shell_exec','passthru','system','proc_open','popen','putenv',
];

// ---------------------------
// Utility Functions
// ---------------------------
function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

function disabled_functions(): array {
    $list = (string)ini_get('disable_functions');
    if ($list === '') return [];
    $parts = array_map('trim', explode(',', $list));
    $parts = array_values(array_filter($parts, fn($x) => $x !== ''));
    return $parts;
}

function is_post(): bool { return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'; }

function current_step(): int {
    $s = $_GET['step'] ?? ($_POST['step'] ?? 1);
    $s = (int)$s;
    if ($s < 1) $s = 1;
    if ($s > 4) $s = 4;
    return $s;
}

function flash_set(string $type, string $msg): void {
    $_SESSION['_flash'] = ['type' => $type, 'msg' => $msg];
}
function flash_get(): ?array {
    if (!isset($_SESSION['_flash'])) return null;
    $v = $_SESSION['_flash'];
    unset($_SESSION['_flash']);
    return is_array($v) ? $v : null;
}

function env_check(string $requiredPhp, array $requiredExt, array $checkFunctions): array {
    $result = [
        'ok' => true,
        'php' => [
            'current' => PHP_VERSION,
            'required' => $requiredPhp,
            'ok' => version_compare(PHP_VERSION, $requiredPhp, '>=')
        ],
        'extensions' => [],
        'disabled_functions' => [],
        'notes' => [],
    ];

    if (!$result['php']['ok']) $result['ok'] = false;

    foreach ($requiredExt as $ext) {
        $loaded = extension_loaded($ext);
        $result['extensions'][] = ['name' => $ext, 'loaded' => $loaded];
        if (!$loaded) $result['ok'] = false;
    }

    $disabled = disabled_functions();
    foreach ($checkFunctions as $fn) {
        $isDisabled = in_array($fn, $disabled, true);
        $exists = function_exists($fn);
        $unavailable = $isDisabled || !$exists;
        $result['disabled_functions'][] = [
            'name' => $fn,
            'disabled' => $isDisabled,
            'exists' => $exists,
            'unavailable' => $unavailable,
        ];
    }

    if (ini_get('display_errors')) $result['notes'][] = __('suggestions_display_errors');
    if ((int)ini_get('memory_limit') !== -1) $result['notes'][] = __('suggestions_memory_limit');

    return $result;
}

function pdo_connect(array $cfg): PDO {
    $dbCfg = $cfg['default'] ?? $cfg;
    $host = $dbCfg['hostname'] ?? '127.0.0.1';
    $port = (int)($dbCfg['port'] ?? 3306);
    $name = $dbCfg['database'] ?? '';
    $user = $dbCfg['username'] ?? '';
    $pass = $dbCfg['password'] ?? '';
    $charset = $dbCfg['charset'] ?? 'utf8mb4';

    if ($name === '' || $user === '') {
        throw new RuntimeException('Database name and username cannot be empty.');
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function load_sql_files(string $databaseDir): array {
    if (!is_dir($databaseDir)) {
        throw new RuntimeException("Database folder does not exist: {$databaseDir}");
    }
    
    $files = glob($databaseDir . '/*.sql');
    if (empty($files)) {
        throw new RuntimeException("No SQL files found in database folder: {$databaseDir}");
    }
    
    usort($files, function($a, $b) {
        $aName = basename($a);
        $bName = basename($b);
        if ($aName === 'init.sql') return -1;
        if ($bName === 'init.sql') return 1;
        return strcmp($aName, $bName);
    });
    
    $allStatements = [];
    foreach ($files as $file) {
        $content = file_get_contents($file);
        if ($content === false) {
            throw new RuntimeException("Unable to read SQL file: {$file}");
        }
        
        $content = preg_replace('/\/\*.*?\*\//s', '', $content);
        $content = preg_replace('/^\s*--.*$/m', '', $content);
        
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        
        $len = strlen($content);
        for ($i = 0; $i < $len; $i++) {
            $char = $content[$i];
            $nextChar = ($i + 1 < $len) ? $content[$i + 1] : '';
            
            if ($inString && $char === '\\') {
                $current .= $char;
                if ($i + 1 < $len) {
                    $current .= $nextChar;
                    $i++;
                }
                continue;
            }
            
            if (!$inString && ($char === '"' || $char === "'" || $char === '`')) {
                $inString = true;
                $stringChar = $char;
                $current .= $char;
            } elseif ($inString && $char === $stringChar) {
                if ($stringChar === '"' && $nextChar === '"') {
                    $current .= $char . $nextChar;
                    $i++;
                    continue;
                }
                $inString = false;
                $stringChar = '';
                $current .= $char;
            } elseif (!$inString && $char === ';') {
                $stmt = trim($current);
                if (!empty($stmt) && !preg_match('/^(SET|USE)\s+/i', $stmt)) {
                    $statements[] = $stmt;
                }
                $current = '';
            } else {
                $current .= $char;
            }
        }
        
        $stmt = trim($current);
        if (!empty($stmt) && !preg_match('/^(SET|USE)\s+/i', $stmt)) {
            $statements[] = $stmt;
        }
        
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if (!empty($stmt)) {
                if (substr($stmt, -1) !== ';') {
                    $stmt .= ';';
                }
                $allStatements[] = [
                    'file' => basename($file),
                    'sql' => $stmt
                ];
            }
        }
    }
    
    return $allStatements;
}

function seed_sql(string $adminUser, string $adminPass, string $tablePrefix = 'go_'): array {
    $adminUser = trim($adminUser);
    if ($adminUser === '') $adminUser = 'admin';
    if (trim($adminPass) === '') {
        throw new RuntimeException('Administrator password cannot be empty.');
    }
    $hash = password_hash($adminPass, PASSWORD_DEFAULT);
    if ($hash === false) {
        throw new RuntimeException('Password hash generation failed. Please check if PHP environment supports password_hash.');
    }
    $now = date('Y-m-d H:i:s');

    $adminTable = $tablePrefix . 'admin';
    return [
        [
            'sql' => "INSERT INTO `{$adminTable}` (`username`,`userpass`,`name`,`useremail`,`addtime`) VALUES (:u,:p,:n,:e,:t)
                      ON DUPLICATE KEY UPDATE `userpass`=VALUES(`userpass`)",
            'params' => [
                ':u' => $adminUser, 
                ':p' => md5($adminPass),
                ':n' => 'Administrator',
                ':e' => 'admin@example.com',
                ':t' => time()
            ]
        ],
    ];
}

function config_from_request(): array {
    $dbPort = (int)($_POST['port'] ?? 3306);
    if ($dbPort <= 0 || $dbPort > 65535) $dbPort = 3306;
    
    $redisPort = (int)($_POST['redis_port'] ?? 6379);
    if ($redisPort <= 0 || $redisPort > 65535) $redisPort = 6379;
    
    $cfg = [
        "default"=>[
            'hostname' => trim((string)($_POST['hostname'] ?? '127.0.0.1')),
            'port' => $dbPort,
            'database' => trim((string)($_POST['database'] ?? '')),
            'username' => trim((string)($_POST['username'] ?? '')),
            'password' => (string)($_POST['password'] ?? ''),
            'charset' => trim((string)($_POST['charset'] ?? 'utf8mb4')),
            'tablepre' => 'go_',
            'type' => 'mysql',
            'debug' => false,
            'pconnect' => 0,
            'autoconnect' => 0,
        ],
        'redis' =>  [
            'host'  =>  trim((string)($_POST['redis_host'] ?? '127.0.0.1')),
            'port'  =>  $redisPort,
            'password'  =>  (string)($_POST['redis_password'] ?? ''),
        ],
    ];
    if ($cfg['default']['charset'] === '') $cfg['default']['charset'] = 'utf8mb4';
    return $cfg;
}

function get_saved_cfg(): array {
    $configFile = __DIR__ . '/system/config/database.inc.php';
    if (file_exists($configFile)) {
        $cfg = include $configFile;
        return is_array($cfg) ? $cfg : [];
    }
    $cfg = $_SESSION['install_cfg'] ?? [];
    return is_array($cfg) ? $cfg : [];
}

function save_cfg(array $cfg): void {
    $configDir = __DIR__ . '/system/config';
    if (!is_dir($configDir)) {
        if (!mkdir($configDir, 0755, true)) {
            throw new RuntimeException('Unable to create config directory: ' . $configDir);
        }
    }
    
    $configFile = $configDir . '/database.inc.php';
    $content = "<?php\n";
    $content .= "// Database configuration file - Automatically generated by installation wizard\n";
    $content .= "// Generation time: " . date('Y-m-d H:i:s') . "\n\n";
    $content .= "return " . var_export($cfg, true) . ";\n";
    
    if (file_put_contents($configFile, $content) === false) {
        throw new RuntimeException('Unable to write configuration file: ' . $configFile);
    }
    
    $_SESSION['install_cfg'] = $cfg;
}

function fetch_available_countries(string $apiKey, string $baseUrl = ''): array {
    if (empty($baseUrl)) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $scheme . '://' . $host;
    }
    
    $url = rtrim($baseUrl, '/') . '/api/libraries/countries';
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'x-api-key:' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        throw new RuntimeException('CURL Error: ' . $error);
    }
    
    if ($httpCode !== 200) {
        throw new RuntimeException("HTTP Error: {$httpCode}. Response: " . substr($response, 0, 200));
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException('JSON parse failed: ' . json_last_error_msg() . '. Response: ' . substr($response, 0, 200));
    }
    
    return $data;
}

function fetch_library_data_full(string $apiKey, string $type, string $countryCode, string $baseUrl = '', int $page = 1, int $pageSize = 100): array {
    if (empty($baseUrl)) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $scheme . '://' . $host;
    }
    
    $url = rtrim($baseUrl, '/') . '/api/libraries';
    $params = [
        'type' => $type,
        'country_code' => $countryCode,
        'page' => $page,
        'page_size' => $pageSize,
    ];
    $url .= '?' . http_build_query($params);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'x-api-key:' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        throw new RuntimeException('CURL Error: ' . $error);
    }
    
    if ($httpCode !== 200) {
        throw new RuntimeException("HTTP Error: {$httpCode}. Response: " . substr($response, 0, 200));
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException('JSON parse failed: ' . json_last_error_msg() . '. Response: ' . substr($response, 0, 200));
    }
    
    return $data;
}

function fetch_library_data_all_pages(string $apiKey, string $type, string $countryCode, string $baseUrl = '', int $pageSize = 100): array {
    $allItems = [];
    $page = 1;
    $hasMore = true;
    $firstResponse = null;
    $totalPages = null;
    
    while ($hasMore) {
        try {
            $response = fetch_library_data_full($apiKey, $type, $countryCode, $baseUrl, $page, $pageSize);
        } catch (Throwable $e) {
            throw new RuntimeException("Failed to fetch page {$page}: " . $e->getMessage());
        }
        
        if ($page === 1) {
            $firstResponse = $response;
        }
        
        $currentPageItems = [];
        if ($type === 'bank' && isset($response['data']['banks']) && is_array($response['data']['banks'])) {
            $currentPageItems = $response['data']['banks'];
        } elseif ($type === 'social' && isset($response['data']['users']) && is_array($response['data']['users'])) {
            $currentPageItems = $response['data']['users'];
        } elseif ($type === 'address' && isset($response['data']['provinces']) && is_array($response['data']['provinces'])) {
            $currentPageItems = $response['data']['provinces'];
        } elseif (isset($response['data']) && is_array($response['data'])) {
            if (isset($response['data'][0]) || empty($response['data'])) {
                $currentPageItems = $response['data'];
            } else {
                $currentPageItems = [$response['data']];
            }
        }
        
        $allItems = array_merge($allItems, $currentPageItems);
        
        $hasMore = false;
        
        if (isset($response['pagination'])) {
            $pagination = $response['pagination'];
            if (isset($pagination['has_more'])) {
                $hasMore = (bool)$pagination['has_more'];
            } elseif (isset($pagination['total']) && isset($pagination['page']) && isset($pagination['page_size'])) {
                if ($totalPages === null) {
                    $totalPages = ceil($pagination['total'] / $pagination['page_size']);
                }
                $hasMore = $pagination['page'] < $totalPages;
            }
        }
        
        if (!isset($response['pagination'])) {
            $hasMore = count($currentPageItems) >= $pageSize;
        }
        
        if (count($currentPageItems) === 0) {
            $hasMore = false;
        }
        
        $page++;
        
        if ($page > 1000) {
            error_log("Warning: Maximum page limit reached (1000 pages), stopping further data retrieval. Type: {$type}, Country: {$countryCode}");
            break;
        }
        
        if ($hasMore) {
            usleep(100000);
        }
    }
    
    if ($firstResponse) {
        if ($type === 'bank') {
            if (isset($firstResponse['data']['banks'])) {
                $firstResponse['data']['banks'] = $allItems;
                if (isset($firstResponse['data']['total'])) {
                    $firstResponse['data']['total'] = count($allItems);
                }
            } else {
                $firstResponse['data'] = [
                    'banks' => $allItems,
                    'total' => count($allItems),
                ];
            }
        } elseif ($type === 'social') {
            if (isset($firstResponse['data']['users'])) {
                $firstResponse['data']['users'] = $allItems;
                if (isset($firstResponse['data']['total'])) {
                    $firstResponse['data']['total'] = count($allItems);
                }
            } else {
                $firstResponse['data'] = [
                    'users' => $allItems,
                    'total' => count($allItems),
                ];
            }
        } elseif ($type === 'address') {
            if (isset($firstResponse['data']['provinces'])) {
                $firstResponse['data']['provinces'] = $allItems;
            } else {
                $firstResponse['data'] = [
                    'provinces' => $allItems,
                ];
            }
        } else {
            $firstResponse['data'] = $allItems;
        }
        return $firstResponse;
    }
    
    if ($type === 'bank') {
        return [
            'data' => [
                'banks' => $allItems,
                'total' => count($allItems),
            ]
        ];
    } elseif ($type === 'social') {
        return [
            'data' => [
                'users' => $allItems,
                'total' => count($allItems),
            ]
        ];
    } elseif ($type === 'address') {
        return [
            'data' => [
                'provinces' => $allItems,
            ]
        ];
    }
    
    return ['data' => $allItems];
}

function fetch_library_data(string $apiKey, string $type, string $countryCode, string $baseUrl = '', bool $fetchAllPages = true): array {
    if ($fetchAllPages) {
        $data = fetch_library_data_all_pages($apiKey, $type, $countryCode, $baseUrl);
    } else {
        $data = fetch_library_data_full($apiKey, $type, $countryCode, $baseUrl);
    }
    
    if ($type === 'bank' && isset($data['data']['banks']) && is_array($data['data']['banks'])) {
        return $data['data']['banks'];
    }
    
    if ($type === 'social' && isset($data['data']['users']) && is_array($data['data']['users'])) {
        return $data['data']['users'];
    }
    
    if ($type === 'address' && isset($data['data']['provinces']) && is_array($data['data']['provinces'])) {
        return $data['data']['provinces'];
    }

    if ($type === 'product' && isset($data['data']['products']) && is_array($data['data']['products'])) {
        return $data['data']['products'];
    }
    
    if (!isset($data['data']) || !is_array($data['data'])) {
        throw new RuntimeException('API response format error: Missing data field or data is not an array.');
    }
    
    return $data['data'];
}

function save_library_data(PDO $pdo, string $type, string $countryCode, array $items): int {
    $count = 0;
    $now = date('Y-m-d H:i:s');
    
    $deleteStmt = $pdo->prepare("DELETE FROM `libraries` WHERE `type` = :type AND `country_code` = :country_code");
    $deleteStmt->execute([':type' => $type, ':country_code' => $countryCode]);
    
    if (empty($items)) {
        return 0;
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO `libraries` (`type`, `country_code`, `col1`, `col2`, `full_data`, `created_at`, `updated_at`)
        VALUES (:type, :country_code, :col1, :col2, :full_data, :created_at, :updated_at)
    ");
    
    foreach ($items as $item) {
        $col1 = '';
        $col2 = '';
        
        if ($type === 'bank') {
            $col1 = (string)($item['logo_url'] ?? '');
            $col2 = (string)($item['name'] ?? '');
        } elseif ($type === 'social') {
            $col1 = (string)($item['avatar'] ?? $item['logo_url'] ?? '');
            $col2 = (string)($item['username'] ?? $item['name'] ?? '');
        } elseif ($type === 'address') {
            $keys = array_keys($item);
            $col1 = isset($keys[0]) ? (string)($item[$keys[0]] ?? '') : '';
            $col2 = isset($keys[1]) ? (string)($item[$keys[1]] ?? '') : '';
        } elseif ($type === 'product') {
            $keys = array_keys($item);
            $col1 = isset($keys[0]) ? (string)($item[$keys[0]] ?? '') : '';
            $col2 = isset($keys[1]) ? (string)($item[$keys[1]] ?? '') : '';
        }
        
        $fullDataJson = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $stmt->execute([
            ':type' => $type,
            ':country_code' => $countryCode,
            ':col1' => $col1,
            ':col2' => $col2,
            ':full_data' => $fullDataJson,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
        
        $count++;
    }
    
    return $count;
}

function save_bank_data(PDO $pdo, string $countryCode, array $banks): int {
    if (empty($banks)) {
        return 0;
    }
    
    $now = time();
    $tablePrefix = 'go_';
    $tableName = $tablePrefix . 'bank';
    $language = $GLOBALS['COUNTRY_LANGUAGE_MAP'][$countryCode]['lan'] ?? $countryCode;
    
    $deleteStmt = $pdo->prepare("DELETE FROM `{$tableName}` WHERE `language` = :language");
    $deleteStmt->execute([':language' => $language]);
    
    $stmt = $pdo->prepare("
        INSERT INTO `{$tableName}` (`name`, `logo`, `bg_color`, `create_time`, `update_time`, `status`, `language`, `sort`)
        VALUES (:name, :logo, :bg_color, :create_time, :update_time, :status, :language, :sort)
    ");
    
    $count = 0;
    foreach ($banks as $bank) {
        $name = trim((string)($bank['name'] ?? ''));
        if (empty($name)) {
            continue;
        }
        
        $logo = (string)($bank['logo_url'] ?? '');
        if ($logo === 'null' || $logo === '') {
            $logo = null;
        }
        
        $stmt->execute([
            ':name' => $name,
            ':logo' => $logo,
            ':bg_color' => null,
            ':create_time' => $now,
            ':update_time' => $now,
            ':status' => 2,
            ':language' => $language,
            ':sort' => 1,
        ]);
        
        $count++;
    }
    
    return $count;
}

function save_social_data(PDO $pdo, string $countryCode, array $users): int {
    if (empty($users)) {
        return 0;
    }
    
    $now = time();
    $tablePrefix = 'go_';
    $tableName = $tablePrefix . 'member';
    $language = $GLOBALS['COUNTRY_LANGUAGE_MAP'][$countryCode]['lan'] ?? $countryCode;
    
    $checkStmt = $pdo->prepare("SELECT `uid` FROM `{$tableName}` WHERE `username` = :username LIMIT 1");
    
    $insertStmt = $pdo->prepare("
        INSERT INTO `{$tableName}` (`username`, `img`, `headimg`, `auto_user`, `time`, `language`, `groupid`, `emailcode`, `mobilecode`, `othercode`, `passcode`)
        VALUES (:username, :img, :headimg, :auto_user, :time, :language, :groupid, :emailcode, :mobilecode, :othercode, :passcode)
    ");
    
    $updateStmt = $pdo->prepare("
        UPDATE `{$tableName}` 
        SET `img` = :img, `headimg` = :headimg, `auto_user` = :auto_user, `language` = :language
        WHERE `username` = :username
    ");
    
    $count = 0;
    foreach ($users as $user) {
        $username = trim((string)($user['username'] ?? ''));
        if (empty($username)) {
            continue;
        }
        
        $avatar = (string)($user['avatar'] ?? $user['avatar_url'] ?? '');
        if ($avatar === 'null' || $avatar === '') {
            $avatar = 'photo/member.jpg';
        }
        
        $checkStmt->execute([':username' => $username]);
        $existing = $checkStmt->fetch();
        
        if ($existing) {
            $updateStmt->execute([
                ':username' => $username,
                ':img' => $avatar,
                ':headimg' => $avatar,
                ':auto_user' => 1,
                ':language' => $language,
            ]);
        } else {
            $insertStmt->execute([
                ':username' => $username,
                ':img' => $avatar,
                ':headimg' => $avatar,
                ':auto_user' => 1,
                ':time' => $now,
                ':language' => $language,
                ':groupid' => 1,
                ':emailcode' => '-1',
                ':mobilecode' => '-1',
                ':othercode' => 0,
                ':passcode' => '-1',
            ]);
        }
        
        $count++;
    }
    
    return $count;
}

function save_product_data(PDO $pdo, string $countryCode, array $products, bool $clearExisting = true): int {
    if (empty($products)) {
        return 0;
    }

    $tablePrefix = 'go_';
    $tableName = $tablePrefix . 'shoplist_jifen';
    $language = $GLOBALS['COUNTRY_LANGUAGE_MAP'][$countryCode]['lan'] ?? $countryCode;
    $time = time();

    $defaultCategoryId = get_default_goods_category_id($pdo, $language);

    $existingStmt = $pdo->prepare("SELECT `id` FROM `{$tableName}` WHERE `title` = :title AND `language` = :language LIMIT 1");
    $insertStmt = $pdo->prepare("
        INSERT INTO `{$tableName}`
        (`sid`, `cateid`, `title`, `money`, `disc_price`, `s_score`, `content`, `sales`, `thumb`, `picarr`, `online`, `pos`, `renqi`, `order`, `quality`, `newhot`, `tejiachaozhi`, `codes_table`, `time`, `updatime`, `fictitious`, `language`, `delete_time`, `status`)
        VALUES
        (0, :cateid, :title, :money, :disc_price, :s_score, :content, :sales, :thumb, :picarr, :online, :pos, :renqi, :order, :quality, :newhot, :tejiachaozhi, :codes_table, :time, :updatime, :fictitious, :language, NULL, :status)
    ");
    $updateStmt = $pdo->prepare("
        UPDATE `{$tableName}`
        SET `cateid` = :cateid,
            `thumb` = :thumb,
            `picarr` = :picarr,
            `money` = :money,
            `disc_price` = :disc_price,
            `s_score` = :s_score,
            `content` = :content,
            `sales` = :sales,
            `online` = :online,
            `pos` = :pos,
            `renqi` = :renqi,
            `order` = :order,
            `quality` = :quality,
            `newhot` = :newhot,
            `tejiachaozhi` = :tejiachaozhi,
            `codes_table` = :codes_table,
            `updatime` = :updatime,
            `fictitious` = :fictitious,
            `status` = :status
        WHERE `id` = :id
    ");

    if ($clearExisting) {
        $deleteStmt = $pdo->prepare("DELETE FROM `{$tableName}` WHERE `language` = :language");
        $deleteStmt->execute([':language' => $language]);
    }

    $count = 0;
    foreach ($products as $product) {
        $title = trim((string)($product['name'] ?? ''));
        if ($title === '') {
            continue;
        }

        $thumb = trim((string)($product['thumbnail'] ?? ''));
        $pics = [];
        if ($thumb !== '') {
            $pics[] = $thumb;
        }
        $picarr = serialize($pics);
        $content = trim((string)($product['description'] ?? ''));
        $price = (float)($product['price'] ?? 0);
        if ($price <= 0) {
            $price = 0;
        }

        $existingStmt->execute([
            ':title' => $title,
            ':language' => $language,
        ]);
        $existing = $existingStmt->fetch();

        $payload = [
            ':cateid' => $defaultCategoryId,
            ':title' => $title,
            ':money' => $price,
            ':disc_price' => $price,
            ':s_score' => 0,
            ':content' => $content,
            ':sales' => 0,
            ':thumb' => $thumb,
            ':picarr' => $picarr,
            ':online' => 1,
            ':pos' => 0,
            ':renqi' => 0,
            ':order' => 1,
            ':quality' => 0,
            ':newhot' => 0,
            ':tejiachaozhi' => 0,
            ':codes_table' => null,
            ':time' => $time,
            ':updatime' => $time,
            ':fictitious' => 0,
            ':language' => $language,
            ':status' => 1,
        ];

        if ($existing) {
            $payload[':id'] = $existing['id'];
            $updateStmt->execute($payload);
        } else {
            $insertStmt->execute($payload);
            $newId = (int)$pdo->lastInsertId();
            if ($newId > 0) {
                $pdo->prepare("UPDATE `{$tableName}` SET `sid` = :sid WHERE `id` = :id")
                    ->execute([':sid' => $newId, ':id' => $newId]);
            }
        }

        $count++;
    }

    return $count;
}

function get_default_goods_category_id(PDO $pdo, string $language): int {
    $tablePrefix = 'go_';
    $categoryTable = $tablePrefix . 'category';

    $stmt = $pdo->prepare("SELECT `cateid` FROM `{$categoryTable}` WHERE `model` = 3 AND (`language` = :language OR `language` IS NULL OR `language` = '') ORDER BY `order` DESC, `cateid` ASC LIMIT 1");
    $stmt->execute([':language' => $language]);
    $row = $stmt->fetch();
    if ($row && isset($row['cateid'])) {
        return (int)$row['cateid'];
    }

    $fallbackStmt = $pdo->prepare("SELECT `cateid` FROM `{$categoryTable}` WHERE `model` = 3 ORDER BY `order` DESC, `cateid` ASC LIMIT 1");
    $fallbackStmt->execute();
    $fallback = $fallbackStmt->fetch();
    if ($fallback && isset($fallback['cateid'])) {
        return (int)$fallback['cateid'];
    }

    return 0;
}

function save_address_data(PDO $pdo, string $countryCode, array $provinces): int {
    if (empty($provinces)) {
        return 0;
    }
    
    $now = time();
    $tablePrefix = 'go_';
    $areaTable = $tablePrefix . 'area';
    $areaDetailsTable = $tablePrefix . 'area_details';
    $areaName = $GLOBALS['COUNTRY_LANGUAGE_MAP'][$countryCode]['name'] ?? $countryCode;
    $language = $GLOBALS['COUNTRY_LANGUAGE_MAP'][$countryCode]['lan'] ?? $countryCode;
    
    $checkAreaStmt = $pdo->prepare("SELECT `id` FROM `{$areaTable}` WHERE `language` = :language LIMIT 1");
    $checkAreaStmt->execute([':language' => $language]);
    $areaRecord = $checkAreaStmt->fetch();
    
    if ($areaRecord) {
        $areaId = (int)$areaRecord['id'];
    } else {
        $insertAreaStmt = $pdo->prepare("
            INSERT INTO `{$areaTable}` (`name`, `desc`, `create_time`, `update_time`, `status`, `language`)
            VALUES (:name, :desc, :create_time, :update_time, :status, :language)
        ");
        $insertAreaStmt->execute([
            ':name' => $areaName,
            ':desc' => $areaName,
            ':create_time' => $now,
            ':update_time' => $now,
            ':status' => 1,
            ':language' => $language,
        ]);
        $areaId = (int)$pdo->lastInsertId();
    }
    
    $deleteStmt = $pdo->prepare("DELETE FROM `{$areaDetailsTable}` WHERE `area_id` = :area_id");
    $deleteStmt->execute([':area_id' => $areaId]);
    
    $insertStmt = $pdo->prepare("
        INSERT INTO `{$areaDetailsTable}` (`area_id`, `pid`, `name`, `desc`, `status`, `create_time`, `update_time`, `sort`, `code`)
        VALUES (:area_id, :pid, :name, :desc, :status, :create_time, :update_time, :sort, :code)
    ");
    
    $totalCount = 0;
    $sort = 1;
    
    foreach ($provinces as $province) {
        $provinceName = trim((string)($province['name'] ?? ''));
        if (empty($provinceName)) {
            continue;
        }
        
        $provinceCode = trim((string)($province['code'] ?? ''));
        $provinceNameEn = trim((string)($province['name_en'] ?? ''));
        
        $insertStmt->execute([
            ':area_id' => $areaId,
            ':pid' => 0,
            ':name' => $provinceName,
            ':desc' => $provinceNameEn,
            ':status' => 1,
            ':create_time' => $now,
            ':update_time' => $now,
            ':sort' => $sort++,
            ':code' => $provinceCode ?: null,
        ]);
        
        $provinceId = (int)$pdo->lastInsertId();
        $totalCount++;
        
        $cities = $province['cities'] ?? [];
        if (is_array($cities)) {
            $citySort = 1;
            foreach ($cities as $city) {
                $cityName = trim((string)($city['name'] ?? ''));
                if (empty($cityName)) {
                    continue;
                }
                
                $cityCode = trim((string)($city['code'] ?? ''));
                $cityNameEn = trim((string)($city['name_en'] ?? ''));
                
                $insertStmt->execute([
                    ':area_id' => $areaId,
                    ':pid' => $provinceId,
                    ':name' => $cityName,
                    ':desc' => $cityNameEn,
                    ':status' => 1,
                    ':create_time' => $now,
                    ':update_time' => $now,
                    ':sort' => $citySort++,
                    ':code' => $cityCode ?: null,
                ]);
                
                $cityId = (int)$pdo->lastInsertId();
                $totalCount++;
                
                $districts = $city['districts'] ?? [];
                if (is_array($districts)) {
                    $districtSort = 1;
                    foreach ($districts as $district) {
                        $districtName = trim((string)($district['name'] ?? ''));
                        if (empty($districtName)) {
                            continue;
                        }
                        
                        $districtCode = trim((string)($district['code'] ?? ''));
                        $districtNameEn = trim((string)($district['name_en'] ?? ''));
                        
                        $insertStmt->execute([
                            ':area_id' => $areaId,
                            ':pid' => $cityId,
                            ':name' => $districtName,
                            ':desc' => $districtNameEn,
                            ':status' => 1,
                            ':create_time' => $now,
                            ':update_time' => $now,
                            ':sort' => $districtSort++,
                            ':code' => $districtCode ?: null,
                        ]);
                        
                        $totalCount++;
                    }
                }
            }
        }
    }
    
    return $totalCount;
}

function render_header(int $step): void {
    global $lang, $LANGUAGES;
    $title = __('title');
    echo '<!doctype html><html lang="' . ($lang === 'en' ? 'en' : 'zh-CN') . '"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>' . h($title) . '</title>';
    echo '<style>
        :root{--bg:#0b1220;--card:#111a2e;--muted:#93a4c7;--text:#e9eefc;--primary:#4f8cff;--good:#2ecc71;--bad:#ff5c7a;--warn:#ffcc00;}
        body{margin:0;background:linear-gradient(180deg,#070b14,#0b1220 40%,#070b14);color:var(--text);font:14px/1.6 -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;}
        .wrap{max-width:980px;margin:36px auto;padding:0 16px;}
        .top{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
        .brand h1{margin:0;font-size:20px;letter-spacing:.5px}
        .brand p{margin:6px 0 0;color:var(--muted)}
        .top-right{display:flex;gap:8px;align-items:center;}
        .lang-selector{display:flex;gap:4px;background:rgba(255,255,255,.06);border-radius:999px;padding:3px;border:1px solid rgba(255,255,255,.08);}
        .lang-btn{padding:4px 12px;border-radius:999px;border:none;background:transparent;color:var(--muted);cursor:pointer;font-size:13px;transition:all 0.2s;}
        .lang-btn.active{background:var(--primary);color:var(--text);}
        .stepper{display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:flex-end}
        .chip{padding:6px 10px;border-radius:999px;background:rgba(255,255,255,.06);color:var(--muted);border:1px solid rgba(255,255,255,.08)}
        .chip.on{background:rgba(79,140,255,.18);color:var(--text);border-color:rgba(79,140,255,.35)}
        .card{background:rgba(17,26,46,.85);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:18px;box-shadow:0 10px 30px rgba(0,0,0,.25)}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        @media (max-width:860px){.grid{grid-template-columns:1fr}}
        .row{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
        .muted{color:var(--muted)}
        .hr{height:1px;background:rgba(255,255,255,.08);margin:14px 0}
        label{display:block;margin:0 0 6px;color:var(--muted)}
        input,select{width:100%;box-sizing:border-box;padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:var(--text);outline:none}
        input:focus,select:focus{border-color:rgba(79,140,255,.6);box-shadow:0 0 0 3px rgba(79,140,255,.15)}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 14px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:var(--text);cursor:pointer;text-decoration:none}
        .btn.primary{background:rgba(79,140,255,.95);border-color:rgba(79,140,255,.95)}
        .btn.danger{background:rgba(255,92,122,.14);border-color:rgba(255,92,122,.35);color:#ffd7df}
        .btn:disabled{opacity:.5;cursor:not-allowed}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px 8px;border-bottom:1px solid rgba(255,255,255,.08);text-align:left;vertical-align:top}
        th{color:var(--muted);font-weight:600}
        .tag{display:inline-block;padding:2px 8px;border-radius:999px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);font-size:12px}
        .tag.good{border-color:rgba(46,204,113,.35);background:rgba(46,204,113,.12);color:#c9f5da}
        .tag.bad{border-color:rgba(255,92,122,.35);background:rgba(255,92,122,.12);color:#ffd7df}
        .tag.warn{border-color:rgba(255,204,0,.35);background:rgba(255,204,0,.10);color:#fff1b8}
        .alert{padding:10px 12px;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.04);margin:0 0 12px}
        .alert.good{border-color:rgba(46,204,113,.35);background:rgba(46,204,113,.10)}
        .alert.bad{border-color:rgba(255,92,122,.35);background:rgba(255,92,122,.10)}
        .alert.warn{border-color:rgba(255,204,0,.35);background:rgba(255,204,0,.10)}
        .code{font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;background:rgba(0,0,0,.25);border:1px solid rgba(255,255,255,.10);padding:10px 12px;border-radius:12px;overflow:auto;white-space:pre}
        .foot{margin-top:14px;color:var(--muted);font-size:12px}
    </style></head><body><div class="wrap">';
    echo '<div class="top"><div class="brand"><h1>' . h($title) . '</h1><p>' . __('title_desc') . '</p></div>';
    echo '<div class="top-right">';
    echo '<div class="lang-selector">';
    foreach ($LANGUAGES as $code => $name) {
        $active = ($lang === $code) ? 'active' : '';
        $url = strtok($_SERVER['REQUEST_URI'], '?') . '?lang=' . $code;
        $queryParams = $_GET;
        unset($queryParams['lang']);
        if (!empty($queryParams)) {
            $url .= '&' . http_build_query($queryParams);
        }
        echo '<a href="' . h($url) . '" class="lang-btn ' . $active . '">' . h($name) . '</a>';
    }
    echo '</div>';
    echo '<div class="stepper">';
    echo '<span class="chip '.($step===1?'on':'').'">' . __('step_1') . '</span>';
    echo '<span class="chip '.($step===2?'on':'').'">' . __('step_2') . '</span>';
    echo '<span class="chip '.($step===3?'on':'').'">' . __('step_3') . '</span>';
    echo '<span class="chip '.($step===4?'on':'').'">' . __('step_4') . '</span>';
    echo '</div></div></div>';
}

function render_footer(): void {
    echo '</div></body></html>';
}

// ---------------------------
// Handle Actions
// ---------------------------
$step = current_step();

// Handle AJAX requests for progress
if (isset($_GET['action']) && $_GET['action'] === 'get_progress') {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    
    $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
    $progress = [
        'total' => 0,
        'current' => 0,
        'current_file' => '',
        'status' => 'idle',
        'message' => __('init_waiting')
    ];
    
    if (file_exists($progressFile)) {
        $fileContent = file_get_contents($progressFile);
        if ($fileContent !== false) {
            $fileProgress = json_decode($fileContent, true);
            if (is_array($fileProgress)) {
                $progress = $fileProgress;
            }
        }
    } else {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['init_progress'])) {
            $progress = $_SESSION['init_progress'];
        }
    }
    
    echo json_encode($progress);
    exit;
}

if (is_post()) {
    $action = (string)($_POST['action'] ?? '');

    if ($action === 'save_db') {
        $cfg = config_from_request();
        try {
            $pdo = pdo_connect($cfg);
            $pdo->query('SELECT 1');
            save_cfg($cfg);
            flash_set('good', __('connection_success'));
            header('Location: ?step=3' . ($lang !== 'zh' ? '&lang=' . $lang : ''));
            exit;
        } catch (Throwable $e) {
            save_cfg($cfg);
            flash_set('bad', __('connection_failed') . $e->getMessage());
            header('Location: ?step=2' . ($lang !== 'zh' ? '&lang=' . $lang : ''));
            exit;
        }
    }

    if ($action === 'test_db') {
        $cfg = config_from_request();
        try {
            $pdo = pdo_connect($cfg);
            $pdo->query('SELECT 1');
            save_cfg($cfg);
            flash_set('good', 'Test connection successful: You can proceed to the next step.');
        } catch (Throwable $e) {
            save_cfg($cfg);
            flash_set('bad', 'Test connection failed: ' . $e->getMessage());
        }
        header('Location: ?step=2' . ($lang !== 'zh' ? '&lang=' . $lang : ''));
        exit;
    }

    if ($action === 'init') {
        $cfg = get_saved_cfg();
        
        if (!$cfg || empty($cfg['default'])) {
            flash_set('bad', 'No database configuration detected. Please complete step 2 first.');
            header('Location: ' . ($_SERVER['PHP_SELF'] ?? 'install.php') . '?step=3' . ($lang !== 'zh' ? '&lang=' . $lang : ''));
            exit;
        }
        
        $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
        $progress = [
            'total' => 0,
            'current' => 0,
            'current_file' => '',
            'status' => 'starting',
            'message' => __('init_preparing')
        ];
        $_SESSION['init_progress'] = $progress;
        file_put_contents($progressFile, json_encode($progress));
        session_write_close();
        
        if (ob_get_level()) {
            ob_end_clean();
        }
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        
        echo str_repeat(' ', 1024);
        if (ob_get_level()) {
            ob_end_flush();
        }
        flush();
        
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $pdo = pdo_connect($cfg);
            
            $tablePrefix = $cfg['default']['tablepre'] ?? 'go_';
            
            $pdo->exec("SET NAMES utf8mb4");
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            $useTransaction = false;
            
            $databaseDir = __DIR__ . '/database';
            $sqlStatements = load_sql_files($databaseDir);
            
            $total = count($sqlStatements);
            $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
            $progress = [
                'total' => $total,
                'current' => 0,
                'current_file' => '',
                'status' => 'running',
                'message' => sprintf('Preparing to execute SQL statements... (Total %d)', $total)
            ];
            @session_start();
            $_SESSION['init_progress'] = $progress;
            file_put_contents($progressFile, json_encode($progress));
            session_write_close();
            
            if ($useTransaction) {
                $pdo->beginTransaction();
            }
            
            $executedCount = 0;
            $fileCount = [];
            $errors = [];
            $currentFile = '';
            
            foreach ($sqlStatements as $index => $item) {
                $currentFile = $item['file'];
                $currentNum = $index + 1;
                
                $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
                $progress = [
                    'total' => $total,
                    'current' => $currentNum,
                    'current_file' => $currentFile,
                    'status' => 'running',
                    'message' => "Executing: {$currentFile} ({$currentNum}/{$total})"
                ];
                @session_start();
                $_SESSION['init_progress'] = $progress;
                file_put_contents($progressFile, json_encode($progress));
                session_write_close();
                
                try {
                    $pdo->exec($item['sql']);
                    $executedCount++;
                    $fileName = $item['file'];
                    $fileCount[$fileName] = ($fileCount[$fileName] ?? 0) + 1;
                } catch (PDOException $e) {
                    if (strpos($e->getMessage(), 'already exists') !== false || 
                        strpos($e->getMessage(), 'Duplicate') !== false) {
                        continue;
                    }
                    $errors[] = "Failed to execute SQL in file {$item['file']}: " . $e->getMessage() . "\nSQL: " . substr($item['sql'], 0, 200);
                    error_log("SQL execution error: " . $e->getMessage() . "\nSQL snippet: " . substr($item['sql'], 0, 200));
                }
            }
            
            if (!empty($errors) && $executedCount === 0) {
                throw new RuntimeException("All SQL statements failed to execute. First error: " . $errors[0]);
            }
            
            if ($useTransaction && $pdo->inTransaction()) {
                $pdo->commit();
            }
            
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

            $fileInfo = [];
            foreach ($fileCount as $file => $count) {
                $fileInfo[] = "{$file} ({$count} statements)";
            }
            $fileSummary = implode(', ', $fileInfo);
            
            $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
            $progress = [
                'total' => $total,
                'current' => $total,
                'current_file' => '',
                'status' => 'completed',
                'message' => sprintf('Initialization complete: Executed %d SQL statements across %d files (%s).', 
                    $executedCount, count($fileCount), $fileSummary)
            ];
            @session_start();
            $_SESSION['init_progress'] = $progress;
            file_put_contents($progressFile, json_encode($progress));
            session_write_close();
            
            usleep(200000);
            
            flash_set('good', sprintf('Initialization complete: Executed %d SQL statements across %d files (%s).', 
                $executedCount, count($fileCount), $fileSummary));
        } catch (Throwable $e) {
            @session_start();
            if (isset($pdo) && $pdo instanceof PDO) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            }
            $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
            $progress = [
                'total' => $_SESSION['init_progress']['total'] ?? 0,
                'current' => $_SESSION['init_progress']['current'] ?? 0,
                'current_file' => '',
                'status' => 'error',
                'message' => __('init_error') . $e->getMessage()
            ];
            $_SESSION['init_progress'] = $progress;
            file_put_contents($progressFile, json_encode($progress));
            flash_set('bad', __('init_error') . $e->getMessage());
        }
        $redirectUrl = $_SERVER['PHP_SELF'] ?? 'install.php';
        $redirectUrl .= '?step=3';
        if ($lang !== 'zh') {
            $redirectUrl .= '&lang=' . $lang;
        }
        header('Location: ' . $redirectUrl);
        exit;
    }

    if ($action === 'fetch_countries') {
        $apiKey = trim((string)($_POST['api_key'] ?? $_SESSION['api_key'] ?? ''));
        $apiBaseUrl = "https://api.taolong.com";
        
        try {
            if ($apiKey === '') throw new RuntimeException(__('api_key_required'));
            
            $countriesData = fetch_available_countries($apiKey, $apiBaseUrl);
            
            $_SESSION['available_countries'] = $countriesData;
            $_SESSION['api_key'] = $apiKey;
            
            unset($_SESSION['fetch_results']);
            
            flash_set('good', __('countries_fetched'));
        } catch (Throwable $e) {
            flash_set('bad', __('countries_fetch_failed') . $e->getMessage());
        }
        header('Location: ?step=4' . ($lang !== 'zh' ? '&lang=' . $lang : ''));
        exit;
    }

    if ($action === 'fetch_data') {
        $apiKey = trim((string)($_POST['api_key'] ?? $_SESSION['api_key'] ?? ''));
        $apiBaseUrl = "https://api.taolong.com";
        $dataType = trim((string)($_POST['data_type'] ?? ''));
        $countryCode = trim((string)($_POST['country_code'] ?? ''));
        
        try {
            if ($apiKey === '') throw new RuntimeException(__('api_key_required'));
            
            $_SESSION['api_key'] = $apiKey;
            
            $cfg = get_saved_cfg();
            if (!$cfg || empty($cfg['default'])) {
                throw new RuntimeException('No database configuration detected. Please complete step 2 first.');
            }
            $pdo = pdo_connect($cfg);
            
            $allTypes = [
                'address' => ['type' => 'address', 'country_code' => 'CN', 'name' => __('get_address')],
                'product' => ['type' => 'product', 'country_code' => 'all', 'name' => __('get_product')],
                'bank' => ['type' => 'bank', 'country_code' => 'all', 'name' => __('get_bank')],
                'social' => ['type' => 'social', 'country_code' => 'all', 'name' => __('get_social')],
            ];
            
            if ($dataType !== '' && isset($allTypes[$dataType])) {
                $typeInfo = $allTypes[$dataType];
                if ($countryCode !== '') {
                    $typeInfo['country_code'] = $countryCode;
                }
                $types = [$typeInfo];
            } else {
                $types = array_values($allTypes);
            }
            
            $results = [];
            $totalCount = 0;
            $savedCount = 0;
            
            foreach ($types as $typeInfo) {
                try {
                    $firstPageResponse = fetch_library_data_full($apiKey, $typeInfo['type'], $typeInfo['country_code'], $apiBaseUrl, 1, 100);
                    $actualCountryCode = $firstPageResponse['country_code'] ?? $typeInfo['country_code'];
                    
                    $items = fetch_library_data($apiKey, $typeInfo['type'], $typeInfo['country_code'], $apiBaseUrl, true);
                    if ($typeInfo['type'] === 'address') {
                        $count = is_array($items) ? count($items) : 0;
                    } else {
                        $count = count($items);
                    }
                    $totalCount += $count;
                    
                    $saved = 0;
                    if ($typeInfo['type'] === 'bank' && $count > 0) {
                        $saved = save_bank_data($pdo, $actualCountryCode, $items);
                        $savedCount += $saved;
                    } elseif ($typeInfo['type'] === 'social' && $count > 0) {
                        $saved = save_social_data($pdo, $actualCountryCode, $items);
                        $savedCount += $saved;
                    } elseif ($typeInfo['type'] === 'address' && $count > 0) {
                        $saved = save_address_data($pdo, $actualCountryCode, $items);
                        $savedCount += $saved;
                    } elseif ($typeInfo['type'] === 'product' && $count > 0) {
                        $saved = save_product_data($pdo, $actualCountryCode, $items, true);
                        $savedCount += $saved;
                    }
                    
                    $results[] = [
                        'type' => $typeInfo['type'],
                        'name' => $typeInfo['name'],
                        'country_code' => $typeInfo['country_code'],
                        'count' => $count,
                        'saved' => $saved,
                        'success' => true,
                        'data' => $items,
                    ];
                } catch (Throwable $e) {
                    $results[] = [
                        'type' => $typeInfo['type'],
                        'name' => $typeInfo['name'],
                        'country_code' => $typeInfo['country_code'],
                        'count' => 0,
                        'saved' => 0,
                        'success' => false,
                        'error' => $e->getMessage(),
                    ];
                }
            }
            
            $_SESSION['fetch_results'] = $results;
            
            if ($totalCount > 0) {
                if ($savedCount > 0) {
                    flash_set('good', sprintf(__('data_fetched_with_saved'), $totalCount, $savedCount));
                } else {
                    flash_set('good', sprintf(__('data_fetched'), $totalCount));
                }
            } else {
                flash_set('warn', __('data_fetch_empty'));
            }
        } catch (Throwable $e) {
            flash_set('bad', __('data_fetch_failed') . $e->getMessage());
        }
        header('Location: ?step=4' . ($lang !== 'zh' ? '&lang=' . $lang : ''));
        exit;
    }

    if ($action === 'reset') {
        unset($_SESSION['install_cfg']);
        flash_set('good', __('config_reset'));
        header('Location: ?step=1' . ($lang !== 'zh' ? '&lang=' . $lang : ''));
        exit;
    }
}

// ---------------------------
// Page Rendering
// ---------------------------
render_header($step);

$flash = flash_get();
if ($flash) {
    $type = $flash['type'] ?? '';
    $cls = 'bad';
    if ($type === 'good') $cls = 'good';
    elseif ($type === 'warn') $cls = 'warn';
    echo '<div class="alert '.$cls.'">'.h((string)($flash['msg'] ?? '')).'</div>';
}

echo '<div class="card">';

if ($step === 1) {
    $check = env_check($REQUIRED_PHP, $REQUIRED_EXTENSIONS, $CHECK_FUNCTIONS);

    echo '<div class="row"><div class="tag '.($check['ok']?'good':'bad').'">'.($check['ok'] ? __('env_ok') : __('env_not_ok')).'</div>';
    echo '<div class="muted">' . __('env_fix_prompt') . '</div></div>';
    echo '<div class="hr"></div>';

    echo '<table><thead><tr><th style="width:220px">' . __('item') . '</th><th>' . __('result') . '</th></tr></thead><tbody>';

    echo '<tr><td>' . __('php_version') . '</td><td>';
    echo '<div class="row"><span class="tag '.($check['php']['ok']?'good':'bad').'">'.h($check['php']['current']).'</span>';
    echo '<span class="muted">' . __('required_version') . ' ' . h($check['php']['required']) . '</span></div>';
    echo '</td></tr>';

    echo '<tr><td>' . __('required_extensions') . '</td><td>';
    foreach ($check['extensions'] as $ext) {
        $ok = (bool)$ext['loaded'];
        echo '<span class="tag '.($ok?'good':'bad').'" style="margin:0 8px 8px 0;display:inline-block">'.h($ext['name']).' '.($ok ? __('loaded') : __('not_loaded')).'</span>';
    }
    echo '</td></tr>';

    echo '<tr><td>' . __('disabled_functions') . '</td><td class="muted">';
    echo '<div style="margin-top:8px">';
    foreach ($check['disabled_functions'] as $fn) {
        $un = (bool)$fn['unavailable'];
        echo '<span class="tag '.($un?'good':'warn').'" style="margin:0 8px 8px 0;display:inline-block">'.h($fn['name']).' '.($un ? __('unavailable') : __('available')).'</span>';
    }
    echo '</div></td></tr>';

    echo '</tbody></table>';

    if (!empty($check['notes'])) {
        echo '<div class="hr"></div><div class="muted"><b>' . __('suggestions') . '</b><ul style="margin:8px 0 0;padding-left:18px">';
        foreach ($check['notes'] as $n) echo '<li>'.h($n).'</li>';
        echo '</ul></div>';
    }

    echo '<div class="hr"></div>';
    if ($check['ok']) {
        echo '<div class="row">';
        echo '<a class="btn primary" href="?step=2' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('next') . '：' . __('step_2') . '</a>';
        echo '<form method="post" style="margin:0"><input type="hidden" name="action" value="reset"><button class="btn" type="submit">' . __('reset') . '</button></form>';
        echo '</div>';
    } else {
        echo '<div class="row">';
        echo '<a class="btn" href="?step=1' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('recheck') . '</a>';
        echo '<form method="post" style="margin:0"><input type="hidden" name="action" value="reset"><button class="btn" type="submit">' . __('reset') . '</button></form>';
        echo '</div>';
    }
}

if ($step === 2) {
    $cfg = get_saved_cfg();
    $cfg_def = [
            "default"=>[
                'hostname' => '127.0.0.1',
                'port' => 3306,
                'database' => '',
                'username' => '',
                'password' => '',
                'charset' => 'utf8mb4',
            ],
            'redis' =>  [
                'host'  =>  '127.0.0.1',
                'port'  =>  '6379',
                'password'  =>  '',
            ],
    ];
    $cfg = array_merge($cfg_def, $cfg);
    $cfg_database = $cfg['default'] ?? $cfg_def['default'];
    $cfg_redis = $cfg['redis'] ?? $cfg_def['redis'];

    echo '<div class="row"><div class="tag">' . __('db_config') . '</div><div class="muted">' . __('db_config_tip') . '</div></div>';
    echo '<div class="hr"></div>';

    echo '<form method="post" autocomplete="off">';
    echo '<input type="hidden" name="step" value="2">';
    if ($lang !== 'zh') {
        echo '<input type="hidden" name="lang" value="' . h($lang) . '">';
    }

    echo '<div class="muted" style="margin-bottom:12px"><b>' . __('db_config_title') . '</b></div>';
    echo '<div class="grid">';
    echo '<div><label>' . __('db_host') . '</label><input name="hostname" value="'.h((string)$cfg_database['hostname']).'" placeholder="' . __('db_host_placeholder') . '"></div>';
    echo '<div><label>' . __('db_port') . '</label><input name="port" type="number" value="'.h((string)$cfg_database['port']).'" placeholder="' . __('db_port_placeholder') . '"></div>';
    echo '<div><label>' . __('db_name') . '</label><input name="database" value="'.h((string)$cfg_database['database']).'" placeholder="' . __('db_name_placeholder') . '"></div>';
    echo '<div><label>' . __('db_charset') . '</label><select name="charset">';
    foreach (['utf8mb4','utf8'] as $cs) {
        $sel = ((string)$cfg_database['charset'] === $cs) ? ' selected' : '';
        echo '<option value="'.h($cs).'"'.$sel.'>'.h($cs).'</option>';
    }
    echo '</select></div>';
    echo '<div><label>' . __('db_user') . '</label><input name="username" value="'.h((string)$cfg_database['username']).'" placeholder="' . __('db_user_placeholder') . '"></div>';
    echo '<div><label>' . __('db_password') . '</label><input type="password" name="password" value="'.h((string)$cfg_database['password']).'" placeholder="' . __('db_password_placeholder') . '"></div>';
    echo '</div>';

    echo '<div class="hr"></div>';
    echo '<div class="muted" style="margin-bottom:12px"><b>' . __('redis_config_title') . '</b></div>';
    echo '<div class="grid">';
    echo '<div><label>' . __('redis_host') . '</label><input name="redis_host" value="'.h((string)$cfg_redis['host']).'" placeholder="' . __('redis_host_placeholder') . '"></div>';
    echo '<div><label>' . __('redis_port') . '</label><input name="redis_port" type="number" value="'.h((string)$cfg_redis['port']).'" placeholder="' . __('redis_port_placeholder') . '"></div>';
    echo '<div><label>' . __('redis_password') . '</label><input type="password" name="redis_password" value="'.h((string)$cfg_redis['password']).'" placeholder="' . __('redis_password_placeholder') . '"></div>';
    echo '</div>';

    echo '<div class="hr"></div>';
    echo '<div class="row">';
    echo '<button class="btn" type="submit" name="action" value="test_db">' . __('test_connection') . '</button>';
    echo '<button class="btn primary" type="submit" name="action" value="save_db">' . __('save_and_next') . '</button>';
    echo '<a class="btn" href="?step=1' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('back') . '：' . __('step_1') . '</a>';
    echo '</div>';
    echo '</form>';

    $cfgPreview = get_saved_cfg();
    if ($cfgPreview && !empty($cfgPreview['default'])) {
        echo '<div class="hr"></div>';
        echo '<div class="muted"><b>' . __('saved_config') . '</b>（' . __('saved_config_desc') . '）</div>';
        echo '<div class="code" style="margin-top:10px">';
        $preview = $cfgPreview['default'];
        echo h("Database Configuration:\nhostname={$preview['hostname']}\nport={$preview['port']}\ndatabase={$preview['database']}\nusername={$preview['username']}\npassword=********\ncharset={$preview['charset']}");
        if (!empty($cfgPreview['redis'])) {
            $redis = $cfgPreview['redis'];
            echo "\n\nRedis Configuration:\nhost={$redis['host']}\nport={$redis['port']}\npassword=" . (!empty($redis['password']) ? '********' : '(none)');
        }
        echo '</div>';
    }
}

if ($step === 3) {
    $cfg = get_saved_cfg();
    $hasCfg = !empty($cfg);
    
    $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
    $isCompleted = false;
    if (file_exists($progressFile)) {
        $fileContent = file_get_contents($progressFile);
        if ($fileContent !== false) {
            $fileProgress = json_decode($fileContent, true);
            if (is_array($fileProgress) && isset($fileProgress['status']) && $fileProgress['status'] === 'completed') {
                $isCompleted = true;
            }
        }
    }
    if (!$isCompleted && isset($_SESSION['init_progress']) && $_SESSION['init_progress']['status'] === 'completed') {
        $isCompleted = true;
    }

    echo '<div class="row">';
    echo '<div class="tag '.($hasCfg?'good':'bad').'">'.($hasCfg ? __('init_configured') : __('init_not_configured')).'</div>';
    if ($isCompleted) {
        echo '<div class="muted">' . __('init_completed') . '</div>';
    } else {
        echo '<div class="muted">' . ($hasCfg ? __('init_desc') : 'Please complete step 2 to configure the database connection first.') . '</div>';
    }
    echo '</div>';

    if ($hasCfg) {
        $databaseDir = __DIR__ . '/database';
        $sqlFiles = [];
        if (is_dir($databaseDir)) {
            $files = glob($databaseDir . '/*.sql');
            usort($files, function($a, $b) {
                $aName = basename($a);
                $bName = basename($b);
                if ($aName === 'init.sql') return -1;
                if ($bName === 'init.sql') return 1;
                return strcmp($aName, $bName);
            });
            $sqlFiles = array_map('basename', $files);
        }
        
        echo '<div class="hr"></div>';
        echo '<div class="muted"><b>' . __('init_sql_files') . '</b>：' . __('init_sql_files_desc') . '</div>';
        
        if (!empty($sqlFiles)) {
            echo '<div class="muted" style="margin-top:8px">' . __('init_files_list') . '</div>';
            echo '<ul class="muted" style="margin:8px 0 0;padding-left:18px">';
            foreach ($sqlFiles as $file) {
                echo '<li>'.h($file).'</li>';
            }
            echo '</ul>';
        } else {
            echo '<div class="muted" style="margin-top:8px;color:var(--warn)">' . __('init_no_files') . '</div>';
        }

        echo '<div class="hr"></div>';
        
        if ($isCompleted) {
            $progressFile = sys_get_temp_dir() . '/install_progress_' . session_id() . '.json';
            $completedMessage = __('init_complete_message');
            if (file_exists($progressFile)) {
                $fileContent = file_get_contents($progressFile);
                if ($fileContent !== false) {
                    $fileProgress = json_decode($fileContent, true);
                    if (is_array($fileProgress) && !empty($fileProgress['message'])) {
                        $completedMessage = $fileProgress['message'];
                    }
                }
            }
            
            echo '<div class="alert good">'.h($completedMessage).'</div>';
            echo '<div class="row">';
            echo '<a class="btn primary" href="?step=4' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('next') . '：' . __('step_4') . '</a>';
            echo '<a class="btn" href="?step=2' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('back') . '：' . __('step_2') . '</a>';
            echo '</div>';
        } else {
            echo '<form method="post" autocomplete="off" id="initForm">';
            echo '<input type="hidden" name="step" value="3">';
            echo '<input type="hidden" name="action" value="init">';
            if ($lang !== 'zh') {
                echo '<input type="hidden" name="lang" value="' . h($lang) . '">';
            }

            echo '<div class="row">';
            echo '<button class="btn primary" type="submit" id="initBtn">' . __('init_start') . '</button>';
            echo '<a class="btn" href="?step=2' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('back') . '：' . __('step_2') . '</a>';
            echo '</div>';
            echo '</form>';
        }
        
        echo '<div id="progressContainer" style="display:none;margin-top:20px">';
        echo '<div class="muted" style="margin-bottom:8px"><b>' . __('init_progress') . '</b></div>';
        echo '<div style="background:rgba(255,255,255,.06);border-radius:12px;height:24px;overflow:hidden;position:relative;border:1px solid rgba(255,255,255,.12)">';
        echo '<div id="progressBar" style="background:linear-gradient(90deg,rgba(79,140,255,.8),rgba(79,140,255,1));height:100%;width:0%;transition:width 0.3s ease;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:600"></div>';
        echo '</div>';
        echo '<div id="progressText" class="muted" style="margin-top:8px;font-size:12px">' . __('init_preparing') . '</div>';
        echo '</div>';
        
        echo '<script>
        document.getElementById("initForm").addEventListener("submit", function(e) {
            e.preventDefault();
            var btn = document.getElementById("initBtn");
            var container = document.getElementById("progressContainer");
            var progressBar = document.getElementById("progressBar");
            var progressText = document.getElementById("progressText");
            var idleChecks = 0;
            
            btn.disabled = true;
            btn.textContent = "' . __('init_processing') . '";
            container.style.display = "block";
            
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.send(formData);
            
            var progressInterval = setInterval(function() {
                var progressXhr = new XMLHttpRequest();
                progressXhr.open("GET", "?step=3&action=get_progress&t=" + Date.now() + "&lang=' . $lang . '", true);
                progressXhr.onreadystatechange = function() {
                    if (progressXhr.readyState === 4) {
                        if (progressXhr.status === 200) {
                            try {
                                var progress = JSON.parse(progressXhr.responseText);
                                console.log("Progress:", progress.status, progress.current, "/", progress.total, progress.message);
                                
                                if (progress.total > 0 && progress.current > 0) {
                                    var percent = Math.min(100, Math.round((progress.current / progress.total) * 100));
                                    progressBar.style.width = percent + "%";
                                    progressBar.textContent = percent + "%";
                                    progressText.textContent = progress.message || "' . __('init_processing') . ' (" + progress.current + "/" + progress.total + ")";
                                    idleChecks = 0;
                                } else if (progress.status === "starting" || progress.status === "running") {
                                    progressText.textContent = progress.message || "' . __('init_preparing') . '";
                                    if (progress.status === "running" && progress.total > 0) {
                                        progressBar.style.width = "5%";
                                        progressBar.textContent = "0%";
                                    }
                                    idleChecks = 0;
                                } else {
                                    progressText.textContent = progress.message || "' . __('init_waiting') . '";
                                    idleChecks++;
                                }
                                
                                if (progress.status === "completed" || progress.status === "error") {
                                    clearInterval(progressInterval);
                                    if (progress.status === "completed") {
                                        progressBar.style.background = "linear-gradient(90deg,rgba(46,204,113,.8),rgba(46,204,113,1))";
                                        progressBar.style.width = "100%";
                                        progressBar.textContent = "100%";
                                        progressText.textContent = progress.message;
                                        
                                        var nextBtn = document.createElement("a");
                                        nextBtn.href = "?step=4' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '";
                                        nextBtn.className = "btn primary";
                                        nextBtn.style.marginTop = "12px";
                                        nextBtn.textContent = "' . __('next') . '：' . __('step_4') . '";
                                        
                                        var btnContainer = btn.parentElement;
                                        if (btnContainer) {
                                            btnContainer.appendChild(nextBtn);
                                        }
                                        
                                        btn.style.display = "none";
                                    } else {
                                        progressBar.style.background = "linear-gradient(90deg,rgba(255,92,122,.8),rgba(255,92,122,1))";
                                        progressText.textContent = progress.message;
                                        btn.disabled = false;
                                        btn.textContent = "' . __('init_retry') . '";
                                    }
                                }

                                if (progress.status === "idle" && idleChecks >= 3) {
                                    clearInterval(progressInterval);
                                    btn.disabled = false;
                                    btn.textContent = "' . __('init_start') . '";
                                }
                            } catch(e) {
                                console.error("Failed to parse progress", e, progressXhr.responseText);
                            }
                        } else {
                            console.error("Failed to get progress, status code:", progressXhr.status);
                        }
                    }
                };
                progressXhr.onerror = function() {
                    console.error("Progress request failed");
                };
                progressXhr.send();
            }, 300);
            
            setTimeout(function() {
                clearInterval(progressInterval);
            }, 300000);
        });
        </script>';
        echo '<div class="row" style="margin-top:10px">';
        echo '<form method="post" style="margin:0"><input type="hidden" name="action" value="reset"><button class="btn danger" type="submit">' . __('reset') . '</button></form>';
        echo '</div>';

        echo '<div class="hr"></div>';
    } else {
        echo '<div class="hr"></div>';
        echo '<div class="row">';
        echo '<a class="btn primary" href="?step=2' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('init_go_to_db') . '</a>';
        echo '<a class="btn" href="?step=1' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('back') . '：' . __('step_1') . '</a>';
        echo '</div>';
    }
}

if ($step === 4) {
    echo '<div class="row">';
    echo '<div class="tag">' . __('get_data') . '</div>';
    echo '<div class="muted">' . __('get_data_desc') . '</div>';
    echo '</div>';
    
    echo '<div class="hr"></div>';
    echo '<div class="muted"><b>' . __('get_data_instruction') . '</b>：' . __('get_data_types') . '</div>';
    echo '<ul class="muted" style="margin:8px 0 0;padding-left:18px">';
    echo '<li><b>address</b>：' . __('get_address') . '</li>';
    echo '<li><b>product</b>：' . __('get_product') . '</li>';
    echo '<li><b>bank</b>：' . __('get_bank') . '</li>';
    echo '<li><b>social</b>：' . __('get_social') . '</li>';
    echo '</ul>';
    
    echo '<div class="hr"></div>';
    echo '<div class="muted"><b>' . __('api_key_config') . '</b></div>';
    $savedApiKey = $_SESSION['api_key'] ?? '';
    echo '<form method="post" autocomplete="off" id="apiKeyForm" style="margin-bottom:20px">';
    echo '<input type="hidden" name="step" value="4">';
    if ($lang !== 'zh') {
        echo '<input type="hidden" name="lang" value="' . h($lang) . '">';
    }
    echo '<div class="grid">';
    echo '<div><label>' . __('api_key') . '</label><input type="text" name="api_key" id="api_key_input" value="'.h($savedApiKey).'" placeholder="' . __('api_key_placeholder') . '" required></div>';
    echo '</div>';
    echo '</form>';
    
    echo '<div class="hr"></div>';
    echo '<form method="post" autocomplete="off" id="fetchCountriesForm" style="margin-bottom:20px">';
    echo '<input type="hidden" name="step" value="4">';
    echo '<input type="hidden" name="action" value="fetch_countries">';
    if ($lang !== 'zh') {
        echo '<input type="hidden" name="lang" value="' . h($lang) . '">';
    }
    echo '<div class="row">';
    echo '<button class="btn primary" type="submit" id="fetchCountriesBtn">' . __('get_countries') . '</button>';
    echo '<div class="muted" style="margin-left:12px">' . __('country_list_prompt') . '</div>';
    echo '</div>';
    echo '</form>';
    
    $availableCountries = $_SESSION['available_countries'] ?? null;
    if ($availableCountries && is_array($availableCountries)) {
        echo '<div class="hr"></div>';
        echo '<div class="muted"><b>' . __('available_countries') . '</b></div>';
        
        $dataTypes = [
            'address' => ['name' => __('get_address'), 'icon' => '📍'],
            'product' => ['name' => __('get_product'), 'icon' => '🛍️'],
            'bank' => ['name' => __('get_bank'), 'icon' => '🏦'],
            'social' => ['name' => __('get_social'), 'icon' => '👥'],
        ];
        
        foreach ($dataTypes as $type => $info) {
            if (isset($availableCountries['available'][$type]) && is_array($availableCountries['available'][$type])) {
                echo '<div style="margin-top:16px;padding:12px;background:rgba(255,255,255,.03);border-radius:12px;border:1px solid rgba(255,255,255,.08)">';
                echo '<div class="muted" style="margin-bottom:8px"><strong>'.$info['icon'].' '.h($info['name']).'</strong></div>';
                echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:8px">';
                foreach ($availableCountries['available'][$type] as $country) {
                    $code = $country['code'] ?? '';
                    $name = $country['name'] ?? '';
                    $count = isset($country['count']) ? ' ('.number_format($country['count']).' ' . __('records') . ')' : '';
                    echo '<div style="padding:8px;background:rgba(255,255,255,.05);border-radius:8px;border:1px solid rgba(255,255,255,.1)">';
                    echo '<div style="font-weight:600;color:var(--text)">'.h($name).'</div>';
                    echo '<div style="font-size:12px;color:var(--muted);margin-top:4px"><code>'.h($code).'</code>'.$count.'</div>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
        }
    }
    
    echo '<div class="hr"></div>';
    echo '<div class="muted"><b>' . __('data_retrieval') . '</b>：' . __('data_retrieval_desc') . '</div>';
    
    if (!$availableCountries || !is_array($availableCountries)) {
        echo '<div class="alert warn" style="margin-top:12px">';
        echo '💡 <strong>' . __('info') . '</strong>：' . __('country_list_prompt');
        echo '</div>';
    }
    
    $dataTypes = [
        'address' => ['name' => __('get_address'), 'icon' => '📍', 'color' => 'primary'],
        'product' => ['name' => __('get_product'), 'icon' => '🛍️', 'color' => 'primary'],
        'bank' => ['name' => __('get_bank'), 'icon' => '🏦', 'color' => 'primary'],
        'social' => ['name' => __('get_social'), 'icon' => '👥', 'color' => 'primary'],
    ];
    
    foreach ($dataTypes as $type => $info) {
        echo '<div style="margin-top:12px;padding:12px;background:rgba(255,255,255,.03);border-radius:12px;border:1px solid rgba(255,255,255,.08)">';
        echo '<div class="muted" style="margin-bottom:8px"><strong>'.$info['icon'].' '.h($info['name']).'</strong></div>';
        
        if ($availableCountries && isset($availableCountries['available'][$type]) && is_array($availableCountries['available'][$type]) && count($availableCountries['available'][$type]) > 0) {
            echo '<form method="post" autocomplete="off" class="fetch-type-form" data-type="'.h($type).'" style="display:flex;gap:8px;align-items:flex-end">';
            echo '<input type="hidden" name="step" value="4">';
            echo '<input type="hidden" name="action" value="fetch_data">';
            echo '<input type="hidden" name="data_type" value="'.h($type).'">';
            if ($lang !== 'zh') {
                echo '<input type="hidden" name="lang" value="' . h($lang) . '">';
            }
            echo '<div style="flex:1">';
            echo '<label style="display:block;margin-bottom:6px;font-size:12px;color:var(--muted)">' . __('country_code') . '</label>';
            echo '<select name="country_code" class="country-select" style="width:100%">';
            foreach ($availableCountries['available'][$type] as $country) {
                $code = $country['code'] ?? '';
                $name = $country['name'] ?? '';
                $count = isset($country['count']) ? ' ('.number_format($country['count']).' ' . __('records') . ')' : '';
                echo '<option style="background-color: #070b14;" value="'.h($code).'">'.h($name).' - '.h($code).$count.'</option>';
            }
            echo '</select>';
            echo '</div>';
            echo '<button class="btn '.$info['color'].'" type="submit" style="white-space:nowrap">' . __('get_data_button') . '</button>';
            echo '</form>';
        } else {
            echo '<form method="post" autocomplete="off" class="fetch-type-form" data-type="'.h($type).'" style="margin:0">';
            echo '<input type="hidden" name="step" value="4">';
            echo '<input type="hidden" name="action" value="fetch_data">';
            echo '<input type="hidden" name="data_type" value="'.h($type).'">';
            if ($lang !== 'zh') {
                echo '<input type="hidden" name="lang" value="' . h($lang) . '">';
            }
            echo '<button class="btn '.$info['color'].'" type="submit" style="width:100%">' . __('use_default') . '</button>';
            echo '</form>';
        }
        echo '</div>';
    }
    
    echo '<div class="hr"></div>';
    echo '<div class="row">';
    echo '<a class="btn" href="?step=3' . ($lang !== 'zh' ? '&lang=' . $lang : '') . '">' . __('back') . '：' . __('step_3') . '</a>';
    echo '<a class="btn primary" href="/go/index/init">' . __('complete') . '</a>';
    echo '</div>';
    
    echo '<script>
    function syncApiKeyToForms() {
        var apiKey = document.getElementById("api_key_input").value;
        var forms = document.querySelectorAll(".fetch-type-form, #fetchCountriesForm");
        forms.forEach(function(form) {
            var input = form.querySelector("input[name=\'api_key\']");
            if (!input) {
                input = document.createElement("input");
                input.type = "hidden";
                input.name = "api_key";
                form.appendChild(input);
            }
            input.value = apiKey;
        });
    }
    
    document.getElementById("api_key_input").addEventListener("input", syncApiKeyToForms);
    document.getElementById("api_key_input").addEventListener("change", syncApiKeyToForms);
    
    document.getElementById("fetchCountriesForm").addEventListener("submit", function(e) {
        var apiKey = document.getElementById("api_key_input").value.trim();
        if (!apiKey) {
            e.preventDefault();
            alert("' . __('api_key_required') . '");
            document.getElementById("api_key_input").focus();
            return false;
        }
        syncApiKeyToForms();
    });
    
    document.querySelectorAll(".fetch-type-form").forEach(function(form) {
        form.addEventListener("submit", function(e) {
            var apiKey = document.getElementById("api_key_input").value.trim();
            if (!apiKey) {
                e.preventDefault();
                alert("' . __('api_key_required') . '");
                document.getElementById("api_key_input").focus();
                return false;
            }
            syncApiKeyToForms();
        });
    });
    </script>';
    
    $fetchResults = $_SESSION['fetch_results'] ?? null;
    if ($fetchResults && is_array($fetchResults)) {
        echo '<div class="hr"></div>';
        echo '<div class="muted"><b>' . __('fetch_results') . '</b></div>';
        echo '<table style="margin-top:10px">';
        echo '<thead><tr><th>' . __('type') . '</th><th>' . __('name') . '</th><th>' . __('country') . '</th><th>' . __('status') . '</th><th>' . __('count') . '</th><th>' . __('saved') . '</th></tr></thead><tbody>';
        foreach ($fetchResults as $result) {
            echo '<tr>';
            echo '<td><code>'.h($result['type'] ?? '').'</code></td>';
            echo '<td>'.h($result['name'] ?? '').'</td>';
            echo '<td>'.h($result['country_code'] ?? '').'</td>';
            if ($result['success'] ?? false) {
                echo '<td><span class="tag good">' . __('success') . '</span></td>';
                echo '<td><strong>'.h((string)($result['count'] ?? 0)).'</strong></td>';
                $saved = (int)($result['saved'] ?? 0);
                if (($result['type'] === 'bank' || $result['type'] === 'social' || $result['type'] === 'address') && $saved > 0) {
                    echo '<td><span class="tag good"><strong>'.h((string)$saved).'</strong></span></td>';
                } else {
                    echo '<td class="muted">-</td>';
                }
            } else {
                echo '<td><span class="tag bad">' . __('failed') . '</span></td>';
                echo '<td colspan="2" class="muted" style="max-width:400px;word-break:break-word">'.h($result['error'] ?? __('unknown_error')).'</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
        
        unset($_SESSION['fetch_results']);
        
        echo '<div class="hr"></div>';
        $hasBankData = false;
        $hasSocialData = false;
        $hasAddressData = false;
        $totalSaved = 0;
        $bankSaved = 0;
        $socialSaved = 0;
        $addressSaved = 0;
        $totalCount = 0;
        foreach ($fetchResults as $result) {
            if ($result['type'] === 'bank' && ($result['success'] ?? false)) {
                $hasBankData = true;
                $saved = (int)($result['saved'] ?? 0);
                $bankSaved += $saved;
                $totalSaved += $saved;
            }
            if ($result['type'] === 'social' && ($result['success'] ?? false)) {
                $hasSocialData = true;
                $saved = (int)($result['saved'] ?? 0);
                $socialSaved += $saved;
                $totalSaved += $saved;
            }
            if ($result['type'] === 'address' && ($result['success'] ?? false)) {
                $hasAddressData = true;
                $saved = (int)($result['saved'] ?? 0);
                $addressSaved += $saved;
                $totalSaved += $saved;
            }
            if ($result['success'] ?? false) {
                $totalCount += (int)($result['count'] ?? 0);
            }
        }
        if ($hasBankData && $bankSaved > 0) {
            echo '<div class="alert good">' . sprintf(__('bank_saved'), $bankSaved) . '</div>';
        }
        if ($hasSocialData && $socialSaved > 0) {
            echo '<div class="alert good">' . sprintf(__('social_saved'), $socialSaved) . '</div>';
        }
        if ($hasAddressData && $addressSaved > 0) {
            echo '<div class="alert good">' . sprintf(__('address_saved'), $addressSaved) . '</div>';
        }
        if ($totalCount > 0) {
            echo '<div class="muted">' . sprintf(__('total_records'), $totalCount) . '</div>';
        }
    }
}

echo '</div>';
render_footer();
?>