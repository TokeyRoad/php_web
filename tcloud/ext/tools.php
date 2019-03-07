<?php
const Day = 86400;
function GetModule($path_info) {
    //analyze path info, get dir,class and function
    $dir_path = null;
    $class_name = null;
    $path_elm = explode("/", $path_info);
    foreach ($path_elm as $elm) {
        $dir_path = "{$dir_path}/{$class_name}";
        $class_name = $elm;
    }

    //get object and call the fun
    $src_file = "{$dir_path}/{$class_name}.php";
    if (is_file(".{$src_file}")) {
        include_once(".$src_file");
        return new $class_name;
    } else {
        log_error("error path info, {$src_file} dose not exist");
    }
}

function GetUrlInfo($url) {
    $elm = explode("/", $url, 4);
    $GLOBALS['app'] = $elm[1];
    $GLOBALS['class'] = $elm[2];
    if(count($elm) > 3){
        $GLOBALS['function'] = $elm[3];
    }
    return $GLOBALS;
}

function CreateToken($guid, $platform, $account) {
    $now = time();
    return md5($guid.$platform.$account.$now);
}

function call() {
    //get object and call the fun
    
    $file = "/app/{$GLOBALS['app']}/logic/{$GLOBALS['class']}.php";
    
    if (is_file(".$file")) {
        include(".{$file}");
        if (class_exists($GLOBALS['class'])) {
            $module = new $GLOBALS['class'];
            if ($module != null) {
                if (method_exists($module, __init)) {
                    $module->__init();
                }
                if (strpos($GLOBALS['function'],"trigger_") !== 0 && method_exists($module, $GLOBALS['function'])) {
                    $function = $GLOBALS['function'];
                    $module->$function();
                	//call_user_func_array(array($module, $GLOBALS['function']), []);
                } else {
                    if (strpos($GLOBALS['function'],"trigger_") === 0 || $GLOBALS['function'] != null) {
                        log_error("module class <{$GLOBALS['class']}> has no function named {$GLOBALS['function']}");
                    } else {
                        $module->__instance();
                    }
                }
            }
        } else {
            log_error("module class <{$class_name}> is not defined");
        }
    } else {
        log_error("error path info, {$file} dose not exist");
    }
}

function dump($var, $echo = true, $label = null, $strict = true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else {
        return $output;
    }
}

function log_trace($log) {
    $file = "{$_SERVER['DOCUMENT_ROOT']}/log/" . date('Y_m_d_H') . "_trace" . ".log";
    file_put_contents($file, "{$GLOBALS['class']}:{$GLOBALS['function']} | " . date('Y-m-d H:i:s') . " | {$log}" . "\n", FILE_APPEND);
}

function log_gm_trace($log) {
    $file = "{$_SERVER['DOCUMENT_ROOT']}/trace_log/" . date('Y_m_d_H') . "_trace" . ".log";
    file_put_contents($file, "{$GLOBALS['class']}:{$GLOBALS['function']} | " . date('Y-m-d H:i:s') . " | {$log}" . "\n", FILE_APPEND);
}

function log_debug($log) {
    $file = "{$_SERVER['DOCUMENT_ROOT']}/log/" . date('Y_m_d_H') . "_debug" . ".log";
    file_put_contents($file, "{$GLOBALS['class']}:{$GLOBALS['function']} | " . date('Y-m-d H:i:s') . " | {$log}" . "\n", FILE_APPEND);
}

function log_error($log) {
    $file = "{$_SERVER['DOCUMENT_ROOT']}/log/" . date('Y_m_d_H') . "_error" . ".log";
    file_put_contents($file, "{$GLOBALS['class']}:{$GLOBALS['function']} | " . date('Y-m-d H:i:s') . " | {$log}" . "\n", FILE_APPEND);
}

function log_important($line, $from_id, $account, $type, $amount, $param = 0, $param_res = 0){
    $file = "{$_SERVER['DOCUMENT_ROOT']}/log/" . date('Y_m_d_H') . "_important" . ".log";
    file_put_contents($file, "{$GLOBALS['class']}:{$GLOBALS['function']} | " . date('Y-m-d H:i:s') . "| {$line}"." | {$from_id}". " | {$account}". " | {$type}" ." | {$amount}" ." | {$param}"." | {$param_res}"."\n", FILE_APPEND);
}

function log_db($o_log,$c_log) {
    $t_log = time();
    $u_log = $_SESSION["username"];
    $res = array("time"=>$t_log,"user"=>$u_log,"operate"=>$o_log,"content"=>$c_log);
    return $res;
}

//把xml文件转换为array，把节点的属性也作为改节点的数组元素
//args:$path xml文件路径
//res:array
function xml2array($path) {
    $xml = simplexml_load_file ($path); 
    $str = json_encode($xml);
    $needle = '"@attributes":{';
    $k = stripos($str, $needle);
    while($k) {
        $str2 = substr_replace($str, "", $k, 15);
        $k2 = stripos($str2,"}",$k);
        $str = substr_replace($str2,"", $k2, 1);
        $k = stripos($str, $needle);
    }
    $arr = json_decode($str,TRUE);
    return $arr;
}

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec) * 10000;
}

//$file : file tmp info
function save_file($file, $path, $extensions) {
    $name = microtime_float() . "_" . md5($file) . $extensions;
    if(move_uploaded_file($file, "{$_SERVER['DOCUMENT_ROOT']}{$path}/{$name}")) {
        log_trace("move_uploaded_file {$file} to {$_SERVER['DOCUMENT_ROOT']}{$path}/{$name} success");
        $url = "{$path}/{$name}";
        return $url;
    }
    
    log_error("move_uploaded_file {$file} to {$_SERVER['DOCUMENT_ROOT']}{$path}/{$name} error");
    return null;
}

function showtpl($url) {
    if ($url == null) {
        $url = "404.html";
    }
    $elm = explode(".", $url);
    $type= $elm[count($elm) - 1];
    if($type == "css") {
        header('Content-type: text/css');  
    } else if($type == "js"){
        header('Content-type: text/javascript');  
    } else {
    }
    $path = "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/tpl/{$url}";
    $content = file_get_contents($path);
    echo $content;
}

// function loadxlsx($path, $excel) {
    // require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel.php";
    // require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel/IOFactory.php";
    // require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel/Reader/Excel2007.php";
    // require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel/Reader/Excel5.php";

    // $inputFileType = PHPExcel_IOFactory::identify("{$_SERVER['DOCUMENT_ROOT']}{$path}/{$excel}"); 
    // $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
    // $objPHPExcel = $objReader->load("{$_SERVER['DOCUMENT_ROOT']}{$path}/{$excel}"); 
    // $sheet = $objPHPExcel->getSheet(0);
    // $highestRow = $sheet->getHighestRow();           //取得总行数 
    // $highestColumn = $sheet->getHighestColumn();        //取得总列数
    // $res=array();
    // for($j=2;$j<=$highestRow;$j++) {                      //从第二行开始读取数据
        // $str="";
                // for($k='A',$i=0;$k<=$highestColumn;$k++,$i++)            //从A列读取数据
                // { 
                    // $str =$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();//读取单元格
                    // $str = str_replace(array("\r\n", "\r", "\n"), " ", $str); 
                    // $res[$j-2][$i]=$str;
                // } 
        // $str=mb_convert_encoding($str,'utf-8','auto');//根据自己编码修改
        // $strs = explode("|*|",$str);
    // };
    // return $res;
// }

function ajaxloadxlsx($path, $excel) {
    require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel.php";
    require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel/IOFactory.php";
    require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel/Reader/Excel2007.php";
    require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/PHPExcel/PHPExcel/Reader/Excel5.php";

    $inputFileType = PHPExcel_IOFactory::identify("{$_SERVER['DOCUMENT_ROOT']}{$path}/{$excel}"); 
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);  
    $objPHPExcel = $objReader->load("{$_SERVER['DOCUMENT_ROOT']}{$path}/{$excel}"); 
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();           //取得总行数 
    $highestColumn = $sheet->getHighestColumn();        //取得总列数
    $res=array();
    for($j=2;$j<=$highestRow;$j++) {                      //从第二行开始读取数据
        $str="";
                for($k='A',$i=0;$k<=$highestColumn;$k++,$i++)            //从A列读取数据
                { 
                    $str = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();//读取单元格
                    $str = str_replace(array("\r\n", "\r", "\n"), " ", $str);
                    $res[$j-2][$i]=$str;
                } 
        // $str=mb_convert_encoding($str,'utf-8','auto');//根据自己编码修改
        // $strs = explode("|*|",$str);
    };
    return $res;
}

function add_htmlheader(){
    if(strpos($GLOBALS['function'],"html") != 0 || strpos($GLOBALS['function'],"htm") != 0|| strpos($GLOBALS['function'],"jsp") != 0){
        header('Content-type: text/html');
        return;
    }
    if(strpos($GLOBALS['function'],"txt") != 0){
        header('Content-type: text/plain');
        return;
    }
    if(strpos($GLOBALS['function'],"xlsx") != 0){
        header('Content-type: application/xlsx');
        return;
    }
    if(strpos($GLOBALS['function'],"asp") != 0){
        header('Content-type: text/asp');
        return;
    }
    if(strpos($GLOBALS['function'],"avi") != 0){
        header('Content-type: video/avi');
        return;
    }
    if(strpos($GLOBALS['function'],"mp4") != 0){
        header('Content-type: video/mp4');
        return;
    }
    if(strpos($GLOBALS['function'],"css") != 0){
        header('Content-type: text/css');
        return;
    }
    if(strpos($GLOBALS['function'],"gif") != 0){
        header('Content-type: image/gif');
        return;
    }
    if(strpos($GLOBALS['function'],"jpeg") != 0 || strpos($GLOBALS['function'],"jpg") != 0){
        header('Content-type: image/jpeg');
        return;
    }
    if(strpos($GLOBALS['function'],"img") != 0){
        header('Content-type: application/x-img');
        return;
    }
    if(strpos($GLOBALS['function'],"xml") != 0){
        header('Content-type: text/xml');
        return;
    }
    if(strpos($GLOBALS['function'],"json") != 0){
        header('Content-type: text/json');
        return;
    }
    header('Content-type: application/octet-stream');
}


function parse_config_xml($path,$key,$index) {
    $xml = simplexml_load_file($path);
    foreach($xml->children() as $child){
        foreach ($child->attributes() as $k => $v) {
            $cvalue = json_decode(json_encode($v),true);
            $array[$k]=$cvalue[0];
        }
      
        if($array[$key] == $index){
            return $array;
        }
    }
    return null;
}

function index_config_xml($path,$key,$index) {
    $xml = simplexml_load_file($path);
    $value;
    foreach($xml->children() as $child){
        foreach ($child->attributes() as $k => $v) {
            $cvalue = json_decode(json_encode($v),true);
            $array[$k]=$cvalue[0];
        }
      
        if($array[$key] == $index){
            $value[] = $array;
        }
    }
    return $value;
}

function xml_to_array($path){
    $xml = simplexml_load_file($path);
    $value;
    foreach($xml->children() as $child){
        foreach ($child->attributes() as $k => $v) {
            $cvalue = json_decode(json_encode($v),true);
            $array[$k]=$cvalue[0];
        }
        $value[] = $array;
    }
    return $value;
}

function array_to_kvi($array){
    $new_array = array();
    foreach ($array as $key => $value) {
        $size = count($new_array);
        $new_array[$size]["k"] = $key;
        if(is_array($value)){
            $new_array[$size]["v"] = array_to_kvi($value);
        }
        else{
            $new_array[$size]["v"] = $value;
        }
    }
    return $new_array;
}
function check_day($cur_time, $last_time){
    $cur = date("Y-m-d",$cur_time);
    $last = date("Y-m-d",$last_time);
    if($cur == $last)
        return true;
    else 
        return false;
}

function check_month($cur_time, $last_time){
    $cur = date("Y-m",$cur_time);
    $last = date("Y-m",$last_time);
    if($cur == $last)
        return 1;
    else 
        return 0;
}

function getMillisecond() { 
    list($s1, $s2) = explode(' ', microtime()); 
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000); 
}

function key_fun($str1){
    if(file_exists("{$_SERVER['DOCUMENT_ROOT']}/ext/sensitiveWord.php")){
        require_once "{$_SERVER['DOCUMENT_ROOT']}/ext/sensitiveWord.php";
    }
    if(substr_count($str1, " ") > 0){
        return true;
    }

    foreach ($key_arr_one as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_two as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_three as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_four as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_five as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_six as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_seven as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_eight as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_nine as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_ten as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    foreach ($key_arr_eleven as $child) {
        if(substr_count($str1, $child) > 0){
            // echo $str1."\n";
            // echo $child."error<br>";
            return true;
        }
    }
    return false;
}

function getDayBegin($time = null){
    if($time == NULL){
        return strtotime(date('Y-m-d',time()));
    }else{
        return strtotime(date('Y-m-d',$time));
    }
}
function getDifDays($start, $end){//时间戳
    if($end > $start){
        $date = floor(($end - $start) / Day);
        return $date;
    }else{
        return -1;
    }
}
function less_day(){
    $month_big = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    //现在的月份
    $date_month_old = (int)date('m',time());
    //下个月的月份
    $date_month_new = $date_month_old + 1;
    //下个月1号的时间戳
    $date_time_new = strtotime('1 '.$month_big[$date_month_new-1].' '.date('Y',time()));
    //今天的时间戳
    $date_time_old = strtotime(date('d',time()).' '.$month_big[$date_month_old-1].' '.date('Y',time()));
    //距下月剩余时间
    $time_new = ($date_time_new - $date_time_old)/24/60/60;
    return $time_new;
}
function get_device(){
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        return 1;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        return 2;
    }else{
        return -1;
    }
}
function http_post($url, $body=null, $headers=null, $verifyPeer=false) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    if ($body) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    }
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 1000*15);
    if ($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $verifyPeer);
    $response = curl_exec($curl);
    $curlInfo = curl_getinfo($curl);
    curl_close($curl);
    return $response;
}
