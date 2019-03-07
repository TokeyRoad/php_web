<?php
    class test extends action{
        public function __instance() {
            //dump($GLOBALS);
            $this->show();
        }

        public function __init() {
            if ($_POST == NULL) {
                $_POST = $_GET;
            }
        }
        
    }
