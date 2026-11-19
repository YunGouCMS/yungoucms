<?php
defined('G_IN_SYSTEM') or exit('No permission resources.');
System::load_app_class('admin', G_ADMIN_DIR, 'no');
class import extends admin
{
    private $db;
    private $apiBaseUrl = "https://api.taolong.com";
    const TYPES = [
        "1" => "address",
        "2" => "bank",
        "3" => "product",
    ];
    const LAN = [
        "CN" => [
            "name" => "中国",
            "lan" => "Chinese",
        ],
        "US" => [
            "name" => "美国",
            "lan" => "English",
        ],
        "en" => [
            "name" => "美国",
            "lan" => "English",
        ],
        "JP" => [
            "name" => "日本",
            "lan" => "Japanese",
        ],
        "KR" => [
            "name" => "韩国",
            "lan" => "Korean",
        ],
    ];
    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_app_model('admin_model', G_ADMIN_DIR);
    }

    public function index()
    {
        $type = $this->segment(4);
        if (!isset(self::TYPES[$type])) {
            _messageerror("请选择导入类型");
        }
        if($type == 1){
            $title_text = $this->lan("address", "address",2);
        }elseif($type == 2){
            $title_text = $this->lan("bank", "bank",2);
        }elseif($type == 3){
            $title_text = $this->lan("product", "product",2);
        }
        $apikey = _cfg("taolong_crypto_api_key");
        if(empty($apikey)|| is_array($apikey)){
            $apikey = '';
        }
        include $this->tpl(ROUTE_M, 'import.index');
    }

    public function getImortLan()
    {
        $type = getParams('type');
        if (!isset(self::TYPES[$type])) {
            $this->jsonData->error(400, '参数错误');
            return;
        }
        $apikey = getParams('apikey') ?: _cfg("taolong_crypto_api_key");
        $data = $this->fetch_available_countries($apikey);
        $available = $data['available'];
        $this->jsonData->success(200, 'ok', ["list" => $available[self::TYPES[$type]]]);
    }

    private function get_saved_cfg(): array {
        $configFile =   './system/config/database.inc.php';
        if (file_exists($configFile)) {
            $cfg = include $configFile;
            return is_array($cfg) ? $cfg : [];
        }
        $cfg = $_SESSION['install_cfg'] ?? [];
        return is_array($cfg) ? $cfg : [];
    }

    private function pdo_connect(array $cfg): PDO
    {
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

    public function getImportData()
    {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $cfg = $this->get_saved_cfg();
        $pdo = $this->pdo_connect($cfg);
        $type = trim(getParams('type'));
        $countryCode = trim(getParams('country_code'));
        $apiKey = getParams('apikey') ?: _cfg("taolong_crypto_api_key");
        $page = (int)(getParams('page') ?: 1);
        $pageSize = (int)(getParams('page_size') ?: 200);
        if ($page < 1) { $page = 1; }
        if ($pageSize < 1) { $pageSize = 200; }
        if ($pageSize > 1000) { $pageSize = 1000; }
        $clear = (int)(getParams('clear') ?: 0) === 1;

        $type = self::TYPES[$type] ?? '';
        if(empty($type) || empty($countryCode) || empty($apiKey)) {
            $this->jsonData->error(400, '参数错误');
        }

        // address：接口可能一次返回全量，且单省可能很大；这里改成“缓存 + 游标批处理”，每次只写固定条数避免超时
        if ($type === 'address') {
            $cacheKey = sha1($apiKey . '|' . $type . '|' . $countryCode);
            $cacheFile = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "taolong_import_{$cacheKey}.json";
            $stateFile = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "taolong_import_state_{$cacheKey}.json";

            if ($clear) {
                if (file_exists($cacheFile)) { @unlink($cacheFile); }
                if (file_exists($stateFile)) { @unlink($stateFile); }
            }

            $provinces = null;
            if (file_exists($cacheFile)) {
                $raw = @file_get_contents($cacheFile);
                if ($raw !== false) {
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) {
                        $provinces = $decoded;
                    }
                }
            }

            if (!is_array($provinces)) {
                // 首次拉取全量（只做一次）
                $data = $this->fetch_library_data_full($apiKey, $type, $countryCode, 1, 1000);
                if (isset($data['data']['provinces']) && is_array($data['data']['provinces'])) {
                    $provinces = $data['data']['provinces'];
                } else {
                    $provinces = [];
                }
                @file_put_contents($cacheFile, json_encode($provinces, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }

            $state = null;
            if (file_exists($stateFile)) {
                $rawState = @file_get_contents($stateFile);
                if ($rawState !== false) {
                    $decodedState = json_decode($rawState, true);
                    if (is_array($decodedState)) {
                        $state = $decodedState;
                    }
                }
            }
            if (!is_array($state)) {
                $state = [
                    'area_id' => 0,
                    'p' => 0,
                    'c' => 0,
                    'd' => 0,
                    'province_id' => 0,
                    'city_id' => 0,
                    'sort' => 1,
                    'processed' => 0, // 已插入明细条数（省/市/区合计）
                ];
            }

            // page_size 对 address 作为“单次最多插入条数”使用（默认 200 会太小，这里放大一点）
            $maxOps = max(200, min(2000, $pageSize * 4));
            $batch = $this->save_address_data_cursor($pdo, $countryCode, $provinces, $state, $maxOps, $clear || $page === 1);

            $saved = (int)($batch['saved'] ?? 0);
            $state = $batch['state'] ?? $state;
            $done = (bool)($batch['done'] ?? false);
            @file_put_contents($stateFile, json_encode($state, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            if ($done) {
                if (file_exists($cacheFile)) { @unlink($cacheFile); }
                if (file_exists($stateFile)) { @unlink($stateFile); }
            }

            $this->jsonData->success(200, 'ok', [
                'page' => $page,
                'page_size' => $pageSize,
                'total' => 0, // 地址总条数不预计算（避免再次遍历），前端用 fetched_so_far + 判断 has_more
                'count' => $saved,
                'saved' => $saved,
                'fetched_so_far' => (int)($state['processed'] ?? 0),
                'has_more' => $done ? 0 : 1,
                'next_page' => $done ? $page : ($page + 1),
            ]);
        }

        // 其它类型：按接口分页拉取 + 保存
        $data = $this->fetch_library_data_full($apiKey, $type, $countryCode, $page, $pageSize);
        $total = (int)($data['data']['total'] ?? 0);

        $items = [];
        if ($type === 'bank' && isset($data['data']['banks']) && is_array($data['data']['banks'])) {
            $items = $data['data']['banks'];
        } elseif ($type === 'social' && isset($data['data']['users']) && is_array($data['data']['users'])) {
            $items = $data['data']['users'];
        } elseif ($type === 'product' && isset($data['data']['products']) && is_array($data['data']['products'])) {
            $items = $data['data']['products'];
        }

        $count = is_array($items) ? count($items) : 0;
        $saved = 0;
        if ($type === 'bank' && $count > 0) {
            $saved = $this->save_bank_data($pdo, $countryCode, $items, $clear || $page === 1);
        } elseif ($type === 'social' && $count > 0) {
            $saved = $this->save_social_data($pdo, $countryCode, $items);
        } elseif ($type === 'product' && $count > 0) {
            $saved = $this->save_product_data($pdo, $countryCode, $items, $clear || $page === 1);
        }

        $fetchedSoFar = (($page - 1) * $pageSize) + $count;
        $hasMore = false;
        if ($total > 0) {
            $hasMore = $fetchedSoFar < $total && $count > 0;
        } else {
            $hasMore = $count >= $pageSize;
        }

        $this->jsonData->success(200, 'ok', [
            'page' => $page,
            'page_size' => $pageSize,
            'total' => $total,
            'count' => $count,
            'saved' => $saved,
            'fetched_so_far' => $fetchedSoFar,
            'has_more' => $hasMore ? 1 : 0,
            'next_page' => $hasMore ? ($page + 1) : $page,
        ]);
    }

    private function save_address_data_cursor(PDO $pdo, string $countryCode, array $provinces, array $state, int $maxOps, bool $clearExisting): array
    {
        $now = time();
        $tablePrefix = 'go_';
        $areaTable = $tablePrefix . 'area';
        $areaDetailsTable = $tablePrefix . 'area_details';

        $areaId = (int)($state['area_id'] ?? 0);
        if ($areaId <= 0) {
            $checkAreaStmt = $pdo->prepare("SELECT `id` FROM `{$areaTable}` WHERE `language` = :language LIMIT 1");
            $checkAreaStmt->execute([':language' => $countryCode]);
            $areaRecord = $checkAreaStmt->fetch();
            if ($areaRecord) {
                $areaId = (int)$areaRecord['id'];
            } else {
                $insertAreaStmt = $pdo->prepare("
                    INSERT INTO `{$areaTable}` (`name`, `desc`, `create_time`, `update_time`, `status`, `language`)
                    VALUES (:name, :desc, :create_time, :update_time, :status, :language)
                ");
                $areaName = self::LAN[$countryCode]['name'] ?? $countryCode;
                $language = self::LAN[$countryCode]['lan'] ?? $countryCode;
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
            $state['area_id'] = $areaId;
        }

        if ($clearExisting) {
            $deleteStmt = $pdo->prepare("DELETE FROM `{$areaDetailsTable}` WHERE `area_id` = :area_id");
            $deleteStmt->execute([':area_id' => $areaId]);
            $state['p'] = 0;
            $state['c'] = 0;
            $state['d'] = 0;
            $state['province_id'] = 0;
            $state['city_id'] = 0;
            $state['sort'] = 1;
            $state['processed'] = 0;
        }

        $insertStmt = $pdo->prepare("
            INSERT INTO `{$areaDetailsTable}` (`area_id`, `pid`, `name`, `desc`, `status`, `create_time`, `update_time`, `sort`, `code`)
            VALUES (:area_id, :pid, :name, :desc, :status, :create_time, :update_time, :sort, :code)
        ");

        $saved = 0;
        $ops = 0;
        $p = (int)($state['p'] ?? 0);
        $c = (int)($state['c'] ?? 0);
        $d = (int)($state['d'] ?? 0);
        $provinceId = (int)($state['province_id'] ?? 0);
        $cityId = (int)($state['city_id'] ?? 0);
        $sort = (int)($state['sort'] ?? 1);
        $processed = (int)($state['processed'] ?? 0);

        $pTotal = count($provinces);
        while ($ops < $maxOps && $p < $pTotal) {
            $province = $provinces[$p] ?? null;
            if (!is_array($province)) {
                $p++;
                $c = 0; $d = 0; $provinceId = 0; $cityId = 0;
                continue;
            }

            if ($provinceId <= 0) {
                $provinceName = trim((string)($province['name'] ?? ''));
                if ($provinceName === '') {
                    $p++;
                    $c = 0; $d = 0; $provinceId = 0; $cityId = 0;
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
                $saved++; $processed++; $ops++;
                if ($ops >= $maxOps) {
                    break;
                }
            }

            $cities = $province['cities'] ?? [];
            if (!is_array($cities)) {
                $p++;
                $c = 0; $d = 0; $provinceId = 0; $cityId = 0;
                continue;
            }

            $cTotal = count($cities);
            while ($ops < $maxOps && $c < $cTotal) {
                $city = $cities[$c] ?? null;
                if (!is_array($city)) {
                    $c++; $d = 0; $cityId = 0;
                    continue;
                }

                if ($cityId <= 0) {
                    $cityName = trim((string)($city['name'] ?? ''));
                    if ($cityName === '') {
                        $c++; $d = 0; $cityId = 0;
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
                        ':sort' => $sort++,
                        ':code' => $cityCode ?: null,
                    ]);
                    $cityId = (int)$pdo->lastInsertId();
                    $saved++; $processed++; $ops++;
                    if ($ops >= $maxOps) {
                        break;
                    }
                }

                $districts = $city['districts'] ?? [];
                if (is_array($districts)) {
                    $dTotal = count($districts);
                    while ($ops < $maxOps && $d < $dTotal) {
                        $district = $districts[$d] ?? null;
                        if (!is_array($district)) {
                            $d++;
                            continue;
                        }
                        $districtName = trim((string)($district['name'] ?? ''));
                        if ($districtName === '') {
                            $d++;
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
                            ':sort' => $sort++,
                            ':code' => $districtCode ?: null,
                        ]);
                        $saved++; $processed++; $ops++; $d++;
                    }
                }

                if (!isset($districts) || !is_array($districts) || $d >= (is_array($districts) ? count($districts) : 0)) {
                    $c++;
                    $d = 0;
                    $cityId = 0;
                }
            }

            if ($c >= $cTotal) {
                $p++;
                $c = 0; $d = 0;
                $provinceId = 0;
                $cityId = 0;
            }
        }

        $state['p'] = $p;
        $state['c'] = $c;
        $state['d'] = $d;
        $state['province_id'] = $provinceId;
        $state['city_id'] = $cityId;
        $state['sort'] = $sort;
        $state['processed'] = $processed;

        $done = $p >= $pTotal;
        return [
            'saved' => $saved,
            'state' => $state,
            'done' => $done,
        ];
    }
    /**
     * 
     * 导入地址
     * @return void
     */
    private function save_address_data(PDO $pdo,string $countryCode, array $provinces, bool $clearExisting = true): int
    {
        if (empty($provinces)) {
            return 0;
        }
        $now = time();
        $tablePrefix = 'go_';
        $areaTable = $tablePrefix . 'area';
        $areaDetailsTable = $tablePrefix . 'area_details';

        $checkAreaStmt = $pdo->prepare("SELECT `id` FROM `{$areaTable}` WHERE `language` = :language LIMIT 1");
        $checkAreaStmt->execute([':language' => $countryCode]);
        $areaRecord = $checkAreaStmt->fetch();

        if ($areaRecord) {
            $areaId = (int)$areaRecord['id'];
        } else {
            $insertAreaStmt = $pdo->prepare("
                INSERT INTO `{$areaTable}` (`name`, `desc`, `create_time`, `update_time`, `status`, `language`)
                VALUES (:name, :desc, :create_time, :update_time, :status, :language)
            ");
            $areaName = self::LAN[$countryCode]['name']??$countryCode;
            $language = self::LAN[$countryCode]['lan']??$countryCode;
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

        if ($clearExisting) {
            $deleteStmt = $pdo->prepare("DELETE FROM `{$areaDetailsTable}` WHERE `area_id` = :area_id");
            $deleteStmt->execute([':area_id' => $areaId]);
        }

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

    /**
     * 
     * 导入银行
     * @return void
     */
    private function save_bank_data(PDO $pdo, string $countryCode, array $banks, bool $clearExisting = true): int {
        if (empty($banks)) {
            return 0;
        }
        
        $now = time();
        $tablePrefix = 'go_';
        $tableName = $tablePrefix . 'bank';

        if ($clearExisting) {
            $deleteStmt = $pdo->prepare("DELETE FROM `{$tableName}` WHERE `language` = :language");
            $deleteStmt->execute([':language' => $countryCode]);
        }
        
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
            $language = self::LAN[$countryCode]["lan"]??$countryCode;
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
    /**
     * 
     * 导入产品
     * @return void
     */
    private function save_product_data(PDO $pdo, string $countryCode, array $products, bool $clearExisting = true): int {
        if (empty($products)) {
            return 0;
        }

        $now = time();
        $tablePrefix = 'go_';
        $tableName = $tablePrefix . 'shoplist';
        $language = self::LAN[$countryCode]["lan"] ?? $countryCode;

        if ($clearExisting) {
            $deleteStmt = $pdo->prepare("DELETE FROM `{$tableName}` WHERE `language` = :language");
            $deleteStmt->execute([':language' => $language]);
        }

        $cateid = $this->get_default_goods_category_id($pdo, $language);

        $checkStmt = $pdo->prepare("SELECT `id` FROM `{$tableName}` WHERE `title` = :title AND `language` = :language LIMIT 1");
        $insertStmt = $pdo->prepare("
            INSERT INTO `{$tableName}`
            (`sid`, `cateid`, `brandid`, `title`, `title_style`, `title2`, `keywords`, `description`, `money`, `yunjiage`, `zongrenshu`, `canyurenshu`, `shenyurenshu`, `def_renshu`, `qishu`, `maxqishu`, `thumb`, `picarr`, `content`, `codes_table`, `xsjx_time`, `pos`, `renqi`, `time`, `order`, `q_user`, `q_showtime`, `zhiding`, `not_prize_id`, `ispk`, `ssc`, `send`, `xg`, `dh`, `dhbl`, `language`, `is_show`, `preferential_price`, `groups`)
            VALUES
            (0, :cateid, 0, :title, '', '', '', :description, :money, :yunjiage, :zongrenshu, 0, :shenyurenshu, 0, 1, :maxqishu, :thumb, :picarr, :content, :codes_table, NULL, 0, 0, :time, 1, :q_user, 'N', 0, 0, 0, 0, 0, 0, 0, 0, :language, 1, :preferential_price, :groups)
        ");
        $updateStmt = $pdo->prepare("
            UPDATE `{$tableName}`
            SET `cateid` = :cateid,
                `description` = :description,
                `money` = :money,
                `yunjiage` = :yunjiage,
                `zongrenshu` = :zongrenshu,
                `shenyurenshu` = :shenyurenshu,
                `maxqishu` = :maxqishu,
                `thumb` = :thumb,
                `picarr` = :picarr,
                `content` = :content,
                `codes_table` = :codes_table,
                `time` = :time,
                `language` = :language,
                `preferential_price` = :preferential_price,
                `groups` = :groups
            WHERE `id` = :id
        ");

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
            $money = 100.00;
            $yunjiage = 1.00;
            $zongrenshu = (int)ceil($money / $yunjiage);
            $shenyurenshu = $zongrenshu;
            $maxqishu = 9999;

            $payload = [
                ':cateid' => $cateid,
                ':title' => $title,
                ':description' => $content !== '' ? $content : $title,
                ':money' => $money,
                ':yunjiage' => $yunjiage,
                ':zongrenshu' => $zongrenshu,
                ':shenyurenshu' => $shenyurenshu,
                ':maxqishu' => $maxqishu,
                ':thumb' => $thumb,
                ':picarr' => $picarr,
                ':content' => $content,
                ':codes_table' => null,
                ':time' => $now,
                ':language' => $language,
                ':preferential_price' => $money,
                ':groups' => '',
            ];

            $checkStmt->execute([':title' => $title, ':language' => $language]);
            $existing = $checkStmt->fetch();
            if ($existing) {
                $payload[':id'] = $existing['id'];
                $updateStmt->execute($payload);
            } else {
                $insertStmt->execute(array_merge($payload, [
                    ':q_user' => serialize([]),
                ]));
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

    private function get_default_goods_category_id(PDO $pdo, string $language): int
    {
        $tablePrefix = 'go_';
        $tableName = $tablePrefix . 'category';

        $stmt = $pdo->prepare("SELECT `cateid` FROM `{$tableName}` WHERE `model` = 1 AND (`language` = :language OR `language` IS NULL OR `language` = '') ORDER BY `order` DESC, `cateid` ASC LIMIT 1");
        $stmt->execute([':language' => $language]);
        $row = $stmt->fetch();
        if ($row && isset($row['cateid'])) {
            return (int)$row['cateid'];
        }

        $fallbackStmt = $pdo->prepare("SELECT `cateid` FROM `{$tableName}` WHERE `model` = 1 ORDER BY `order` DESC, `cateid` ASC LIMIT 1");
        $fallbackStmt->execute();
        $fallback = $fallbackStmt->fetch();
        if ($fallback && isset($fallback['cateid'])) {
            return (int)$fallback['cateid'];
        }

        return 0;
    }

    /**
     * 
     * 导入社交
     * @return void
     */
    private function save_social_data(PDO $pdo, string $countryCode, array $users): int {
        if (empty($users)) {
            return 0;
        }
        
        $now = time();
        $tablePrefix = 'go_';
        $tableName = $tablePrefix . 'member';
        
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
            $language = self::LAN[$countryCode]["lan"]??$countryCode;
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

    private function fetch_available_countries(string $apiKey): array
    {
        $url = rtrim($this->apiBaseUrl, '/') . '/api/libraries/countries';
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

    private function fetch_library_data_full(string $apiKey, string $type, string $countryCode, int $page = 1, int $pageSize = 100): array
    {
        $url = rtrim($this->apiBaseUrl, '/') . '/api/libraries';
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
}
