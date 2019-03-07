<?php
    const picture_way = "/app/dragon/tpl/static/picture";
    const oss_endpoint = "oss-cn-beijing.aliyuncs.com";
    const oss_bucket = "dragon-trunk";
    const version_history_way = "/opt/server/tcloud/app/dragon/tpl/static/history/";
    class gm extends action {

        public function __instance(){
            $this->show('login.html');
        }
        public function __init() {
            if ($_POST == NULL) {
               $_POST = $_GET;
            }
            if($GLOBALS['function'] == 'login.html') {
                $this->show('login.html');
                return;
            }
        }
        public function login() {
            $username = $_POST['username'];
            $passwd = $_POST['password'];
            if($username == NULL || $passwd == NULL){
                $this->show('login.html');
                return;
            }
            $db = $this->getaccountdb();
            $res = $db->query("SELECT * from admininfo where username = '$username' and passwd = password('$passwd')");
            if($res != NULL) {
                session_start();
                $_SESSION["username"] = $username;
                // echo $_SESSION["username"];
                $this->show(null);
            } else {
                $this->show('login.html');
            }
        }
        public function version() {
            session_start();
            if($_POST['msg'] == NULL){
                $this->json_return(erron::ERROR_UNKNOWN, err_des::ERROR_UNKNOWN, NULL);
            }
            $msg = $_POST['msg'];
            // echo $msg;
            // echo path::version_file_path;
            $myfile = fopen(path::version_file_path, "w");
            if(!$myfile) {
                $this->json_return(erron::ERROR_UNKNOWN, err_des::ERROR_UNKNOWN, NULL);
            }
            fwrite($myfile, $msg);
            fclose($myfile);
            
            $filebak=microtime_float().".json";
            // echo $filebak;
            $iscopy = copy(path::version_file_path,version_history_way.$filebak);
            // dump($iscopy);
            $log=log_db("游戏版本",$filebak);
            $db = $this->getaccountdb();
            $res_log = $db->query("INSERT INTO operating_record (operating_record.time,user,operate,content) VALUES ('$log[time]','$log[user]','$log[operate]','$log[content]')");  //操作日志
            $this->oss_upload();
            $this->json_return(erron::ERROR_NO_ERROR, err_des::ERROR_NO_ERROR, NULL);
        }
        public function mailgm() {
            session_start();
            if($_POST['type'] == NULL || $_POST['title'] == NULL || $_POST['content'] == NULL){
                $this->json_return(erron::ERROR_UNKNOWN, err_des::ERROR_UNKNOWN, NULL);
            }
            if($_POST['type'] == 0 && $_POST['targets'] == NULL) {
                $this->json_return(erron::ERROR_UNKNOWN, err_des::ERROR_UNKNOWN, NULL);
            }
            // $msg = "mail --type={$_POST['type']} --targets={$_POST['targets']} --title={$_POST['title']} --content={$_POST['content']} --attachment={$_POST['attachment']}\n";
            // $console = config('GM_SERVER');
            // $res = $this->send($console[ip], $console[port], $msg);
            //log_trace($msg . $res);
            $record_arr=array();
            if("{$_POST['type']}"==0){
                $record_arr[0]="个人邮件";
                $record_arr[1]="{$_POST['targets']}";
                $record_arr[2]="{$_POST['title']}";
                $record_arr[4]="{$_POST['content']}";
            }else if("{$_POST['type']}"==1){
                $record_arr[0]="全服邮件";
                $record_arr[1]="所有玩家";
                $record_arr[2]="{$_POST['title']}";
                $record_arr[4]="{$_POST['content']}";
            }
            $attachment[0]="{$_POST['attachment']}";
            if($attachment[0] != NULL){
                switch ($attachment[0][0]){
                    case reward_type::gold:{
                        $attachment[1]="金币";
                        break;
                    }
                    case reward_type::diamond:{
                        $attachment[1]="钻石";
                        break;
                    }
                    case reward_type::chip:{
                        $attachment[1]="芯片";
                        break;
                    }
                    case reward_type::tank:{
                        $attachment[1]="坦克";
                        break;
                    }
                    default:$attachment[1]="error";
                }
                $arr = explode(",", $attachment[0]);
                
                if($arr[1] == 0 || $arr[1] == NULL){
                    $record_arr[3]=$arr[0].",".$arr[2];
                    $item = strval($arr[0].",".$arr[2]);
                }else{
                    if(intval($arr[0]) == reward_type::chip){
                        $chip_res = parse_config_xml(path::chip_xml_path,"chip_id",$arr[1]);
                        if($chip_res == null){
                            $this->json_return(erron::ERROR_UNKNOWN, err_des::ERROR_UNKNOWN, NULL);
                        }
                    }else if(intval($arr[0]) == reward_type::tank){
                        $tank_res = parse_config_xml(path::role_xml_path,"tank_id",$arr[1]);
                        if($tank_res == null){
                            $this->json_return(erron::ERROR_UNKNOWN, err_des::ERROR_UNKNOWN, NULL);
                        }
                    }                    
                    $record_arr[3]=$arr[0].",".$arr[1].",".$arr[2];
                    $item = strval($arr[0].",".$arr[1].",".$arr[2]);
                }
            }else{
                $item = "";
            }
            $record= "邮件类型:".$record_arr[0].",收件人:".$record_arr[1].",标题:". $record_arr[2].",附件:".$record_arr[3].",内容:".$record_arr[4];

            date_default_timezone_set('Asia/Shanghai');
            $cur_time = date(time());

            $log=log_db("邮件",$record);
            $account_db = $this->getaccountdb();
            $res = NULL;
            if("{$_POST['type']}"==1){
                $res = $account_db->call("sp_add_global_mail","'{$cur_time}','{$record_arr[2]}','{$record_arr[4]}','{$item}'");
            }else if("{$_POST['type']}"==0){
                $re = explode(",", $record_arr[1]);
                //dump($re);
                for($i = 0; $i < count($re); $i++){
                    $game_redis = $this->getgameredis($re[$i]);
                    $player = json_decode($game_redis->get($re[$i], "player"), true);
                    if($player == NULL){
                        continue;
                    }
                    $game_db = $this->getgamedb($re[$i]);
                    $mai_account = $re[$i];
                    $res = $game_db->call("sp_add_mail","'{$mai_account}','{$record_arr[2]}','{$record_arr[4]}','{$item}','{$cur_time}'");
                }
            }
            if($res != NULL) {
                $res_log = $account_db->query("INSERT INTO operating_record (operating_record.time,user,operate,content) VALUES ('$log[time]','$log[user]','$log[operate]','$log[content]')");  //操作日志
                $this->json_return(erron::ERROR_NO_ERROR, err_des::ERROR_NO_ERROR, NULL);
            } else {
                $this->json_return(erron::ERROR_UNKNOWN, err_des::ERROR_UNKNOWN, NULL);
            }
        }
        private function oss_upload(){
            if (file_exists(ALIYUN_OSS_SDK_PATH . 'autoload.php')) {
                require_once ALIYUN_OSS_SDK_PATH . 'autoload.php';
            } else {
                $this->json_return(erron::ERROR_LOGIN_FAILD, err_des::ERROR_LOGIN_FAILD, NULL);
            }            
            $bucket=oss_bucket;
            $ossClient = new \OSS\OssClient(ACCESSKEYID, ACCESSKEYSECRET, oss_endpoint);
            //判断bucketname是否存在，不存在就去创建
            if( !$ossClient->doesBucketExist($bucket)){
                $ossClient->createBucket($bucket);
            }
            $file = path::version_file_path;
            $object = "version.json";//想要保存文件的名称
            //$file = $url;//文件路径，必须是本地的。
            //$file = "./Uploads/Uploads/2017-07-24/5975c17ec4d9d.jpg";
            try{
                $ossClient->uploadFile($bucket,$object,$file);
                // if (isunlink==true){
                //     unlink($file);
                // }
            }catch (OssException $e){
                $e->getErrorMessage();
            }
        }
    }
