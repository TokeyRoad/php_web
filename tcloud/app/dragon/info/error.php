<?php

class erron {
    const ERROR_UNKNOWN = -1;
    const ERROR_NO_ERROR = 0;
    const ERROR_DBSERVER_ERROR = 1;
    const ERROR_LOGIN_FAILD = 2;
    const ERROR_TOKEN = 3;
    const ERROR_HAS_BEEN_SIGN = 4;
    const ERROR_ROOKIE_SIGN_FINISHED = 5;
    const ERROR_REPEATED_BUY = 6;
}

class err_des {
    const ERROR_UNKNOWN = "System error";
    const ERROR_NO_ERROR = "Success";
    const ERROR_DBSERVER_ERROR = "Db error";
    const ERROR_LOGIN_FAILD = "Login faild";
    const ERROR_TOKEN = "token error";
    const ERROR_HAS_BEEN_SIGN = 'has been sign';
    const ERROR_ROOKIE_SIGN_FINISHED = 'rookie finish';
    const ERROR_REPEATED_BUY = "repeated buy";
}
