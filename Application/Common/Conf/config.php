<?php
require_once('constants.php');
return array(
    //'配置项'=>'配置值'
    'DEFAULT_MODULE'  => 'Home',  // 默认模块
    // 'LOAD_EXT_CONFIG' => 'constants',//加载扩展配置文件,如果在这儿加载的话读取不了constants.php内容
    /* 数据库设置 */
    'DB_TYPE' => 'mysql',     // 数据库类型
    'DB_HOST' => DB_HOST, // 服务器地址
    'DB_NAME' => DB_NAME,          // 数据库名
    'DB_USER' => DB_USER,      // 用户名
    'DB_PWD' => DB_PWD,          // 密码
    'URL_MODEL' => 1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
);