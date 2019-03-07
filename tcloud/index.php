<?php
    require "{$_SERVER['DOCUMENT_ROOT']}/ext/tools.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/ext/base.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/ext/action.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/ext/mem.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/ext/db.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/ext/redis.php";
    // require "{$_SERVER['DOCUMENT_ROOT']}/ext/phpmailer/class.phpmailer.php";
    // require "{$_SERVER['DOCUMENT_ROOT']}/app/sdk/supersdk/Common.php";
    // require "{$_SERVER['DOCUMENT_ROOT']}/app/sdk/supersdk/HttpHelper.php";    //Linux
    // echo $_SERVER['PATH_INFO'];
    if ($_SERVER['PATH_INFO'] == "/") {
        echo "welcome, this is a test website!";
	return;
    }
    GetUrlInfo($_SERVER['PATH_INFO']);
    //Windows
    // require "/opt/tcloud/ext/tools.php";
    // dump($_SERVER);
    require "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/instance.php";
    // GetUrlInfo($_SERVER['PATH_INFO']);  //Linux
    // if ($GLOBALS['app'] != null) {
        // if (strpos($GLOBALS['class'],"tpl") === 0) {
            // add_htmlheader();
            // // header('Content-type: text/html');  
            // showtpl($GLOBALS['function']);
        // }
        // else {
            // header("Access-Control-Allow-Origin: http://localhost:7456");
            // require "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/instance.php";
        // }
    // }
