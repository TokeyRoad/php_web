<?php
	define('WE_DEMO', 1);
	if (!defined('WE_DEMO')) {
		die('Unauthorized Access!'); 
	}

	// Tencent is pleased to support the open source community by making WeDemo available.
	// Copyright (C) 2016 THL A29 Limited, a Tencent company. All rights reserved.
	// Licensed under the MIT License (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at
	// http://opensource.org/licenses/MIT
	// Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

	/* !!! 请配置以下信息 !!! */

	//error_reporting(0);
	date_default_timezone_set('Asia/Shanghai');
	// SDK路径
	define('WX_SDK_PATH', __DIR__ . '/sdk/wxsdk/');
	define('QQ_SDK_PATH', __DIR__ . '/sdk/qqsdk/');
	define('ALIYUN_OSS_SDK_PATH', __DIR__ . '/sdk/aliyun/');
	define('TWITTER_SDK_PATH', __DIR__ . '/sdk/twitter/');
	//define('DYSMS_SDK_PATH', __DIR__ . '/sdk/dysms/api_sdk/vendor/');

	/* !!! 请配置以上信息 !!! */
	/* END file */
    
    require "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/info/config.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/info/error.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/info/define.php";
    require "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/info/xml_path.php";

    if (!config('DEBUG')) {
        error_reporting(0);
    }
    
    call();