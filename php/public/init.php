<?
$phpPath = is_dir('/app') ? '/app/php' : '/www/wwwroot/smanga.mn2.cc/php';
require_once "$phpPath/public/lkw.php";
require_once "$phpPath/dosql/mysql-1.0.php";

$link = @mysqli_connect($gIp, $gUserName, $gPassWord, $gDatabase, $gPort)
	or exit_request([
		'code' => 1,
		'initCode' => 0,
		'message' => '数据库链接错误',
	]);
// 设置默认字符集
$link->set_charset('utf8mb4');
// 切换当前数据库
$link->query('use smanga;');

$verRes = dosql(['table' => 'version']);

$vers = [];

if ($verRes) {
	for ($i = 0; $i < count($verRes); $i++) {
		array_push($vers, $verRes[$i]['version']);
	}
}

if (is_file('./version')) {
	exit_request([
		'code' => 1,
		'initCode' => 3,
		'vers' => $vers,
		'text' => '无需部署'
	]);
}

// 书签表
$link->query("
		CREATE TABLE IF NOT EXISTS `bookmark`  (
		`bookmarkId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		`mediaId` int(11) NULL DEFAULT NULL,
		`mangaId` int(11) NULL DEFAULT NULL,
		`chapterId` int(11) NULL DEFAULT NULL,
		`userId` int(11) NULL DEFAULT NULL,
		`browseType` enum('flow','single','double') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'single',
		`page` int(11) NULL DEFAULT NULL,
		`pageImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
		`createTime` datetime(0) NULL DEFAULT NULL,
		PRIMARY KEY (`bookmarkId`) USING BTREE,
		UNIQUE INDEX `opage`(`chapterId`, `page`) USING BTREE
		) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
	");
// 章节表
$link->query("
		CREATE TABLE IF NOT EXISTS `chapter`  (
		`chapterId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '章节记录',
		`mangaId` int(11) NULL DEFAULT NULL COMMENT '漫画id',
		`mediaId` int(11) NULL DEFAULT NULL COMMENT '媒体库id',
		`pathId` int(11) NULL DEFAULT NULL COMMENT '路径id',
		`chapterName` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '章节名称',
		`chapterPath` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '章节路径',
		`chapterType` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '文件类型',
		`browseType` enum('flow','single','double') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT 'flow' COMMENT '浏览方式',
		`chapterCover` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '章节封面',
		`picNum` int(11) NULL DEFAULT NULL COMMENT '图片数量',
		`createTime` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
		`updateTime` datetime(0) NULL DEFAULT NULL COMMENT '最新修改时间',
		PRIMARY KEY (`chapterId`) USING BTREE,
		UNIQUE INDEX `oname`(`mangaId`, `chapterName`) USING BTREE
	  ) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_unicode_ci ROW_FORMAT = Dynamic;
");
// 压缩表
$link->query("
		CREATE TABLE IF NOT EXISTS `compress`  (
		`compressId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '转换id',
		`compressType` enum('zip','rar','pdf','image') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '转换类型',
		`compressPath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '转换路径',
		`compressStatus` enum('uncompressed','compressing','compressed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '转换状态',
		`createTime` datetime(0) NULL DEFAULT NULL COMMENT '转换时间',
		`updateTime` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
		`imageCount` int(10) NULL DEFAULT NULL COMMENT '图片总数',
		`mediaId` int(11) NULL DEFAULT NULL COMMENT '媒体库id',
		`mangaId` int(11) NULL DEFAULT NULL COMMENT '漫画id',
		`chapterId` int(11) NULL DEFAULT NULL COMMENT '章节id',
		`chapterPath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '章节路径',
		`userId` int(11) NULL DEFAULT NULL COMMENT '用户标识',
		PRIMARY KEY (`compressId`) USING BTREE,
		UNIQUE INDEX `id`(`compressId`) USING BTREE,
		UNIQUE INDEX `oChapter`(`chapterId`) USING BTREE
	  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
	");
// 历史表
$link->query("
		CREATE TABLE IF NOT EXISTS `history`  (
		`historyId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '历史记录id',
		`userid` int(11) NULL DEFAULT NULL COMMENT '用户id',
		`mediaId` int(11) NULL DEFAULT NULL COMMENT '媒体库id',
		`mangaId` int(11) NULL DEFAULT NULL COMMENT '漫画id',
		`mangaName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '漫画名称',
		`chapterId` int(11) NULL DEFAULT NULL COMMENT '章节id',
		`chapterName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '章节名称',
		`chapterPath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '章节路径',
		`createTime` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
		PRIMARY KEY (`historyId`) USING BTREE
	  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
	");

// 登录表
$link->query("
		CREATE TABLE IF NOT EXISTS `login`  (
		`loginId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '登录记录',
		`userId` int(11) NULL DEFAULT NULL COMMENT '用户记录',
		`userName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户名',
		`nickName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '昵称',
		`request` int(1) NULL DEFAULT NULL COMMENT '0->成功 1->失败',
		`ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
		`loginTime` datetime(0) NULL DEFAULT NULL COMMENT '登录时间',
		PRIMARY KEY (`loginId`) USING BTREE
	  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
	");

// 漫画表
$link->query("
		CREATE TABLE IF NOT EXISTS `manga`  (
		`mangaId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '漫画id',
		`mediaId` int(11) NOT NULL COMMENT '媒体库id',
		`pathId` int(11) NULL DEFAULT NULL COMMENT '路径id',
		`mangaName` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '漫画名称',
		`mangaPath` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '漫画路径',
		`mangaCover` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '漫画封面',
		`chapterCount` int(255) NULL DEFAULT NULL COMMENT '章节总数',
		`browseType` enum('flow','single','double') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT 'flow' COMMENT '浏览方式',
		`createTime` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
		`updateTime` datetime(0) NULL DEFAULT NULL COMMENT '最后修改时间',
		`direction` int(1) NULL DEFAULT 1 COMMENT '翻页方向 0 左到右; 1右到左',
		`removeFirst` int(1) NULL DEFAULT 0 COMMENT '剔除首页 01',
		PRIMARY KEY (`mangaId`) USING BTREE,
		UNIQUE INDEX `oname`(`mediaId`, `mangaPath`) USING BTREE
	  ) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_unicode_ci ROW_FORMAT = Dynamic;
	");

// 媒体库表
$link->query("
		CREATE TABLE IF NOT EXISTS `media`  (
		`mediaId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '媒体库id',
		`mediaName` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '媒体库名称',
		`mediaType` int(1) NOT NULL COMMENT '媒体库类型 0->漫画 1->单本',
		`mediaCover` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '媒体库封面',
		`directoryFormat` int(1) NULL DEFAULT NULL COMMENT '目录格式 \r\n0 漫画 -> 章节 -> 图片\r\n1 目录 -> 漫画 -> 章节 -> 图片\r\n2 漫画 -> 图片\r\n3 目录 -> 漫画 -> 图片\r\n\r\n23为单本',
		`fileType` int(1) NULL DEFAULT NULL COMMENT '文件类型 0->图片 1->pdf',
		`defaultBrowse` enum('flow','single','double') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT 'flow' COMMENT '默认浏览类型',
		`createTime` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
		`updateTime` datetime(0) NULL DEFAULT NULL COMMENT '最新修改时间',
		`direction` int(1) NULL DEFAULT 1 COMMENT '翻页方向 0 左到右; 1右到左',
		`removeFirst` int(1) NULL DEFAULT 0 COMMENT '剔除首页 01',
		PRIMARY KEY (`mediaId`) USING BTREE,
		UNIQUE INDEX `nameId`(`mediaId`, `mediaName`) USING BTREE,
		UNIQUE INDEX `name`(`mediaName`) USING BTREE
	  ) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_unicode_ci ROW_FORMAT = Dynamic;
	");

// 路径表
$link->query("
		CREATE TABLE IF NOT EXISTS `path`  (
	  `pathId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '路径id',
	  `mediaId` int(11) NOT NULL COMMENT '媒体库id',
	  `path` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '路径',
	  `createTime` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
	  PRIMARY KEY (`pathId`) USING BTREE,
	  UNIQUE INDEX `opath`(`mediaId`, `path`) USING BTREE
	) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_unicode_ci ROW_FORMAT = Dynamic;
	");

// 用户表
$link->query("
		CREATE TABLE IF NOT EXISTS `user`  (
		`userId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户id',
		`userName` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '用户名',
		`passWord` char(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '密码',
		`nickName` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL COMMENT '昵称',
		`registerTime` datetime(0) NULL DEFAULT NULL COMMENT '注册时间',
		`updateTime` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
		PRIMARY KEY (`userId`, `userName`) USING BTREE,
		UNIQUE INDEX `username`(`userName`) USING BTREE
	  ) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_unicode_ci ROW_FORMAT = Dynamic;
	");

// 创建版本表
$link->query("
				CREATE TABLE IF NOT EXISTS `version` (
				`versionId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '版本记录',
				`versionDescribe` VARCHAR(255) NULL DEFAULT NULL COMMENT '版本描述',
				`version` varchar(255) NULL DEFAULT NULL COMMENT 'version number',
				`createTime` datetime(0) NULL DEFAULT NULL COMMENT 'createTime',
				`updateTime` datetime(0) NULL DEFAULT NULL COMMENT 'updateTime',
				PRIMARY KEY (`versionId`) USING BTREE,
				UNIQUE INDEX `version`(`version`) USING BTREE);
			");

// 插入默认用户名密码
$link->query("INSERT INTO `user` VALUES (1, 'smanga', 'f7f1fe7186209906a97756ff912bb644', NULL, NULL, NULL);");

// 314
if (array_search('3.1.4', $vers) === false) {
	$link->query("ALTER TABLE compress MODIFY COLUMN compressType enum('zip','rar','pdf','image','7z')");
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.1.4', '添加7z,修复shell参数', '2023-2-28 8:32:00', 'now()']
	]);
}

// 315
if (array_search('3.1.5', $vers) === false) {
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.1.5', '条漫模式新增书签支持', '2023-3-4 14:57:00', 'now()']
	]);
}

// 316
if (array_search('3.1.6', $vers) === false) {
	$link->query("ALTER TABLE user ADD `mediaLimit` varchar(255)");
	$link->query("ALTER TABLE user ADD `editMedia` int(1) DEFAULT 1");
	$link->query("ALTER TABLE user ADD `editUser` int(1) DEFAULT 1");
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.1.6', '新增用户权限管理', '2023-3-5 18:05:00', 'now()']
	]);
}

// 317
if (array_search('3.1.7', $vers) === false) {
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.1.7', '外置sql设置错误问题', '2023-3-18 00:27:31', 'now()']
	]);
}

// 318
if (array_search('3.1.8', $vers) === false) {
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.1.8', '新增视图切换功能, 解决文字展示不全的问题.', '2023-4-1 13:23:08', 'now()']
	]);
}

// 319
if (array_search('3.1.9', $vers) === false) {
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.1.9', '新增排序方式切换功能.', '2023-4-1 23:33:43', 'now()']
	]);
}

// 320
if (array_search('3.2.0', $vers) === false) {
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.2.0', '新增搜索功能;处理扫描错误.', '2023-4-5 21:02:03', 'now()']
	]);
}

// 321
if (array_search('3.2.1', $vers) === false) {
	// 创建个人设置表
	$sqlComm = "CREATE TABLE IF NOT EXISTS `config` (
				`configId` int(0) NOT NULL AUTO_INCREMENT COMMENT '设置项主键',
				`userId` int(0) NULL COMMENT '关联的用户id',
				`userName` varchar(255) NULL COMMENT '关联的用户名',
				`configValue` text NULL COMMENT '设置的详细内容 json打包',
				`createTime` datetime(0) NULL COMMENT '设置的创建时间',
				`updateTime` datetime(0) NULL COMMENT '设置的最近升级时间',
				PRIMARY KEY (`configId`),
				UNIQUE INDEX `id`(`userId`) USING BTREE COMMENT '用户id唯一'
			)";

	$link->query($sqlComm);

	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.2.1', '新增用户设置模块', '2023-4-22 18:12:57', 'now()']
	]);
}

// 322
if (array_search('3.2.2', $vers) === false) {
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.2.2', '修复缓存与排序的bug.', '2023-4-22 23:49:03', 'now()']
	]);
}

// 323
if (array_search('3.2.3', $vers) === false) {
	// 创建个人设置表
	$sqlComm = "CREATE TABLE IF NOT EXISTS `collect` (
				`collectId` int(0) NOT NULL AUTO_INCREMENT COMMENT '收藏id',
				`collectType` varchar(255) NULL COMMENT '收藏类型',
				`userId` int(0) NOT NULL COMMENT '用户id',
				`mediaId` int(0) NULL COMMENT '媒体库id',
				`mangaId` int(0) NULL COMMENT '漫画id',
				`mangaName` varchar(255) NULL COMMENT '漫画名称',
				`chapterId` int(0) NULL COMMENT '章节id',
				`chapterName` varchar(255) NULL COMMENT '章节名称',
				`createTime` datetime(0) NULL COMMENT '收藏日期',
				PRIMARY KEY (`collectId`),
				UNIQUE INDEX `uManga`(`collectType`, `mangaId`) USING BTREE COMMENT '漫画id不允许重复',
				UNIQUE INDEX `uChapter`(`collectType`, `chapterId`) USING BTREE COMMENT '章节id不允许重复'
			)";

	$link->query($sqlComm);

	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.2.3', '新增收藏模块', '2023-4-24 23:36:57', 'now()']
	]);
}


// 324
if (array_search('3.2.4', $vers) === false) {
	// 修改搜索表varchar字段 字符集为utf8mb4
	$link->query("
			ALTER TABLE `manga` 
			MODIFY COLUMN `mangaName` varchar(191) CHARACTER SET utf8mb4 NOT NULL COMMENT '漫画名称' AFTER `pathId`,
			MODIFY COLUMN `mangaPath` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '漫画路径' AFTER `mangaName`,
			MODIFY COLUMN `mangaCover` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL COMMENT '漫画封面' AFTER `mangaPath`;
		");

	$link->query("
			ALTER TABLE `chapter` 
			MODIFY COLUMN `chapterName` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL COMMENT '章节名称' AFTER `pathId`,
			MODIFY COLUMN `chapterPath` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL COMMENT '章节路径' AFTER `chapterName`,
			MODIFY COLUMN `chapterType` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL COMMENT '文件类型' AFTER `chapterPath`,
			MODIFY COLUMN `chapterCover` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL COMMENT '章节封面' AFTER `browseType`;
		");

	$link->query("
			ALTER TABLE `media` 
			MODIFY COLUMN `mediaName` varchar(191) CHARACTER SET utf8mb4 NOT NULL COMMENT '媒体库名称' AFTER `mediaId`,
			MODIFY COLUMN `mediaCover` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL COMMENT '媒体库封面' AFTER `mediaType`;
		");

	$link->query("
			ALTER TABLE `path` 
			MODIFY COLUMN `path` varchar(191) CHARACTER SET utf8mb4 NOT NULL COMMENT '路径' AFTER `mediaId`;
		");

	$link->query("
			ALTER TABLE `user` 
			MODIFY COLUMN `userName` varchar(191) CHARACTER SET utf8mb4 NOT NULL COMMENT '用户名' AFTER `userId`,
			MODIFY COLUMN `nickName` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL COMMENT '昵称' AFTER `passWord`,
			MODIFY COLUMN `mediaLimit` varchar(191) CHARACTER SET utf8mb4 NULL DEFAULT NULL AFTER `updateTime`;
		");

	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.2.4', '适配表情文字', '2023-5-1 02:13:55', 'now()']
	]);
}

// 323
if (array_search('3.2.5', $vers) === false) {
	dosql([
		'table' => 'version',
		'type' => 'insert',
		'field' => ['version', 'versionDescribe', 'createTime', 'updateTime'],
		'value' => ['3.2.5', '修改初始化流程', '2023-5-1 11:45:45', 'now()']
	]);
}



$configPath = getenv('SMANGA_CONFIG');

if (!$configPath) {
	exit_request([
		'code' => 1,
		'message' => '环境变量错误'
	]);
}

$installLock = "$configPath/install.lock";

if (is_file($installLock)) {
	// 记录版本 代表初始化结束
	write_txt('./version', '3.2.5');
} else {
	write_txt("$configPath/install.lock", 'success');
}

exit_request([
	'code' => 0,
	'initCode' => 2,
	'vers' => $vers,
	'message' => '初始化成功!'
]);
