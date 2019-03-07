<?php
    class action extends base {
        public function __construct(){
            if($_POST == NULL){
                $_POST = $_GET;
            }
            // $account = $_POST['account'];
            // $ban_type = $_POST['ban_type'];
            // $token = $_POST['token'];
            // $time = time();
            // $game_redis = $this->getgameredis($account);
            // $res = $game_redis->get($account, "ban_account");
            // if($ban_type == NULL && $res != NULL && intval($res) > $time){
            //     $this->json_return(erron::ERROR_ACCOUNT_BAN, err_des::ERROR_ACCOUNT_BAN, $res);
            // }
            // if($token != NULL){
            //     if($game_redis->get($account,"token") != $token ){
            //         $this->json_return(erron::ERROR_TOKEN, err_des::ERROR_TOKEN, NULL);
            //     }
            // }
        }
        protected function send($ip, $port, $msg) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if (null == $socket || !socket_connect($socket, $ip, $port)) {
                return null;
            }
            
            socket_write($socket, $msg);
            $len = socket_recv($socket, $buff, 4, MSG_WAITALL);
            if ($len < 4) {
                socket_close($socket);
                return null;
            }
            $size = (int)$buff;
            $len = socket_recv($socket, $res, $size, MSG_WAITALL);
            if ($len != $size) {
                socket_close($socket);
                return null;
            }
            return $res;
        }
        
        protected function show($tpl) {
            if ($tpl == null) {
                $tpl = "index.html";
            }
            $path = "{$_SERVER['DOCUMENT_ROOT']}/app/{$GLOBALS['app']}/tpl/{$GLOBALS['class']}/{$tpl}";
            
            $content = file_get_contents($path);
            echo $content;
        }
        
        protected function get_auth() {
            $values = $_POST;
            if ($values == null) {
                $values = $_GET;
            }
            
            if (is_null($info) || $info->token != $values["token"]) {
                $this->json_return(erron::ERROR_TOKEN_ERROR, err_des::ERROR_TOKEN_ERROR, null);
            }
            
            return $info;
        }
                
        protected function getaccountdb() {
            $config = config('ACCOUNT_DB');
            return new db($config['MYSQL_DSN'], $config['MYSQL_USERNAME'], $config['MYSQL_PASSWORD'], config('PDO_OPTIONS'));
        }
        
        protected function getgamedb($account) {
            $game_dbs = config('GAME_DB');
            $config = $game_dbs[$account % sizeof($game_dbs)];
            return new db($config['MYSQL_DSN'], $config['MYSQL_USERNAME'], $config['MYSQL_PASSWORD'], config('PDO_OPTIONS'));
        }

        protected function getaccountredis(){
            $account_redis = config('ACCOUNT_REDIS');
            return new redisx($account_redis['REDIS_IP'], $account_redis['REDIS_PORT'], $account_redis['REDIS_AUTH']);
        }

        protected function getgameredis($account) {
            $game_redis = config('GAME_REDIS');
            $config = $game_redis[$account % sizeof($game_redis)];
            return new redisx($config['REDIS_IP'], $config['REDIS_PORT'], $config['REDIS_AUTH']);
        }
        protected function get_game_redis_index($account){
            $game_redis = config('GAME_REDIS');
            return $account % sizeof($game_redis);
        }

        protected function querydb($account, $sql) {
            $db = $this->getgamedb($account);
            return $db->query($sql);
        }
        
        protected function call($account, $name, $args) {
            $db = $this->getgamedb($account);
            $sql = "CALL {$name}({$args})";
            return $db->query($sql);
        }
        
        public static function tranParams($obj){ 
            if(!get_magic_quotes_gpc()){
                if(is_array($obj)){ 
                    foreach($obj as $key => $val){
                        $obj[$key] = self::tranparams($val);
                    }
                }else{
                    $obj = addslashes($obj);
                }
            }
            return $obj;
        }
        
        public static function tranRequest(){
            global $_POST;
            global $_GET;
            global $_REQUEST;
            global $_SESSION;
            if(!get_magic_quotes_gpc()){
                $_POST=self::tranParams($_POST);
                $_GET=self::tranParams($_GET);
                $_REQUEST=self::tranParams($_REQUEST);
                $_SESSION=self::tranParams($_SESSION);
            }
        }
    }
