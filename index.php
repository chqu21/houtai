<?php
/*
$rs = array(
     "member_id"=>"523",
     "comment_type"=>"0",
     "member_name"=> "",
     "class_time"=>"2.00",
     "uploadedfile"=> "",
     "order_code"=>"201505272016045928398021"
);

$rs['praiselist'] = array(
    'environment' => array(
        'score'=>10,
        'content'=>'评价11'

    ),
    'teaching_process'=>array(
        'score'=>18,
        'content'=>'评价12'
    ),
    'teaching_effect'=>array(
        'score'=>13,
        'content'=>'评价13'
    ),
    'service_quality'=>array(
        'score'=>10,
        'content'=>'评价1'
    ),
    'homework_questions'=>array(
        'score'=>10,
        'content'=>'评价55'
    )
);
echo json_encode($rs);
exit;
*/
// 检测PHP环境
if(version_compare(PHP_VERSION, '5.3.0','<'))  die('require PHP > 5.3.0 !');

define('DS', DIRECTORY_SEPARATOR);
// 站点目录
define('SITE_DIR', dirname(__FILE__));
// 站点地址
define('SCRIPT_DIR', rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/\\'));
define('SITE_URL', $_SERVER['HTTP_HOST'] . SCRIPT_DIR);
//来源页面
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
//文件上传根目录
define('UPLOAD_PATH', './Public/upload/');

// ThinkPHP定义
define('APP_DEBUG', true);
define('THINK_PATH', SITE_DIR . DS . 'Libs' . DS . 'ThinkPHP' . DS);
define('APP_PATH', SITE_DIR . DS . 'App' . DS);
define('RUNTIME_PATH', SITE_DIR . DS . 'Public' . DS . 'Runtime' . DS);   // 系统运行时目录

require(THINK_PATH.'ThinkPHP.php');