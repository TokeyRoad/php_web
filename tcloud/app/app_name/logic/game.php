<?php
    class game extends action{
        
        public function __init() {
            // if ($_POST == NULL) {
            //     $_POST = $_GET;
            // }
        }
        
        public function proc() {
            $proc = $_POST['proc'];
            $account = $_POST['_account'];
            
            $ary = explode("*", $_POST['args']);
            
            $args = NULL;
            foreach ($ary as $arg){
                if ($args != null) {
                    $args = $args . ",";
                }
                $args = $args. "'{$arg}'";
            }
            $res = $this->call($account, $proc, $args);            
	        $this->json_return(erron::ERROR_NO_ERROR, err_des::ERROR_NO_ERROR, $res[0]);
        }
    }
