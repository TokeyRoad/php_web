<?php 
    $link=mysql_connect("localhost","root","root"); 
    if(!$link) {
        die(mysql_error());
    } else {
        echo "link ok";
    }
 

