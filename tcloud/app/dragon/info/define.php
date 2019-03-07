<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class regular {
    const phone = "/1[34578]{1}\d{9}$/";
    const email = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
}

class common{
	const challenge_times = 3;
}

class rediskey{
	const token = "token";
}

